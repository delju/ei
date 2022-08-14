<?php

namespace App\Controller;


use App\Entity\Animals;
use App\Form\AnimalsType;
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
    public function Administration(): Response
    {
        return $this->render('pages/admin/messages.html.twig');
    }

    /**
     * @Route("/admin-lodgers", name="admin-logders")
     */
    public function Lodgers(): Response
    {
        return $this->render('pages/admin/lodgers.html.twig');
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