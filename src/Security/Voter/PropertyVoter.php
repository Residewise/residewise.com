<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Property;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class PropertyVoter extends Voter
{
    public final const PROPERTY_VIEW = 'PROPERTY_VIEW';

    public final const PROPERTY_EDIT = 'PROPERTY_EDIT';

    public final const PROPERTY_DELETE = 'PROPERTY_DELETE';

    public final const ACTIONS = [
        self::PROPERTY_VIEW,
        self::PROPERTY_EDIT,
        self::PROPERTY_DELETE,
    ];

    public function __construct(
        private readonly Security $security
    ) {
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, self::ACTIONS, true)
            && $subject instanceof Property;
    }

    /**
     * @param Property $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $token->getUser();

        return match ($attribute) {
            self::PROPERTY_VIEW => $this->canViewProperty(),
            self::PROPERTY_EDIT => $this->canEditProperty($subject),
            self::PROPERTY_DELETE => $this->canDeleteProperty($subject),
            default => false
        };
    }

    private function canViewProperty(): bool
    {
        return true;
    }

    private function canEditProperty(Property $property): bool
    {
        return $property->getPropertyOwners()->contains($user);
    }

    private function canDeleteProperty(Property $property): bool
    {
        return $this->security->isGranted(User::ROLE_USER);
    }
}
