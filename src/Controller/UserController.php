<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountFormType;
use App\Form\UserFormType;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use App\Service\ImageUploadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use function json_decode;

#[Route('/user')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly UserRepository     $userRepository,
        private readonly ImageUploadService $imageUploadService,
        private readonly ReviewRepository   $reviewRepository,
    )
    {
    }

    #[Route(path: '/account', name: 'user_account')]
    public function account(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserFormType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $avatar */
            $avatar = $form->get('avatar')
                ->getData();
            assert($user instanceof User);

            if ($avatar !== null) {
                $fileContentBase64 = $this->imageUploadService->process($avatar, 300);
                $user->setAvatar($fileContentBase64);
            }

            $this->userRepository->add($user);
            return $this->redirectToRoute('user_account');
        }

        return $this->render('user/account.html.twig', [
            'accountForm' => $form->createView(),
        ]);
    }

    #[Route('/assets', name: 'user_assets')]
    public function assets(): Response
    {
        return $this->render('user/assets.html.twig');
    }

    #[Route('/new/asset/', name: 'new_user_asset')]
    public function create(): Response
    {
        return $this->render('user/new-asset.html.twig');
    }

    #[Route('/bookmarks/', name: 'user_bookmarks')]
    public function bookmarks(): Response
    {
        return $this->render('user/bookmarks.html.twig');
    }

    #[Route('/agency', name: 'user_agency')]
    public function agency(): Response
    {
        return $this->render('user/agency.html.twig');
    }

    #[Route('/search', name: '_user_search')]
    public function search(Request $request): Response
    {
        $keyword = $request->get('q');
        $ids = (array) json_decode($request->get('i'), null, 512, JSON_THROW_ON_ERROR);
        $users = $this->userRepository->findByInput($keyword, $ids);

        return $this->render('user/_autocomplete-list.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/avg/rating/{id}', name: '_user_avg_rating')]
    public function _rating(User $user): Response
    {
        $avgUserRating = $this->reviewRepository->getAverageUserRating($user);

        return $this->render('user/_rating.html.twig', [
            'avgUserRating' => $avgUserRating,
            'user' => $user,
        ]);
    }

    #[Route('/set/empty/account', name: 'user_set_empty_account')]
    public function setAccount(): Response
    {
        $accountTypeForm = $this->createForm(AccountFormType::class);

        if ($accountTypeForm->isSubmitted() && $accountTypeForm->isValid()) {
            return $this->redirectToRoute('user_set_empty_account');
        }

        return $this->render('user/set-account.html.twig', [
            'accountTypeForm' => $accountTypeForm->createView(),
        ]);
    }
}
