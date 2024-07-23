<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MyAccountController extends AbstractController
{
    #[Route('/account', name: 'app_my_account')]
    public function index(): Response
    {
        return $this->render('my_account/accountBase.html.twig', [
        ]);
    }
}
