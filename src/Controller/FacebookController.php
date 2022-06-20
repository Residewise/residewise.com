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

#[Route(path: '/social/facebook')]
class FacebookController extends AbstractController
{

    public function __construct(
        private readonly ClientRegistry $clientRegistry
    )
    {}

    #[Route(path: '/connect', name: 'connect_facebook_start')]
    public function connect(): Response
    {
        return $this->clientRegistry
            ->getClient('facebook')
            ->redirect(['public_profile', 'email'], []);
    }

    #[Route(path: '/connect/check', name: 'connect_facebook_check')]
    public function check() : void
    {
    }

}
