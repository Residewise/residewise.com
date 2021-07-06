<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

final class UserDataPersister implements ContextAwareDataPersisterInterface
{

  private $logger;

  public function __construct(
    LoggerInterface $logger
  ) {
    $this->logger = $logger;
  }

  public function supports($data, array $context = []): bool
  {
    return $data instanceof User;
  }

  public function persist($data, array $context = [])
  {
    if (($context['collection_operation_name'] ?? null) == Request::METHOD_POST) {
      $this->logger->info('User just registered');
    }

    return $data;
  }

  public function remove($data, array $context = [])
  {
    // call your persistence layer to delete $data
  }
}
