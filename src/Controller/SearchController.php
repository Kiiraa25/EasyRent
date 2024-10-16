<?php

namespace App\Controller;

use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/search', name: 'app_')]
class SearchController extends AbstractController
{
    #[Route('', name: 'search')]
    public function index(Request $request): Response
    {

        dd("daluy");
        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);


        // if ($searchForm->isSubmitted() && $searchForm->isValid()) {
        // }
        return $this->render('vehicle/showAllVehicles.html.twig', [
            'searchForm' => $searchForm,
        ]);
    }
}
