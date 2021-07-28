<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntryPointController extends AbstractController
{
    #[Route('/{route}', name: 'main_entry_point')]
  public function main(): Response
  {
      return $this->render('base.html.twig');
  }

    #[Route('/', name: 'second_entry_point')]
  public function second(): Response
  {
      return $this->render('base.html.twig');
  }
}
