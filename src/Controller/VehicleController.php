<?php

namespace App\Controller;

use App\Dto\SearchDto;
use App\Entity\Brand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Enum\PhotoTypeEnum;
use App\Form\VehicleForms\VehicleType;
use App\Form\VehicleForms\EditVehicleType;
use App\Form\SearchType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\VehicleRepository;
use App\Enum\VehicleStatusEnum;
use App\Service\Api\DataGouvAddressService;
use Detection\MobileDetect;
use Mobile_Detect;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use function Amp\Dns\query;

class VehicleController extends AbstractController


{

    #[Route('/vehicle/new', name: 'app_vehicle_new')]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request, EntityManagerInterface $entityManager, DataGouvAddressService $dataGouvAddressService): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        if (!$user->getProfile() || !$user->getProfile()->isVerified()) {
            $this->addFlash('profile_verification', 'Votre profil doit être vérifié pour pouvoir louer un véhicule.');
        }

        $vehicle = new Vehicle();

        $vehicleForm = $this->createForm(VehicleType::class, $vehicle);
        $vehicleForm->handleRequest($request);

        if ($vehicleForm->isSubmitted() && $vehicleForm->isValid()) {
            if (count($vehicle->getPhotos()) < 5) {
                $this->addFlash('error', 'Vous devez ajouter au moins 5 photos pour ce véhicule.');
            }

            // Traitement de chaque photo soumise
            foreach ($vehicle->getPhotos() as $photo) {
                // Définir les propriétés supplémentaires qui ne sont pas dans le formulaire
                $photo->setVehicle($vehicle);
                $photo->setCreatedAt(new \DateTimeImmutable());
                $photo->setUpdatedAt(new \DateTimeImmutable());
                $photo->setType(PhotoTypeEnum::VEHICLE);
            }

            // Obtenir les coordonnées basées sur l'adresse et le code postal
            $coordinates = $dataGouvAddressService->getVehicleCoordinates($vehicle->getAddress(), $vehicle->getPostalCode());

            if (!empty($coordinates['features'])) {
                $latitude = $coordinates['features'][0]['geometry']['coordinates'][1] ?? null;
                $longitude = $coordinates['features'][0]['geometry']['coordinates'][0] ?? null;

                if ($latitude && $longitude) {
                    $vehicle->setLatitude($latitude);
                    $vehicle->setLongitude($longitude);
                } else {
                    $this->addFlash('error', 'Impossible de récupérer les coordonnées GPS pour l\'adresse fournie.');
                }
            } else {
                $this->addFlash('error', 'Impossible de récupérer les coordonnées GPS pour l\'adresse fournie.');
                return $this->render('vehicle/newVehicle.html.twig', [
                    'vehicleForm' => $vehicleForm,
                ]);
            }

            // Obtenir la marque et le modèle du véhicule
            $model = $vehicle->getModel();
            $brandName = $model->getBrand()->getName();

            // Vérifier si la marque existe déjà
            $brandRepository = $entityManager->getRepository(Brand::class);
            $existingBrand = $brandRepository->findOneBy(['name' => $brandName]);

            if ($existingBrand) {
                // Utiliser la marque existante
                $model->setBrand($existingBrand);
            } else {
                // Créer une nouvelle marque si elle n'existe pas
                $newBrand = new Brand();
                $newBrand->setName($brandName);
                $entityManager->persist($newBrand);
                $model->setBrand($newBrand);
            }

            $issueDate = $vehicle->getRegistrationCertificate()->getIssueDate();
            $fifteenYearsAgo = (new \DateTime())->modify('-15 years');

            // Vérification de la date d'immatriculation
            if ($issueDate < $fifteenYearsAgo) {
                $this->addFlash('error', 'Vous ne pouvez pas ajouter un véhicule de plus de 15 ans.');
            } else if ($issueDate > new \DateTime()) {
                $this->addFlash('error', "La date d'immatriculation ne peux pas être ultérieure à la date du jour");
            }

            // Vérifier le nombre de portes
            $doors = $vehicle->getDoors();
            if ($doors < 1 || $doors > 5) {
                $this->addFlash('error', 'Le nombre de portes doit être entre 1 et 5.');
            }

            // Vérifier le nombre de sièges
            $seats = $vehicle->getSeats();
            if ($seats < 1 || $seats > 7) {
                $this->addFlash('error', 'Le nombre de sièges doit être entre 1 et 7.');
            }

            // Vérifier le kilométrage
            $mileage = $vehicle->getMileage();
            if ($mileage < 0 || $mileage > 200000) {
                $this->addFlash('error', 'Le kilométrage doit être entre 0 et 200 000 km.');
                return $this->render('vehicle/newVehicle.html.twig', [
                    'vehicleForm' => $vehicleForm,
                ]);
            }



            $vehicle->setCreatedAt(new \DateTimeImmutable());
            $vehicle->setUpdatedAt(new \DateTimeImmutable());
            $vehicle->setOwner($user);
            $vehicle->setStatus(VehicleStatusEnum::WAITING_FOR_VALIDATION);

            $entityManager->persist($vehicle);
            $entityManager->flush();

            $this->addFlash('success', 'Votre annonce a été ajoutée avec succès.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('vehicle/newVehicle.html.twig', [
            'vehicleForm' => $vehicleForm,
        ]);
    }


    // READ
    #[Route('/vehicle/{id}', name: 'app_vehicle_show')]
    public function show(Vehicle $vehicle, Request $request): Response
    {

        if (!$vehicle) {
            throw $this->createNotFoundException('Véhicule non trouvé.');
        }

        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');
        $owner = $vehicle->getOwner();

        return $this->render('vehicle/showVehicle.html.twig', [
            'vehicle' => $vehicle,
            'owner' => $owner,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    // SHOW ALL USER VEHICLES
    #[Route('/user/vehicles', name: 'app_user_vehicles')]
    #[IsGranted('ROLE_USER')]
    public function showUserVehicles(VehicleRepository $vehicleRepository): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur est bien connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $vehicles = $vehicleRepository->createQueryBuilder('v')
            ->where('v.owner = :owner')
            ->andWhere('v.status IN (:statuses)')
            ->setParameter('owner', $user)
            ->setParameter('statuses', [VehicleStatusEnum::WAITING_FOR_VALIDATION, VehicleStatusEnum::ACTIVE, VehicleStatusEnum::SUSPENDED])
            ->getQuery()
            ->getResult();

        return $this->render('vehicle/showUserVehicles.html.twig', [
            'vehicles' => $vehicles
        ]);
    }

    // SHOW ALL VEHICLES
    #[Route('/vehicles', name: 'app_vehicles')]
    public function showVehicles(VehicleRepository $vehicleRepository, Request $request, DataGouvAddressService $DataGouvAddressService): Response
    {
        $today = (new \DateTime())->format('Y-m-d');
        $defaultEndDate = (new \DateTime())->modify('+8 days')->format('Y-m-d');

        $mobileDetect = new Mobile_Detect();
        $isMobile = $mobileDetect->isMobile();

        // Récupérer les paramètres GET ou utiliser les valeurs par défaut
        $search = $request->query->get('search', '');

        $startDateQuery = $request->query->get('startDate', $today);
        $endDateQuery = $request->query->get('endDate', $defaultEndDate);

        // Créer l'objet DTO
        $searchDto = new SearchDto();
        $searchDto
            ->setSearch($search)
            ->setStartDate(new \DateTime($startDateQuery))
            ->setEndDate(new \DateTime($endDateQuery));

        // Formulaire
        $searchForm = $this->createForm(SearchType::class, $searchDto);
        $searchForm->handleRequest($request);

        $vehicleTotalPrices = [];
        $vehicleMarkers = [];
        $latitude = 45.750000; // Valeurs par défaut
        $longitude = 4.850000;

        $search = $searchDto->getSearch();
        $startDate = $searchDto->getStartDate()->format('Y-m-d');
        $endDate = $searchDto->getEndDate()->format('Y-m-d');

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {


            // Vérification des erreurs
            if ($startDate <= $today || $endDate <= $today || $endDate < $startDate || $endDate == $startDate) {
                $this->addFlash('search-error', 'Les dates saisies ne sont pas valides');
                $vehicles = [];
            }

            if (strlen($search) < 3) {
                $this->addFlash('search-error', 'La recherche doit contenir au moins 3 caractères.');
                $vehicles = [];
            }
        }

        // Recherche des véhicules
        $vehicles = $vehicleRepository->search($searchDto);

        $days = $searchDto->getStartDate()->diff($searchDto->getEndDate())->days;

        foreach ($vehicles as $vehicle) {
            $vehicleTotalPrices[$vehicle->getId()] = $vehicle->getPricePerDay() * $days;
            $vehicleMarkers[] = [
                'id' => $vehicle->getId(),
                'latitude' => $vehicle->getLatitude(),
                'longitude' => $vehicle->getLongitude(),
                'model' => $vehicle->getModel()->getName(),
                'brand' => $vehicle->getModel()->getBrand()->getName(),
                'pricePerDay' => $vehicle->getPricePerDay(),
                'city' => $vehicle->getCity(),
            ];
        }

        // Obtenir les coordonnées de la recherche
        $cityCoordinates = $DataGouvAddressService->getCityCoordinates($searchDto->getSearch());
        if ($cityCoordinates) {
            $latitude = $cityCoordinates['features'][0]['geometry']['coordinates'][1];
            $longitude = $cityCoordinates['features'][0]['geometry']['coordinates'][0];
        }




        $queryString = http_build_query([
            'search' => $search,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);

        return $this->render('vehicle/showAllVehicles.html.twig', [
            'searchForm' => $searchForm,
            'vehicles' => $vehicles ?? [],
            'vehicleTotalPrices' => $vehicleTotalPrices,
            'days' => $days ?? 0,
            'vehicleMarkers' => $vehicleMarkers,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'queryString' => $queryString,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'is_mobile' => $isMobile,
        ]);
    }



    // UPDATE
    #[Route('/vehicle/{id}/edit', name: 'app_vehicle_edit')]
    #[IsGranted('ROLE_USER')]
    public function edit(
        Request $request,
        Vehicle $vehicle,
        EntityManagerInterface $entityManager,
        DataGouvAddressService $dataGouvAddressService
    ): Response {

        $owner = $vehicle->getOwner();
        $currentUser = $this->getUser();

        if (!$owner instanceof User || $currentUser !== $owner) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à accéder à cette page.');
        }

        if (!$vehicle) {
            throw $this->createNotFoundException('Véhicule non trouvé.');
        }

        $form = $this->createForm(EditVehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des nouvelles coordonnées si l'adresse a changé
            $newAddress = $vehicle->getAddress();
            $newPostalCode = $vehicle->getPostalCode();

            $coordinates = $dataGouvAddressService->getVehicleCoordinates($newAddress, $newPostalCode);

            if (!empty($coordinates['features'])) {
                $latitude = $coordinates['features'][0]['geometry']['coordinates'][1] ?? null;
                $longitude = $coordinates['features'][0]['geometry']['coordinates'][0] ?? null;

                if ($latitude && $longitude) {
                    $vehicle->setLatitude($latitude);
                    $vehicle->setLongitude($longitude);
                } else {
                    $this->addFlash('error', 'Impossible de récupérer les nouvelles coordonnées GPS pour l\'adresse fournie.');
                    return $this->redirectToRoute('app_vehicle_edit', ['id' => $vehicle->getId()]);
                }
            } else {
                $this->addFlash('error', 'Impossible de récupérer les nouvelles coordonnées GPS pour l\'adresse fournie.');
                return $this->redirectToRoute('app_vehicle_edit', ['id' => $vehicle->getId()]);
            }

            $vehicle->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            $this->addFlash('success', 'Véhicule mis à jour avec succès.');
            return $this->redirectToRoute('app_vehicle_show', ['id' => $vehicle->getId()]);
        }

        return $this->render('vehicle/editVehicle.html.twig', [
            'form' => $form->createView(),
            'vehicle' => $vehicle,
        ]);
    }

    // DELETE --> mettre en statut "supprimé" ou "archivé"
    #[Route('/vehicle/{id}/delete', name: 'app_vehicle_delete')]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Vehicle $vehicle, EntityManagerInterface $entityManager): Response
    {
        $owner = $vehicle->getOwner();
        $currentUser = $this->getUser();


        if (!$owner instanceof User || $currentUser == !$owner) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        if (!$vehicle) {
            throw $this->createNotFoundException('Véhicule non trouvé.');
        }

        $vehicle->setStatus(VehicleStatusEnum::ARCHIVED);
        $vehicle->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->persist($vehicle);
        $entityManager->flush();

        $this->addFlash('success', 'Véhicule supprimé avec succès.');
        return $this->redirectToRoute('app_user_vehicles');
    }
}
