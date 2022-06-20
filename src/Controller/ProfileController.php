<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\User;
use App\Form\ReviewFormType;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Uuid;

#[Route('/profile')]
class ProfileController extends AbstractController
{

    #[Route('/{id}', name: 'user_public_profile')]
    public function publicProfile(User $user, Request $request, LoggerInterface $logger): Response
    {
       return $this->render('profile/public.html.twig', [
            'user' => $user
        ]);
    }


    #[Route('/profile/popover/{id}', name: 'user_profile_popover')]
    public function userProfilePopover(User $user): Response
    {
        return $this->render('profile/_profile_popover.html.twig', [
            'user' => $user
        ]);
    }


}
