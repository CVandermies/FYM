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
    /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render("fym/home.html.twig", [
            'title' => "Yo les gars !",
        ]);
    }
}
