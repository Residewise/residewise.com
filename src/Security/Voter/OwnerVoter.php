<?php

declare(strict_types = 1);

namespace App\Security\Voter;

use App\Entity\Contract\UserOwnedEntityInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class OwnerVoter extends Voter
{
    public const EDIT = 'EDIT';

    public const VIEW = 'VIEW';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW], true)
            && $subject instanceof UserOwnedEntityInterface;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (! $user instanceof UserInterface) {
            return false;
        }

        return match ($attribute){
         self::VIEW, self::EDIT => $user === $subject->getOwner(),
         default => false
        };
    }
}
