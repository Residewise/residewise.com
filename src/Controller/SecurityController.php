<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Form\EmailFormType;
use App\Form\PasswordFormType;
use App\Repository\UserRepository;
use App\Service\Email\AccountConfirmationEmail;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/security')]
class SecurityController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly TranslatorInterface $translator,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly AuthenticationUtils $authenticationUtils,
        private AccountConfirmationEmail $accountConfirmationEmail
    )
    {
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(): Response
    {
         if ($this->getUser() !== null) {
             return $this->redirectToRoute('user_account');
         }

        // get the login error if there is one
        $error = $this->authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): never
    {
        throw new LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }

    #[Route(path: '/confirm/{token}', name: 'app_account_confirm')]
    public function confirm(User $user): Response
    {
        if(! $user){
           return $this->redirectToRoute('app_login');
        }

        $user->setIsEnabled(true);
        $user->setToken(Uuid::v4());
        $this->userRepository->add($user, true);

        $this->addFlash('success', $this->translator->trans('email-address.confirmed'));

        return $this->redirectToRoute('app_login');
    }

    #[Route(path: '/set/password', name: 'user_set_empty_password')]
    public function setEmptyPassword(Request $request): Response
    {
        if($this->getUser()->getPassword() !== UserFactory::PASSWORD_NOT_SET){
            return $this->redirectToRoute('app_login');
        }

        $passwordForm = $this->createForm(PasswordFormType::class, [
            'isOldPasswordRequired' => false,
        ]);

        $passwordForm->handleRequest($request);
        $errors = $passwordForm->getErrors();

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {

            $user = $this->getUser();
            $plainPassword = $passwordForm->get('password')
                ->getData();
            $hashedPassword = $this->userPasswordHasher->hashPassword($user, $plainPassword);

            $this->userRepository->upgradePassword($user, $hashedPassword);
            $this->userRepository->add($user, true);
            $this->addFlash('success', 'password updated successfully');

            return $this->redirectToRoute('user_account');
        }

        return $this->render('security/set-password.html.twig', [
            'passwordForm' => $passwordForm->createView(),
            'errors' => $errors,
        ]);
    }

    #[Route(path: '/forgot/password', name: 'user_forgot_password')]
    public function forgotPassword(Request $request): Response
    {
        $emailForm = $this->createForm(EmailFormType::class);
        $emailForm->handleRequest($request);
        $error = $this->authenticationUtils->getLastAuthenticationError();

        if ($emailForm->isSubmitted() && $emailForm->isValid()) {

            $email = $emailForm->get('email')
                ->getData();
            $user = $this->userRepository->findOneBy([
                'email' => $email,
            ]);

            if($user !== null){
                $this->accountConfirmationEmail->send($user, []);
            }

            $this->addFlash('message', $this->translator->trans('password-reset-email-sent'));

            return $this->redirectToRoute('user_forgot_password');

        }

        return $this->render('security/forgot-password.html.twig', [
            'error' => $error,
            'emailForm' => $emailForm->createView(),
        ]);
    }

    #[Route(path:'/password/reset/{token}', name: 'user_reset_password')]
    public function setNewPassword(User $user, Request $request): Response
    {

        if(! $user){
            return $this->redirectToRoute('app_login');
        }

        $passwordForm = $this->createForm(PasswordFormType::class, null, [
            'isOldPasswordRequired' => true,
        ]);

        $passwordForm->handleRequest($request);
        $error = $this->authenticationUtils->getLastAuthenticationError();

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {

            $oldPlainPassword = $passwordForm->get('oldPassword')
                ->getData();
            $oldHashedPassword = $this->userPasswordHasher->hashPassword($user, $oldPlainPassword);

            $user = $this->userRepository->findOneBy([
                'password' => $oldHashedPassword,
            ]);

            if (! $this->userPasswordHasher->isPasswordValid($user, $oldPlainPassword))
            {
                $plainPassword = $passwordForm->get('password')
                    ->getData();
                $hashedPassword = $this->userPasswordHasher->hashPassword($user, $plainPassword);
                $this->userRepository->upgradePassword($user, $hashedPassword);
                $this->userRepository->add($user, true);
                $this->addFlash('success', 'password updated successfully');

                return $this->redirectToRoute('user_account');
            }

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/set-password.html.twig', [
            'passwordForm' => $passwordForm->createView(),
            'error' => $error,
        ]);
    }
}
