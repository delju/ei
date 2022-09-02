<?php

namespace App\Controller;


use App\Form\ContactType;
use App\Repository\AnimalsRepository;
use App\Search\Search;
use App\Search\SearchFullType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function Home(AnimalsRepository $animalsRepository): Response
    {
//        Affichage des derniers animaux de la dernière chance
        $lodgersLastChance = $animalsRepository->findLastChance(3);

        return $this->render('pages/user/home.html.twig', ['lastChance' => $lodgersLastChance]);
    }

    /**
     * @Route("/adoption", name="adoption")
     */
    public function Adoption(AnimalsRepository $animalsRepository, Request $request, PaginatorInterface $paginator): Response
    {
        //Création d'une nouvelle recherche
        $search = new Search();
        //Retourne le formulaire de recherche
        $form = $this->createForm(SearchFullType::class, $search);
        $form->handleRequest($request);
        //Si le formulaire est envoyé et valide, on effectue la recherche selon les critères de findbysearch
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $animalsRepository->findBySearch($search);
        } else {
        //Si non on affiche une liste
            $result = $animalsRepository->findAll();
        }
        //Pagination des résultats
        $pagination = $paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            1);

        return $this->render('pages/user/adoption.html.twig', ['lodgers' => $pagination, 'searchFullForm' => $form->createView()]);
    }

    /**
     * @Route("/single/{slug}", name="single")
     */
    public function Single(string $slug, AnimalsRepository $animalsRepository): Response
    {
        //Affichage des éléments selon le slug
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
    public function Contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {

            $contactFormData = $form->getData();

            $message = (new Email())
                ->from($contactFormData['email'])
                ->to('julene.delvaux@gmail.com')
                ->subject('vous avez reçu unn email')
                ->text('Sender : '.$contactFormData['email'].\PHP_EOL.
                    $contactFormData['message'],
                    'text/plain');
            $mailer->send($message);

            $this->addFlash('success', 'Vore message a été envoyé');

            return $this->redirectToRoute('contact');
        }

        return $this->render('pages/user/contact.html.twig', ['contactForm' => $form->createView()]);

    }


}
