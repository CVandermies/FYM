<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FymController extends AbstractController
{
    /**
     * @Route("/fym", name="fym")
     */
    public function index(): Response
    {
        return $this->render('fym/index.html.twig', [
            'controller_name' => 'FymController',
        ]);
    }
    
    public function home(){
        return $this->render("fym/home.html.twig", [
            'title' => "Yo les gars !",
        ]);
    }

    /**
     * @Route("/fym/movie/12", name="movie_show")
     */
    public function showMovie(){
        return $this->render("fym/showMovie.html.twig");
    }
}
