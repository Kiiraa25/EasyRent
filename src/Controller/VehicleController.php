<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Form\VehicleType;
use Symfony\Component\HttpFoundation\Request;

class VehicleController extends AbstractController
{
    #[Route('/vehicle/new', name: 'app_vehicle')]
    public function index(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $vehicle = new Vehicle();

        $vehicleForm = $this->createForm(VehicleType::class, $vehicle);
        $vehicleForm->handleRequest($request);

        if ($vehicleForm->isSubmitted() && $vehicleForm->isValid()) {
            $vehicle->setCreatedAt(new \DateTimeImmutable());
            $vehicle->setUpdatedAt(new \DateTimeImmutable());
            $vehicle->setOwner($user);
        }

        return $this->render('vehicle/new.html.twig',[
            'vehicleForm' => $vehicleForm,
        ]);
    }
}
