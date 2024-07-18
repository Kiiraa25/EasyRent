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
    // ...
}
