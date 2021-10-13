<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;

#[ApiResource(
    collectionOperations: [
    ],
    itemOperations: [
        'get' => [
            'path' => '/users/confirmation/{id}'
        ],
    ]
)]
class UserConfirmation
{
    #[ApiProperty(identifier: true)]
    private string $token;

    public function getToken(): string
    {
        return $this->token;
    }
}
