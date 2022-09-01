<?php

namespace App\Controller;


use App\Repository\AnimalsRepository;
use App\Search\Search;
use App\Search\SearchFullType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function Home(AnimalsRepository $animalsRepository): Response
    {
        $lodgersLastChance = $animalsRepository->findLastChance(3);

        return $this->render('pages/user/home.html.twig', ['lastChance' => $lodgersLastChance]);
    }

    /**
     * @Route("/adoption", name="adoption")
     */
    public function Adoption(AnimalsRepository $animalsRepository, Request $request): Response
    {
        $lodgers = $animalsRepository->findAll();

        return $this->render('pages/user/adoption.html.twig', ['lodgers'=>$lodgers]);
    }

    /**
     * @Route("/single/{slug}", name="single")
     */
    public function Single(string $slug, AnimalsRepository $animalsRepository): Response
    {
        $single = $animalsRepository->findOneBySlug($slug);

        return $this->render('pages/user/single.html.twig', ['single' => $single]);
    }

    /**
     * @Route("/conditions", name="conditions")
     */
    public function Conditions(): Response
    {
        return $this->render('pages/user/conditions.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function Contact(): Response
    {
        return $this->render('pages/user/contact.html.twig');
    }

    /**
     * @Route("/
     */

}
