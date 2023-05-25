<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestDataFixture extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $passwordHasher){

    }

    public function load(ObjectManager $manager): void
    {
        $genre = new Genre();
        $genre->setGenre('Coole Genre');

        $manager->persist($genre);

        $movie = new Movie();
        $movie->setName('Cooler Titel');
        $movie->setDescription('Coole Beschreibung.');
        $movie->setAgerest(9);
        $movie->setRating(5);
        $movie->setGenre($genre);

        $user = new User();
        $user->setUsername('Admin');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'admin'));
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $manager->persist($user);
        $manager->persist($movie);

        $manager->flush();
    }
}
