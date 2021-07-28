<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;

#[ApiResource(
    collectionOperations: [
        'post' => [
            'path' => 'users/confirmation',
        ],
    ],
    itemOperations: []
)]
class UserConfirmation
{
    private string $token;

    public function getToken()
    {
        return $this->token;
    }
}
