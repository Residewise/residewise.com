<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class UserConfirmation
{

  #[Assert\NotBlank]
  #[Assert\Length(min: 50, max: 50)]
  private string $token;
}
