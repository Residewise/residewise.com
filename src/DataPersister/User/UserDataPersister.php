<?php

declare(strict_types=1);

namespace App\DataPersister\User;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\User;
use App\Service\Email\EmailService;
use App\Service\Email\EmailTemplate;
use DateTime;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

final class UserDataPersister implements ContextAwareDataPersisterInterface
{
    private $logger;

    private $emailService;

    public function __construct(
        LoggerInterface $logger,
        EmailService $emailService
    ) {
        $this->logger = $logger;
        $this->emailService = $emailService;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     */
    public function persist($data, array $context = [])
    {
        if (Request::METHOD_POST === strtoupper($context['collection_operation_name'])) {
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

    public function remove($data, array $context = [])
    {
        // call your persistence layer to delete $data
    }
}
