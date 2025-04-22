<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\SubcategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticPages extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    #[Route('/category', name: 'app_category_index')]
    public function indexCategory(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/category/{id}', name: 'app_category_show')]
    public function showCategory(int $id, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);
        $subcategories = $category->getSubcategories();

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'subcategories' => $subcategories,
        ]);
    }

    #[Route('/subcategory/{id}', name: 'app_subcategory_show')]
    public function showSubcategory(int $id, SubcategoryRepository $subcategoryRepository): Response
    {
        $subcategory = $subcategoryRepository->find($id);
        $category = $subcategory->getCategory();
        $subcategories = $category->getSubcategories();
        $products = $subcategory->getProducts();

        return $this->render('subcategory/show.html.twig', [
            'subcategory' => $subcategory,
            'category' => $category,
            'subcategories' => $subcategories,
            'products' => $products,
        ]);
    }
}
