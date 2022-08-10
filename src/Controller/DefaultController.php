<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function Home(): Response
    {
        return $this->render('pages/user/home.html.twig');
    }

    /**
     * @Route("/adoption", name="adoption")
     */
    public function Adoption(): Response
    {
        return $this->render('pages/user/adoption.html.twig');
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

}
