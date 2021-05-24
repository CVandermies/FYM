<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/category/new", name="category_create")
     * @Route("/category/{id}/edit", name="category_edit")
     */
    public function categoryForm(Category $category = null, CategoryRepository $repo, Request $request, EntityManagerInterface $manager)
    {
        //create a category only if it doesnt exist already
        if(!$category){
            $category = new Category();          
        }
        else {
            $category = $repo->find($category);
        }
        //lie un nouveau form CategoryType à l'entité category
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($category);
            $manager->flush();

            return $this->redirectToRoute('category_show', ['id' => $category->getId()]);
        }

        return $this->render("category/createCategory.html.twig", [
            'formCategory' => $form->createView(),
            'editMode' => $category->getId() !== null
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


    /**
     * @Route("/category/{id}/delete", name="category_del")
     */
    //delete concerned category
    public function delCategory(Category $category, CategoryRepository $repo, EntityManagerInterface $manager){
        //trouver la categorie par son identifiant
        $todelete = $repo->find($category);
        //le supprimer grâce au manager
        $manager->remove($todelete);
        $manager->flush();

        return $this->redirectToRoute("categoriesList");
    }

}
