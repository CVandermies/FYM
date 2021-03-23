<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    //Add fake info
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        //create 3 categories
        for ($i=0; $i <= 3 ; $i++) { 
            $category = new Category();
            $category->setLabel($faker->sentence())
                    ->setDescription($faker->paragraph());
            $manager->persist($category);
            
            //create 4 to 6 movies       
            for ($j=1; $j <= mt_rand(4,6) ; $j++) {
                $movie = new Movie();
                $content ='<p>'.join($faker->paragraphs(5), '</p><p>').'</p>';

                $movie  ->setTitle($faker->sentence())
                        ->setContent($content) 
                        ->setRating($faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 5))
                        ->setImage("http://placehold.it/350x150")   
                        ->setCategory($category);
                $manager->persist($movie);               
            }
        }

        $manager->flush();
    }
}
