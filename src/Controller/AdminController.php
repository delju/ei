<?php

namespace App\Controller;


use App\Entity\Adoption;
use App\Entity\Animals;
use App\Entity\Message;
use App\Entity\Treatment;
use App\Form\AdoptionType;
use App\Form\AnimalsType;
use App\Form\MessageType;
use App\Form\TreatmentType;
use App\Repository\AnimalsRepository;
use App\Repository\MessageRepository;
use App\Search\Search;
use App\Search\SearchFullType;
use App\Service\PhotoUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin-home", name="admin-home")
     */
    public function Administration(Request $request, EntityManagerInterface $em, MessageRepository $messageRepository): Response
    {
        $messages = $messageRepository->findBy([], ['date' => 'desc']);

        //Création d'un nouveau message a partir du formulaire MessageTYPE dans la page contact
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($message);
            $em->flush();
            return $this->redirectToRoute('admin-home');
        }
        return $this->render('pages/admin/messages.html.twig', ['messages' => $messages, 'messageForm' => $form->createView()]);
    }

    /**
     * @Route("/admin-lodgers", name="admin-lodgers")
     */
    public function Lodgers(AnimalsRepository $animalsRepository, Request $request): Response
    {
        //Création d'un recherche
        $search = new Search();
        //Retourne le formulaire de recherche
        $form = $this->createForm(SearchFullType::class, $search);
        $form->handleRequest($request);
        $result = [];
        //Si le formulaire est envoyé et valide, on effectue la recherche selon les critères de findbysearch
        if ($form->isSubmitted() && $form->isValid()) {
           $result = $animalsRepository->findBySearch($search);
        }


        return $this->render('pages/admin/lodgers.html.twig', ['lodgers' => $result, 'searchFullForm'=>$form->createView()]);
    }

    /**
     * @Route("/admin-single/{slug}", name="admin-single")
     */

    public function Single(string $slug, AnimalsRepository $animalsRepository,Request $request, EntityManagerInterface $em): Response
    {
//    Trouver l'animal selon son slug.
        $single = $animalsRepository->findWithAll($slug);

// Afficher le formulaire d'ajout d'un soin
        $cares = new Treatment();
        $cares->setAnimals($single);
        $formCares = $this->createForm(TreatmentType::class, $cares);
        $formCares->handleRequest($request);
        if ($formCares->isSubmitted() && $formCares->isValid()) {
            $em->persist($cares);
            $em->flush();
            return $this->redirectToRoute('admin-single', ['slug' => $single->getSlug()]);
        }

        return $this->render('pages/admin/single.html.twig', ['single' => $single, 'caresForm'=>$formCares->createView()]);
    }

    /**
     * @Route("/admin-lodgers/add-lodgers", name="add-lodgers")
     */
    public function AddLodgers(Request $request, EntityManagerInterface $em, PhotoUploader $photoUploader): Response
    {
        /* Création d'un nouvel animal */
        $animals = new Animals();
        /* Création d'un formulaire à partir de AnimalsType */
        $formAnimals = $this->createForm(AnimalsType::class, $animals);
        $formAnimals->handleRequest($request);
        if ($formAnimals->isSubmitted() && $formAnimals->isValid()) {
            foreach ($formAnimals->get('gallery')->get('photos') as $photoData) {
                $photo = $photoUploader->uploadPhoto($photoData);
                $animals->getGallery()->addPhoto($photo);
                $em->persist($photo);
            }

            $em->persist($animals->getGallery());
            $em->persist($animals);
            $em->flush();
            return $this->redirectToRoute('admin-lodgers', ['slug' => $animals->getSlug()]);
        }
        return $this->render('pages/admin/form/add-lodgers.html.twig', ['animalsForm' => $formAnimals->createView()]);
    }

    /**
     * @Route("/admin-edit/{slug}", name="edit-lodger")
     */

    public function EditLodger(string $slug, AnimalsRepository $animalsRepository, Request $request, PhotoUploader $photoUploader, EntityManagerInterface $em): Response
    {
        $lodger = $animalsRepository->findOneBySlug($slug);
        $formAnimals = $this->createForm(AnimalsType::class, $lodger);
        $formAnimals->handleRequest($request);

        if ($formAnimals->isSubmitted() && $formAnimals->isValid()) {
            foreach ($formAnimals->get('gallery')->get('photos') as $photoData) {
                $photo = $photoUploader->uploadPhoto($photoData);
                if ($photo != null) {
                    $photo->setGallery($lodger->getGallery());
                    $em->persist($photo);
                }
            }
            $em->persist($lodger->getGallery());
            $em->persist($lodger);
            $em->flush();
            return $this->redirectToRoute('single', ['slug' => $lodger->getSlug()]);
        }
        return $this->render('pages/admin/form/add-lodgers.html.twig', ['animalsForm' => $formAnimals->createView()]);
    }

    /**
     * @Route("/admin-delete/{id<\d+>}", name="delete-lodger")
     */
    public function DeleteLodger(int $id, AnimalsRepository $animalsRepository, EntityManagerInterface $em): Response
    {

        //On récupère le manga selon son id et on le supprime
        $lodger = $animalsRepository->find($id);
        $em->remove($lodger);
        $em->flush();
        //Message lors de la suppression du manga qui s'affichera dans le layout de l'admin
//        $this->addFlash('deleteManga', 'Le manga a bien été supprimé');
        //ON retourne vers admin-books
        return $this->redirectToRoute('admin-lodgers');
    }


    /**
     * @Route("/admin-deceased", name="deceased")
     */

    public function Deceased(AnimalsRepository $animalsRepository): Response
    {
        $deceased = $animalsRepository->findDeceased();
        return $this->render("pages/admin/deceased.html.twig", ['lodgers'=>$deceased]);
    }

    /**
     * @Route("/admin-adopted", name="adopted")
     */

    public function Adopted(AnimalsRepository $animalsRepository): Response
    {
        $adopted = $animalsRepository->findAdopted();

        return $this->render("pages/admin/adopted.html.twig", ['lodgers' => $adopted]);
    }

    /**
     * @Route("/admin-add-adoption/{id}", name="add-adoption")
     */

    public function AddAdoption(int $id, AnimalsRepository $animalsRepository, Request $request, EntityManagerInterface $em, PhotoUploader $photoUploader): Response
    {
        $lodger = $animalsRepository->find($id);
        $adoption = new Adoption();
        $adoption->setAnimals($lodger);
        $formAdoption = $this->createForm(AdoptionType::class, $adoption);
        $formAdoption->handleRequest($request);
        //Si formulaire est envoyé et valide on persist les commentaires et on redirige la page vers le single
        if ($formAdoption->isSubmitted() && $formAdoption->isValid()) {
            foreach ($formAdoption->get('gallery')->get('photos') as $photoData) {
                $photo = $photoUploader->uploadPhoto($photoData);
                $adoption->getGallery()->addPhoto($photo);
                $em->persist($photo);
                $em->flush();
            }
            $em->persist($lodger->getAdoption());
            $em->persist($lodger);
            $em->flush();

            return $this->redirectToRoute('single', ['slug' => $lodger->getSlug()]);
        }


        return $this->render("pages/admin/form/add-adoption.html.twig", ['adoptionForm' => $formAdoption->createView()]);
    }

    /**
     * @Route("/admin-recovered", name="recovered")
     */

    public function Recovered(AnimalsRepository $animalsRepository): Response
    {
        $recovered = $animalsRepository->findRecovered();
        return $this->render("pages/admin/recovered.html.twig", ['lodgers'=>$recovered]);
    }

}