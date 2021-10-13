<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Security\TokenGenerator;
use App\Service\AvatarService;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private readonly mixed $faker;
    public final const ADMIN_FIXTURE_REFERENCE = 'ADMIN_FIXTURE_REFERENCE';
    public final const LANDLORD_FIXTURE_REFERENCE = 'LANDLORD_FIXTURE_REFERENCE';
    public final const TENANT_FIXTURE_REFERENCE = 'TENANT_FIXTURE_REFERENCE';

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        private readonly TokenGenerator $tokenGenerator,
        private readonly AvatarService $avatarService
    ) {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $admin = $this->createAdminUser();
        $this->addReference(self::ADMIN_FIXTURE_REFERENCE, $admin);
        $manager->persist($admin);

        $landlord = $this->createLandlordUser();
        $this->addReference(self::LANDLORD_FIXTURE_REFERENCE, $landlord);
        $manager->persist($landlord);

        $tenant = $this->createTenantUser();
        $this->addReference(self::TENANT_FIXTURE_REFERENCE, $tenant);
        $manager->persist($tenant);

        $manager->flush();
    }

    public function createAdminUser(): User
    {
        $user = new User();
        $user->setRoles([User::ROLE_ADMIN]);
        $user->setIsEnabled(true);
        $user->setEmailVerifiedAt(new DateTimeImmutable());
        $user->setFirstName($this->faker->firstName());
        $user->setLastName($this->faker->lastName());
        $user->setEmail('admin@fixture.test');
        $user->setAvatar($this->avatarService->createAvatar($user->getEmail()));
        $user->setToken($this->tokenGenerator->generateToken());
        $user->setIdentityVerifiedAt(new DateTimeImmutable());
        $password = $this->hasher->hashPassword($user, '12345678');
        $user->setPassword($password);

        return $user;
    }

    public function createLandlordUser(): User
    {
        $user = new User();
        $user->setRoles([User::ROLE_LANDLORD]);
        $user->setIsEnabled(true);
        $user->setEmailVerifiedAt(new DateTimeImmutable());
        $user->setFirstName($this->faker->firstName());
        $user->setLastName($this->faker->lastName());
        $user->setEmail('landlord@fixture.test');
        $user->setAvatar($this->avatarService->createAvatar($user->getEmail()));
        $user->setToken($this->tokenGenerator->generateToken());
        $user->setIdentityVerifiedAt(new DateTimeImmutable());
        $password = $this->hasher->hashPassword($user, '12345678');
        $user->setPassword($password);

        return $user;
    }

    public function createTenantUser(): User
    {
        $user = new User();
        $user->setRoles([User::ROLE_TENANT]);
        $user->setIsEnabled(true);
        $user->setEmailVerifiedAt(new DateTimeImmutable());
        $user->setFirstName($this->faker->firstName());
        $user->setLastName($this->faker->lastName());
        $user->setEmail('tenant@fixture.test');
        $user->setAvatar($this->avatarService->createAvatar($user->getEmail()));
        $user->setToken($this->tokenGenerator->generateToken());
        $password = $this->hasher->hashPassword($user, '12345678');
        $user->setPassword($password);

        return $user;
    }


}
