<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        for ($i=1; $i <= 5 ; $i++) {
            $movie = new Movie();
            $content ='<p>'.join($faker->paragraphs(5), '</p><p>').'</p>';

            $movie  ->setTitle($faker->sentence())
                    ->setContent($content) 
                    ->setRating($faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 5))
                    ->setImage("http://placehold.it/350x150")
                    ->setCategory($faker->randomNumber());      
            $manager->persist($movie);               
        }

        $manager->flush();
    }
}
