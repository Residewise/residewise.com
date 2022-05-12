<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\User;
use App\Form\ReviewFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale}/profile')]
class ProfileController extends AbstractController
{

    #[Route(path:'', name: 'user_account')]
    public function account(): Response
    {
        return $this->render('profile/account.html.twig');
    }

    #[Route('/assets', name: 'user_dashboard_assets')]
    public function userAssets(): Response
    {
        return $this->render('profile/_assets.html.twig');
    }

    #[Route('/{id}', name: 'user_public_profile')]
    public function publicProfile(User $user, Request $request): Response
    {
        $review = new Review();
        $review->setOwner($this->getUser());
        $review->setReviewee($user);

        $reviewForm = $this->createForm(ReviewFormType::class, $review);
        $reviewForm->handleRequest($request);

        if ($reviewForm->isSubmitted() && $reviewForm->isValid()) {

        }

       return $this->render('profile/public.html.twig', [
            'user' => $user,
           'reviewForm' => $reviewForm->createView()
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
