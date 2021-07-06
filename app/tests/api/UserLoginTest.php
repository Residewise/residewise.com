<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Request;

class UserLoginTest extends ApiTestCase
{
  public function testLoginUser()
  {
    $client = self::createClient();
    $client->request(Request::METHOD_POST, '/api/login_check', [
      'json' => [
        'email' => 'john.doe.test.3183@example.com',
        'password' => '12345678'
      ]
    ]);

    $this->assertResponseStatusCodeSame(200);
  }
}
