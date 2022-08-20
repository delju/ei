<?php

namespace App\Controller;


use App\Entity\Animals;
use App\Entity\Message;
use App\Form\AnimalsType;
use App\Form\MessageType;
use App\Repository\AnimalsRepository;
use App\Repository\MessageRepository;
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
        $messages = $messageRepository->findBy([], ['date'=> 'desc' ]);

        //Création d'un nouveau message a partir du formulaire MessageTYPE dans la page contact
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($message);
            $em->flush();
            return $this->redirectToRoute('contact');
        }
        return $this->render('pages/admin/messages.html.twig',['messages'=>$messages, 'messageForm'=>$form->createView()] );
    }

    /**
     * @Route("/admin-lodgers", name="admin-logders")
     */
    public function Lodgers(AnimalsRepository $animalsRepository): Response
    {
        $lodgers = $animalsRepository->findBy([], ['name'=>'asc']);


        return $this->render('pages/admin/lodgers.html.twig', ['lodgers'=>$lodgers]);
    }

    /**
     * @Route("/admin-lodgers/add-lodgers", name="add-logders")
     */
    public function AddLodgers(Request $request, EntityManagerInterface $em, PhotoUploader $photoUploader): Response
    {
        /* Création d'un nouvel animal */
        $animals = new Animals();
        /* Création d'un formulaire à partir de AnimalsType */
        $formAnimals =$this->createForm(AnimalsType::class, $animals);
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
             return $this->redirectToRoute('admin-logders', ['slug' => $animals->getSlug()]);
         }
        return $this->render('pages/admin/add-lodgers.html.twig', ['animalsForm' => $formAnimals->createView() ]);
    }



}