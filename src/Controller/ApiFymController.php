<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Movie;
use App\Repository\CategoryRepository;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiFymController extends AbstractController
{
    //////////////
    //CATEGORIES//
    //////////////

    /**
     * @Route("/api/cat", name="api_cat_get", methods={"GET"})
     */
    public function getCategories(CategoryRepository $cr)
    {
        return $this->json($cr->findAll(), 200, [], ['groups' => 'movies:read']);
    }


    /**
     * @Route("/api/cat/{id}", name="api_cat_get_by_id", methods={"GET"})
     */
    public function getCategoryById(CategoryRepository $cr, $id)
    {
        return $this->json($cr->find($id), 200, [], ['groups' => 'movies:read']);
    }

    /**
     * @Route("/api/cat/add", name="api_cat_create", methods={"POST"}) 
     */

    public function addCategory(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator, CategoryRepository $cr)
    {
        $jsonRecu = $request->getContent();
        try {
            $category = $serializer->deserialize($jsonRecu, Category::class, 'json');
            $errors = $validator-> validate($category);

            if(count($errors)>0){
                return $this->json($errors, 400);
            }

            $em->persist($category);
            $em->flush();
            
            return $this->json($category, 201, [], ['groups' => 'movies:read']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
        
    }

    /**
     * @Route("/api/cat/{id}", name="api_cat_edit", methods={"PUT"})
     */
    public function editCategory(Request $request, ValidatorInterface $validator, EntityManagerInterface $em, SerializerInterface $serializer, CategoryRepository $cr, $id ){

        $jsonRecu = $request->getContent();
        try{
            $data = $serializer->deserialize($jsonRecu, Category::class, 'json');
            $categoryEdited = $cr->find($id);

            $categoryEdited->setLabel($data->getLabel());
            if($data->getDescription() ==! null){
                $categoryEdited->setDescription($data->getDescription());
            }

            $errors = $validator->validate($categoryEdited);
            if(count($errors)>0){
                return $this->json($errors,400);
            }

            $em->persist($categoryEdited);
            $em->flush();

            return $this->json([
                'message' => 'Categorie editee',
                'label' => $categoryEdited->getLabel(),
                'description' => $categoryEdited->getDescription()]
            );

        } catch(NotEncodableValueException $e){
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()], 400
            );
        }
    }

    /**
     * @Route("/api/cat/{id}", name="api_cat_del", methods={"DELETE"})
     */
    public function delCategory(CategoryRepository $cr, EntityManagerInterface $em, $id)
    {
        $category = $cr->find($id);
        if ($category == null){
            return $this->json([
                'status' => 400,
                'message' => "Aucune categorie correspondant a cet ID"], 400
            );
        }
        $moviesToModify = $category->getMovies();

        foreach ($moviesToModify as $movieTM){
            $movieTM->setCategory(null);
        }
        $em->remove($category);
        $em->flush();

        return $this->json([
            'message' => 'Categorie supprimee']
        );
    }


    //////////
    //MOVIES//
    //////////

    /**
     * @Route("/api/fym", name="api_fym_get", methods={"GET"})
     */
    public function indexMovies(MovieRepository $mr)
    {
        //grâce à l'AbstractController, on peut sérialiser et créer un reponse json en une seule opération
        return $this->json($mr->findAll(), 200, [], ['groups' => 'movies:read']);
    }

    /**
     * @Route("/api/fym/{id}", name="api_fym_get_by_id", methods={"GET"})
     */
    public function getMovieById(MovieRepository $mr, $id)
    {
        return $this->json($mr->find($id), 200, [], ['groups' => 'movies:read']);
    }

    /**
     * @Route("/api/fym/{id_cat}", name="api_fym_create", methods={"POST"}) 
     */

    public function createMovie($id_cat = null, Request $request, EntityManagerInterface $em, SerializerInterface $serializer, ValidatorInterface $validator, CategoryRepository $cr)
    {
        $jsonRecu = $request->getContent();
        try {
            $movie = $serializer->deserialize($jsonRecu, Movie::class, 'json');
            
            $category= $cr->find($id_cat);
            if ($category == null){
                return $this->json([
                    'status' => 400,
                    'message' => "Aucune categorie correspondant a cet ID"], 400
                );
            }

            $category->addMovie($movie);
            $errors = $validator-> validate($movie);

            if(count($errors)>0){
                return $this->json($errors, 400);
            }

            $em->persist($movie);
            $em->flush();
            
            return $this->json($movie, 201, [], ['groups' => 'movies:read']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
        
    }

    /**
     * @Route("/api/fym/{id}", name="api_fym_edit", methods={"PUT"})
     */
    public function editMovie(Request $request, ValidatorInterface $validator, EntityManagerInterface $em, SerializerInterface $serializer, MovieRepository $mr, $id ){

        $jsonRecu = $request->getContent();
        try{
            $data = $serializer->deserialize($jsonRecu, Movie::class, 'json');
            $movieEdited = $mr->find($id);

            if($data->getTitle() ==! null){
                $movieEdited->setTitle($data->getTitle());
            }
            if($data->getContent() ==! null){
                $movieEdited->setContent($data->getContent());
            }
            if($data->getRating() ==! null){
                $movieEdited->setRating($data->getRating());
            }
            if($data->getImage() ==! null){
                $movieEdited->setImage($data->getImage());
            }
            if($data->getCategory() ==! null){
                $movieEdited->setCategory($data->getCategory());
            }

            $errors = $validator->validate($movieEdited);
            if(count($errors)>0){
                return $this->json($errors,400);
            }

            $em->persist($movieEdited);
            $em->flush();

            return $this->json([
                'message' => 'film edite'
            ]);

        } catch(NotEncodableValueException $e){
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()], 400
            );
        }
    }

    /**
     * @Route("/api/fym/{id}", name="api_fym_del", methods={"DELETE"})
     */
    public function delMovie(MovieRepository $mr, EntityManagerInterface $em, $id)
    {
        $movie = $mr->find($id);
        if ($movie == null){
            return $this->json([
                'status' => 400,
                'message' => "Aucun film correspondant a cet ID"], 400
            );
        }

        $em->remove($movie);
        $em->flush();

        return $this->json([
            'message' => 'Film supprime']
        );
    }

}
