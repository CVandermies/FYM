<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiFymController extends AbstractController
{
    /**
     * @Route("/api/fym", name="api_fym_index", methods={"GET"})
     */
    public function index(MovieRepository $movieRepository)
    {
        return $this->json($movieRepository->findAll(), 200, [], ['groups' => 'movies:read']);
    }

    /**
     * @Route("/api/fym", name="api_fym_create", methods={"POST"})
     */

    public function addMovie(Request $request)
    {
        $data = json_decode($request->getContent());

        $entityManager = $this->getDoctrine()->getManager();
        $movie= new Movie();

        $movie->setTitle($data->title);
        $movie->setContent($data->content);
        $movie->setRating($data->rating);
        $movie->setImage($data->image);
        $category= $entityManager->getRepository(Category::class)->find($data->category_id);
        $movie->setCategory($category);
        $entityManager->persist($movie);
        
        return new Response("ok", 201);
        
    }
}
