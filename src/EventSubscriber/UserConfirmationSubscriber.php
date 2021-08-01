<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use App\Entity\UserConfirmation;
use App\Repository\UserRepository;
use App\Security\TokenGenerator;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class UserConfirmationSubscriber implements EventSubscriberInterface
{
    /**
     * __construct.
     */
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private TokenGenerator $tokenGenerator
    )
    {
    }

    /**
     * @return array<string, array<int|string>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['confirmUser', EventPriorities::POST_VALIDATE],
        ];
    }

    public function confirmUser(ViewEvent $viewEvent): void
    {
        /** @var UserConfirmation $entity */
        $entity = $viewEvent->getControllerResult();
        $method = $viewEvent->getRequest()->getMethod();
        $request = $viewEvent->getRequest();

        if (! $entity instanceof UserConfirmation || $method !== Request::METHOD_POST || $request->get('_route') !== 'api_user_confirmations_post_collection')
        {
            return;
        }

        /** @var User $user */
        $user = $this->userRepository->findOneBy(
            [
                'token' => $entity->getToken(),
            ]
        );

        $newToken = $this->tokenGenerator->generateToken();
        $user->setToken($newToken);
        $user->setVerifiedAt(new DateTimeImmutable());
        $user->setIsEnabled(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $viewEvent->setResponse(
            new JsonResponse(null, 200)
        );
    }
}
