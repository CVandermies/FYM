<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
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
            'title' => "Bonjour et bienvenue !",
        ]);
    }

    
    /**
     * @Route("/fym/new", name="movie_create")
     * @Route("/fym/movie/{id}/edit", name="movie_edit")
     */
    public function movieForm(Movie $movie = null, Request $request, EntityManagerInterface $manager){
        //create a movie only if it doesnt exist already
        if(!$movie){
            $movie = new Movie();          
        }

        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($movie);
            $manager->flush();

            return $this->redirectToRoute('movie_show', ['id' => $movie->getId()]);
        }

        return $this->render("fym/createMovie.html.twig", [
            'formMovie' => $form->createView(),
            'editMode' => $movie->getId() !== null
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
