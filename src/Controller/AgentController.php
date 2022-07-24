<?php

declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/agent')]
class AgentController extends AbstractController
{
    #[Route(path: '/account', name: 'user_account')]
    public function account(Request $request): Response
    {
        return $this->render('agent/account.html.twig', []);
    }
}