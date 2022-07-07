<?php

declare(strict_types = 1);

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/social/google')]
class GoogleController extends AbstractController
{
    public function __construct(
        private readonly ClientRegistry $clientRegistry
    )
    {}

    #[Route(path: '/connect', name: 'connect_google_start')]
    public function connect(): Response
    {
        return $this->clientRegistry
            ->getClient('google')
            ->redirect(
                ['https://www.googleapis.com/auth/userinfo.email ', 'https://www.googleapis.com/auth/userinfo.profile'],
                []
            );
    }

    #[Route(path: '/connect/check', name: 'connect_google_check')]
    public function check(): void
    {
    }
}
