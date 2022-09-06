<?php

namespace App\Controller;


use App\Entity\Adoption;
use App\Entity\Animals;
use App\Entity\ComeBack;
use App\Entity\Death;
use App\Entity\Message;
use App\Entity\Treatment;
use App\Form\AdoptionType;
use App\Form\AnimalsType;
use App\Form\ComeBackType;
use App\Form\DeathType;
use App\Form\MessageType;
use App\Form\TreatmentType;
use App\Repository\AnimalsRepository;
use App\Repository\MessageRepository;
use App\Search\Search;
use App\Search\SearchFullType;
use App\Service\PhotoUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin-home", name="admin-home")
     */
    public function Administration(Request $request, EntityManagerInterface $em, MessageRepository $messageRepository, PaginatorInterface $paginator): Response
    {
        $messages = $messageRepository->findBy([], ['date' => 'desc']);

        //Création d'un nouveau message a partir du formulaire MessageTYPE dans la page contact
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($message);
            $em->flush();
            $this->addFlash('successMessageAdmin', 'Votre message est bien enregistré');
            return $this->redirectToRoute('admin-home');
        }

        $pagination = $paginator->paginate(
            $messages, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            20/*limit per page*/);

        return $this->render('pages/admin/messages.html.twig', ['messages' => $pagination, 'messageForm' => $form->createView()]);
    }

    /**
     * @Route("/admin-lodgers", name="admin-lodgers")
     */
    public function Lodgers(AnimalsRepository $animalsRepository, Request $request, PaginatorInterface $paginator): Response
    {
        //Création d'un recherche
        $search = new Search();
        //Retourne le formulaire de recherche
        $form = $this->createForm(SearchFullType::class, $search);
        $form->handleRequest($request);
        //Si le formulaire est envoyé et valide, on effectue la recherche selon les critères de findbysearch
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $animalsRepository->findBySearch($search);
        } else {
            $result = $animalsRepository->findAllInAdoption();
        }

        $pagination = $paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            15);


        return $this->render('pages/admin/lodgers.html.twig', ['lodgers' => $pagination, 'searchFullForm' => $form->createView()]);
    }

    /**
     * @Route("/admin-single/{slug}", name="admin-single")
     */

    public function Single(string $slug, AnimalsRepository $animalsRepository, Request $request, EntityManagerInterface $em, PaginatorInterface $paginator): Response
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

            $this->addFlash('successAddCare', 'Votre soin a bien été ajouté');

            return $this->redirectToRoute('admin-single', ['slug' => $single->getSlug()]);
        }


        return $this->render('pages/admin/single.html.twig', ['single' => $single, 'caresForm' => $formCares->createView()]);
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
        //Si formulaire envoyé et validé, on enregistre les photos
        if ($formAnimals->isSubmitted() && $formAnimals->isValid()) {
            foreach ($formAnimals->get('gallery')->get('photos') as $photoData) {
                $photo = $photoUploader->uploadPhoto($photoData);
                $animals->getGallery()->addPhoto($photo);
                $em->persist($photo);

            }
            // Ensuite on ajoute lié la gallery et l'animal
            $em->persist($animals->getGallery());
            $em->persist($animals);
            $em->flush();
            // Message de succès
            $this->addFlash('successAddLodger', 'Le pensionnaire a bien été ajouté');

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

            $this->addFlash('editLodger', 'La fiche du pensionnaire a bien été modifié');

            return $this->redirectToRoute('admin-lodgers');
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
        //Message lors de la suppression du pensionnaire
    $this->addFlash('deleteLodger', 'Le pensionnaire a bien été supprimé');
        //ON retourne vers admin-books
        return $this->redirectToRoute('admin-lodgers');
    }


    /**
     * @Route("/admin-deceased", name="deceased")
     */

    public function Deceased(AnimalsRepository $animalsRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $deceased = $animalsRepository->findDeceased();

        $pagination = $paginator->paginate(
            $deceased,
            $request->query->getInt('page', 1),
            15);
        return $this->render("pages/admin/deceased.html.twig", ['lodgers' => $pagination]);
    }

    /**
     * @Route("/admin-add-deceased/{id<\d+>}", name="add-deceased")
     */

    public function addDeceased(int $id, AnimalsRepository $animalsRepository, Request $request, EntityManagerInterface $em): Response
    {
        $lodger = $animalsRepository->find($id);
        $deceased = new Death();
        $deceased->setAnimals($lodger);
        $formDeceased = $this->createForm(DeathType::class, $deceased);
        $formDeceased->handleRequest($request);
        //Si formulaire est envoyé et valide on persist les commentaires et on redirige la page vers le single
        if ($formDeceased->isSubmitted() && $formDeceased->isValid()) {
            $em->persist($lodger->getDeath());
            $em->persist($lodger);
            $em->flush();

            $this->addFlash('addDeceased', 'Le pensionnaire a été ajouté à la liste');


            return $this->redirectToRoute('deceased');
        }



        return $this->render("pages/admin/form/add-deceased.html.twig", ['deceasedForm' => $formDeceased->createView()]);
    }

    /**
     * @Route("/admin-adopted", name="adopted")
     */

    public function Adopted(AnimalsRepository $animalsRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $adopted = $animalsRepository->findAdopted();
        $pagination = $paginator->paginate(
            $adopted,
            $request->query->getInt('page', 1),
            15);

        return $this->render("pages/admin/adopted.html.twig", ['lodgers' => $pagination]);
    }

    /**
     * @Route("/admin-add-adoption/{id<\d+>}", name="add-adoption")
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

            $this->addFlash('addAdopted', 'Le pensionnaire a été ajouté à la liste');

            return $this->redirectToRoute('adopted');
        }


        return $this->render("pages/admin/form/add-adoption.html.twig", ['adoptionForm' => $formAdoption->createView()]);
    }


    /**
     * @Route("/admin-recovered", name="recovered")
     */

    public function Recovered(AnimalsRepository $animalsRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $recovered = $animalsRepository->findRecovered();
        $pagination = $paginator->paginate(
            $recovered,
            $request->query->getInt('page', 1),
            15);
        return $this->render("pages/admin/recovered.html.twig", ['lodgers' => $pagination]);
    }

    /**
     * @Route("/admin-add-recovered/{id<\d+>}", name="add-recovered")
     */

    public function addRecovered(int $id, AnimalsRepository $animalsRepository, Request $request, EntityManagerInterface $em): Response
    {
        $lodger = $animalsRepository->find($id);
        $recovered = new ComeBack();
        $recovered->setAnimals($lodger);
        $formRecovered = $this->createForm(ComeBackType::class, $recovered);
        $formRecovered->handleRequest($request);
        //Si formulaire est envoyé et valide on persist les commentaires et on redirige la page vers le single
        if ($formRecovered->isSubmitted() && $formRecovered->isValid()) {
            $em->persist($lodger->getComeBack());
            $em->persist($lodger);
            $em->flush();

            $this->addFlash('addRecovered', 'Le pensionnaire a été ajouté à la liste');


            return $this->redirectToRoute('recovered', ['slug' => $lodger->getSlug()]);
        }



        return $this->render("pages/admin/form/add-recovered.html.twig", ['recoveredForm' => $formRecovered->createView()]);
    }

}