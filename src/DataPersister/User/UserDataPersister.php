<?php

declare(strict_types=1);

namespace App\DataPersister\User;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\User;
use App\Service\Email\EmailService;
use App\Service\Email\EmailTemplate;
use DateTime;
use Symfony\Component\HttpFoundation\Request;

final class UserDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private EmailService $emailService
    ) {
    }

    /**
     * @param array<mixed> $context
     */
    public function supports(mixed $data, array $context = []): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User         $data
     * @param array<mixed> $context
     */
    public function persist($data, array $context = []): \App\Entity\User
    {
        if (strtoupper($context['collection_operation_name']) === Request::METHOD_POST) {
            $this->emailService->send(
                $data,
                EmailTemplate::VERIFY_EMAIL['subject'],
                __DIR__ . '/../../../templates/emails/signup.html.twig',
                [
                    'fullname' => $data->getFullName(),
                    'link' => 'https://residewise.com/confirm/' . $data->getToken(),
                    'expiration_date' => new DateTime('+7 days'),
                ]
            );
        }

        return $data;
    }

    /**
     * @param User         $data
     * @param array<mixed> $context
     */
    public function remove($data, array $context = []): void
    {
        // call your persistence layer to delete $data
    }
}
