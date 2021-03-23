<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class FymController extends AbstractController
{
    /**
     * @Route("/fym", name="fym")
     */
    public function index(MovieRepository $repo): Response
    {
        
        $movies = $repo->findAll();

        return $this->render('fym/index.html.twig', [
            'controller_name' => 'FymController',
            'movies' => $movies
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render("fym/home.html.twig", [
            'title' => "Yo les gars !",
        ]);
    }

    
    /**
     * @Route("/fym/new", name="movie_create")
     */
    public function createMovie(Request $request, EntityManagerInterface $manager){
        $movie = new Movie();

        $form = $this->createFormBuilder($movie)
                        ->add('title',)
                        ->add('content',)
                        ->add('rating',)
                        ->add('image',)
                        ->add('category',)
                        ->getForm();

        return $this->render("fym/createMovie.html.twig", [
            'formMovie' => $form->createView()
        ]);    
    }

    /**
     * @Route("/fym/movie/{id}", name="movie_show")
     */
//show concerned movie note
public function showMovie(Movie $movie){
       
    return $this->render("fym/showMovie.html.twig", [
        'movie' => $movie
    ]);
}
}
