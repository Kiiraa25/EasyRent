<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\UserType;
use App\Enum\RoleEnum;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    // #[Route('/subscribe', name: 'app_subscribe')]
    // public function subscribe(Request $request, EntityManagerInterface $entityManager): Response
    // {

    //     $user = new User();
    //     $userProfile = new UserProfile();

    //     $user->setProfile($userProfile);
    //     $userProfile-> setCreatedAt(new \DateTimeImmutable());
    //     $userProfile-> setUpdatedAt(new \DateTimeImmutable());
    //     $userProfile-> setRating(0);

    //     $form = $this->createForm(UserType::class, $user);

    //     $form->handleRequest($request);

    //     if($form->isSubmitted() && $form->isValid()) {
    //         $user->setActive(true);
    //         $user->setRoles([RoleEnum::USER]);

    //         $entityManager->persist($user);
    //         $entityManager->flush();

    //         return $this->redirectToRoute(route: 'app_home');
    //     }

    //     return $this->render('user/subscribe.html.twig', [
    //         'form' => $form
    //     ]);
    // }
}
