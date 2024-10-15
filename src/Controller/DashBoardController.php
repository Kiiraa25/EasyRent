<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashBoardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashBoard')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        return $this->render('dashBoard/dashBoardBase.html.twig', [
        ]);
    }
}
