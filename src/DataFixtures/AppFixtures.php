<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $data = array(
            array(
                "plaintextPassword" => "user",
                "plaintextEmail" => "user@gmail.com",
                "role" => ['ROLE_USER']
            ),
            array(
                "plaintextPassword" => "admin",
                "plaintextEmail" => "admin@gmail.com",
                "role" => ['ROLE_ADMIN']
            )
        );
        foreach ($data as $d) {
            $user = new User();
            $user->setEmail($d["plaintextEmail"]);
            $user->setRoles($d["role"]);
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $d["plaintextPassword"]
            );
            $user->setPassword($hashedPassword);
            $manager->persist($user);
        }

        $eventData = [
            [
                'name' => 'Awesome Conference',
                'startDate' => new \DateTime('-1 week'), // One week ago
                'endDate' => new \DateTime('+2 days'), // Two days from now
                'city' => 'New York City',
                'price' => 150.00,
            ],
            [
                'name' => 'Coding Bootcamp',
                'startDate' => new \DateTime('+1 month'), // One month from now
                'endDate' => new \DateTime('+1 month 1 week'), // One week after start date
                'city' => 'London',
                'price' => 99.99,
            ],
            [
                'name' => 'Design Workshop',
                'startDate' => new \DateTime('+3 months'), // Three months from now
                'endDate' => new \DateTime('+3 months 2 weeks'), // Two weeks after start date
                'city' => 'Paris',
                'price' => 75.00,
            ],
        ];
        foreach ($eventData as $data) {
            $event = new Event();
            $event->setName($data['name']);
            $event->setStartDate($data['startDate']);
            $event->setEndDate($data['endDate']);
            $event->setCity($data['city']);
            $event->setPrice($data['price']);

            $manager->persist($event);
        }

        $manager->flush();
    }
}
