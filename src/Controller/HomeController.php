<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/CGU', name: 'app_CGU')]
    public function CGU(): Response
    {
        return $this->render('home/CGU.html.twig', [
        ]);
    }

    #[Route('/mentions-legales', name: 'app_mentions_legales')]
    public function mentions_legales(): Response
    {
        return $this->render('home/mentions-legales.html.twig', [
        ]);
    }

    #[Route('/politique-de-confidentialité', name: 'app_privacy')]
    public function privacy(): Response
    {
        return $this->render('home/privacy.html.twig', [
        ]);
    }

    #[Route('/a-propos', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('home/about.html.twig', [
        ]);
    }

    #[Route('/faq', name: 'app_FAQ')]
    public function FAQ(): Response
    {
        return $this->render('home/faq.html.twig', [
        ]);
    }

    #[Route('/devenir-hote', name: 'app_host')]
    public function host(): Response
    {
        return $this->render('home/host.html.twig', [
        ]);
    }
}
