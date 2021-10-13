<?php

declare(strict_types=1);

namespace App\Tests\api\Registration;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\Setup\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Request;
final class UserRegisterTest extends FunctionalTestCase
{
    public function testRegisterUser(): void
    {

        $this->client->request(Request::METHOD_POST, '/api/users', [
            'json' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'email' => 'john.doe.test.' . \random_int(1, 90000) . '@example.com',
                'password' => '12345678',
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
    }
}
