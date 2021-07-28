<?php

declare(strict_types=1);

namespace App\Tests\api;

use \ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use \Symfony\Component\HttpFoundation\Request;

class UserLoginTest extends \ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase
{
    public function testLoginUser(): void
    {
        $client = self::createClient();
        $client->request(\Symfony\Component\HttpFoundation\Request::METHOD_POST, '/api/login_check', [
            'json' => [
                'email' => 'john.doe.test.3183@example.com',
                'password' => '12345678',
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);
    }
}
