<?php

declare(strict_types=1);

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Request;

class UserRegisterTest extends ApiTestCase
{
    public function testRegisterUser()
    {
        $client = self::createClient();
        $client->request(Request::METHOD_POST, '/api/users', [
            'json' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'email' => 'john.doe.test.' . random_int(1, 90000) . '@example.com',
                'password' => '12345678',
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
    }
}
