<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Review;
use App\Entity\User;
use App\Form\ReviewFormType;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/review')]
class ReviewController extends AbstractController
{
    public function __construct(
        private readonly ReviewRepository $reviewRepository
    ) {
    }

    #[Route('/new/{id}', name: 'new_review')]
    public function create(User $user, Request $request)
    {
        $review = new Review();
        $review->setAuthor($this->getUser());
        $review->setUser($user);

        $reviewForm = $this->createForm(ReviewFormType::class, $review);
        $reviewForm->handleRequest($request);

        if ($reviewForm->isSubmitted() && $reviewForm->isValid()) {
            $this->reviewRepository->add($review);

            return $this->redirectToRoute('user_public_profile', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('review/new.html.twig', [
            'reviewForm' => $reviewForm->createView(),
            'user' => $user,
        ]);
    }
}
