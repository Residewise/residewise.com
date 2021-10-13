<?php

declare(strict_types=1);

namespace App\Tests\api\Authentication;

use App\Tests\Setup\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Request;
use function dump;
use function json_decode;
use function preg_match_all;

final class UserLoginTest extends FunctionalTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testLoginUser(): void
    {

        $response = $this->client->request(Request::METHOD_POST, '/api/login_check', [
            'json' => [
                'email' => 'tenant@fixture.test',
                'password' => '12345678'
            ]
        ]);

        $this->assertResponseIsSuccessful();

        $jwtPattern = '/(^[\w-]*\.[\w-]*\.[\w-]*$)/';
        $responseArray = $response->toArray();
        preg_match($jwtPattern, $responseArray['token'], $match);

        $this->assertJsonEquals([
            'token' => $match[0]
        ]);

    }
}
