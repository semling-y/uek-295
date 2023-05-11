<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TestDataFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $genre = new  Genre();
        $genre->setGenre("Coole Genre");

        $manager->persist($genre);

        $movie = new Movie();
        $movie->setName("Cooler Titel");
        $movie->setDescription("Coole Beschreibung.");
        $movie->setAgerest(9);
        $movie->setRating(5);
        $movie->setGenre($genre);

        $manager->persist($movie);



        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
