<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\FacebookUser;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
            ->redirect(['https://www.googleapis.com/auth/userinfo.email ', 'https://www.googleapis.com/auth/userinfo.profile'], []);
    }

    #[Route(path: '/connect/check', name: 'connect_google_check')]
    public function check() : void
    {
    }

}
