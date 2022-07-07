<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('/{id}', name: 'user_public_profile')]
    public function publicProfile(User $user, Request $request, LoggerInterface $logger): Response
    {
       return $this->render('profile/public.html.twig', [
           'user' => $user,
       ]);
    }

    #[Route('/profile/popover/{id}', name: 'user_profile_popover')]
    public function userProfilePopover(User $user): Response
    {
        return $this->render('profile/_profile_popover.html.twig', [
            'user' => $user,
        ]);
    }
}
