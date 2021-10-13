<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class UserVoter extends Voter
{
    public final const USER_VIEW = 'USER_VIEW';

    public final const USER_EDIT = 'USER_EDIT';

    public final const USER_DELETE = 'USER_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::USER_VIEW, self::USER_EDIT, self::USER_DELETE], true)
            && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        return false;
    }
}
