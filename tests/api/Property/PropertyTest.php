<?php

declare(strict_types=1);

namespace App\Tests\api\Property;

use App\Entity\Property;
use App\Tests\Setup\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Request;

final class PropertyTest extends FunctionalTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testGetCollectionOperation() : void
    {
        $this->client->request(Request::METHOD_GET, 'api/properties');

        $this->assertResponseIsSuccessful();
        $this->assertMatchesResourceCollectionJsonSchema(Property::class, 'get');
    }

}
