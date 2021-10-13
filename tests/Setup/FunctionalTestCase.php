<?php

namespace App\Tests\Setup;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Exception;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

abstract class FunctionalTestCase extends ApiTestCase
{

    protected Client $client;
    protected EntityManager $entityManager;
    protected readonly ?User $user;

    public function setUp(): void
    {
        $this->client = $this->createClient();
        $this->entityManager = $this->getEntityManager();
        $this->client->setDefaultOptions([
            'base_uri' => 'https://localhost:8000'
        ]);
    }

    /**
     * @throws Exception
     */
    private function getEntityManager(): EntityManager
    {
        try {
            return $this->client->getContainer()->get('doctrine.orm.default_entity_manager');
        } catch (Exception $exception) {
            throw  new Exception('Entity manager cannot be loaded', $exception->getMessage());
        }
    }

}
