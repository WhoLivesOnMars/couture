<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $visiteur = new User();
        $visiteur->setEmail('visiteur@mail.com');
        $visiteur->setPassword(
            $this->passwordHasher->hashPassword($visiteur, 'visiteur12345!')
        );
        $visiteur->setRoles(['ROLE_VISITEUR']);
        $manager->persist($visiteur);

        $pro = new User();
        $pro->setEmail('pro@mail.com');
        $pro->setPassword(
            $this->passwordHasher->hashPassword($pro, 'pro12345!')
        );
        $pro->setRoles(['ROLE_PRO']);
        $manager->persist($pro);

        $admin = new User();
        $admin->setEmail('admin@mail.com');
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'admin12345!')
        );
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $manager->flush();
    }
}
