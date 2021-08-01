<?php
// api/src/Controller/CreateBookPublication.php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class PropertyController extends AbstractController
{

    public function __construct(
      private PropertyRepository  $propertyRepository
      )
    {
    }

}