<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Form\RegistrationFormType;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/security')]
class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly MailerInterface $mailer,
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserFactory $userFactory,
    )
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserAuthenticatorInterface $userAuthenticator,
        AppCustomAuthenticator $authenticator,
    ): ?Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->createUserAccount($form);
            $this->entityManager->flush();
            $this->sendUserConfirmationEmail($user);

            return $userAuthenticator->authenticateUser($user, $authenticator, $request);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    private function sendUserConfirmationEmail(UserInterface $user): void
    {
        assert($user instanceof User);

        $email = new TemplatedEmail();
        $email->from('fabien@example.com');
        $email->to(new Address($user->getEmail()));
        $email->subject($this->translator->trans('email.confirmation.subject'));
        $email->htmlTemplate('emails/confirm-email-address.html.twig');
        $email->context([
            'user' => $user,
            'path' => $this->urlGenerator->generate('app_account_confirm', [
                'token' => $user->getToken(),
            ]),
        ]);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception) {
            $this->logger->log(null, $exception->getMessage());
        }

    }

    private function createUserAccount(FormInterface $form): User
    {
        $user = $this->userFactory->create(
            firstName: $form->get('firstName')
                ->getData(),
            lastName: $form->get('lastName')
                ->getData(),
            email: $form->get('email')
                ->getData(),
            password: $form->get('plainPassword')
                ->getData(),
            roles: $form->get('roles')
                ->getData()
        );
        $this->entityManager->persist($user);

        return $user;
    }
}