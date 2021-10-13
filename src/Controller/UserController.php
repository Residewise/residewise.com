<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class UserController extends AbstractController
{
    #[Route(path: '/me', name: 'user_data', defaults: [
        'api_resource_class' => User::class,
        'api_item_operation_name' => 'me',
    ], methods: [Request::METHOD_GET], )]
    public function __invoke(): JsonResponse
    {
        return $this->json($this->getUser());
    }
}
