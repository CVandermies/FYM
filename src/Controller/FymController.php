<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/fym/movie/{id}", name="movie_show")
     */
//show concerned movie note
    public function showMovie(Movie $movie){
       
        return $this->render("fym/showMovie.html.twig", [
            'movie' => $movie
        ]);
    }
}
