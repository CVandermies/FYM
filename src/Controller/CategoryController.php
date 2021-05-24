<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

   

     /**
     * @Route("/categories", name="categoriesList")
     */
    public function categoriesList(CategoryRepository $repo): Response
    {
        $categories = $repo->findAll();

        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categories
        ]);
    }
    
    /**
     * @Route("/category/{id}", name="category_show")
     */
    //show concerned category note
    public function showCategory(Category $category)
    {        
        
        return $this->render("category/showCategory.html.twig", [
            'category' => $category,
            'liste' => $category->getMovies()
        ]);
    }


}
