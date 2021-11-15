<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var string[]
     */
    private $categories = [
        'Action',
        'Comedy',
        'Horror',
        'Thriller',
        'Science Fiction'
    ];

    /**
     * @var UserPasswordHasherInterface
     */
    private $hasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->hasher = $passwordHasher;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
       $this->addCategories($manager);
//       $this->addAdmin($manager);

        $manager->flush();
    }

    /**
     * @param $manager
     */
    public function addCategories($manager)
    {
        foreach ($this->categories as $category) {
            $c = new Category();
            $c->setName($category);

            $manager->persist($c);
        }
    }

    public function addAdmin($manager)
    {
        $admin = new User();
        $admin->setFirstName('emma');
        $admin->setLastName('007');
        $admin->setUsername('emma007');
        $admin->setPassword(
            $this->hasher->hashPassword(
                $admin,
                'admin'
            )
        );
        $admin->setRoles([
           'ROLE_ADMIN'
        ]);

        $manager->persist($admin);
    }
}
