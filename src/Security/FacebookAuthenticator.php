<?php

declare(strict_types = 1);

namespace App\Security;

use App\Factory\SocialAuthFactory;
use App\Factory\UserFactory;
use App\Repository\SocialAuthRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Provider\FacebookUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class FacebookAuthenticator extends OAuth2Authenticator
{
    private const OAUTH_PROVIDER = 'FACEBOOK_AUTH_PROVIDER';

    private const ROUTE = 'connect_facebook_check';

    public function __construct(
        private readonly ClientRegistry $clientRegistry,
        private readonly SocialAuthRepository $socialAuthRepository,
        private readonly UserRepository $userRepository,
        private readonly RouterInterface $router,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserFactory $userFactory,
        private readonly SocialAuthFactory $socialAuthFactory
    ) {
    }

    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') === self::ROUTE;
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('facebook');
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($accessToken, $client) {
                /** @var FacebookUser $facebookUser */
                $facebookUser = $client->fetchUserFromToken($accessToken);
                $email = $facebookUser->getEmail();

                // 1) have they logged in with Facebook before? Easy!
                $socialAuth = $this->socialAuthRepository->findOneBy([
                    'token' => $facebookUser->getId(),
                ]);

                if ($socialAuth !== null) {
                    return $socialAuth->getOwner();
                }

                // 2) do we have a matching user by email?
                $user = $this->userRepository->findOneBy([
                    'email' => $email,
                ]);

                if ($user === null) {
                    // 3) Maybe you just want to "register" them
                    $user = $this->userFactory->create(
                        firstName: $facebookUser->getFirstName(),
                        lastName: $facebookUser->getLastName(),
                        email: $facebookUser->getEmail(),
                    );

                    $socialAuth = $this->socialAuthFactory->create(
                        token: $facebookUser->getId(),
                        provider: self::OAUTH_PROVIDER,
                        owner: $user
                    );
                    $this->entityManager->persist($socialAuth);
                    $this->entityManager->flush();
                }

                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // change "app_homepage" to some route in your app
        $targetUrl = $this->router->generate('user_account');

        return new RedirectResponse($targetUrl);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }
}