<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product')]
final class ProductController extends AbstractController
{
   // #[Route(name: 'app_product_index', methods: ['GET'])]
   // public function index(ProductRepository $productRepository): Response
   // {
   //     return $this->render('product/index.html.twig', [
   //         'products' => $productRepository->findAll(),
   //     ]);
   // }

    #[Route('/admin/user-list', name: 'app_product_userlist')]
    public function userList(ProductRepository $productRepository, Security $security): Response
    {
        $user = $security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        if ($security->isGranted('ROLE_VISITEUR')) {
            throw $this->createAccessDeniedException('Accès réservé aux professionnels et administrateurs.');
        }

        if ($security->isGranted('ROLE_ADMIN')) {
            $products = $productRepository->findAll();
        } else {
            $products = $productRepository->findBy(['user' => $user]);
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_PRO')) {
            throw $this->createAccessDeniedException();
        }

        $product = new Product();
        $product->setUser($this->getUser());

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('fichier-image')->getData();

            if ($image) {
                $product->setImage("tmp.jpg");
                $entityManager->persist($product);
                $entityManager->flush();

                $filename = 'image-' . $product->getId() . '.' . $image->guessExtension();
                $product->setImage($filename);
                $image->move('uploads', $filename);
            }

            $entityManager->persist($product);
            $entityManager->flush();

            $subcategoryId = $product->getSubcategory()->getId();

            return $this->redirectToRoute('app_subcategory_show', [
                'id' => $subcategoryId
            ]);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($product->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modifier ce produit.');
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('fichier-image')->getData();

            if ($image) {
                if (file_exists('uploads/' . $product->getImage()))
                    unlink('uploads/' . $product->getImage());

                $filename = 'image-' . $product->getId() . '.' . $image->guessExtension();
                $product->setImage($filename);
                $image->move('uploads', $filename);
            }
            $entityManager->flush();

            $subcategoryId = $product->getSubcategory()->getId();

            return $this->redirectToRoute('app_subcategory_show', [
                'id' => $subcategoryId
            ]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($product->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas supprimer ce produit.');
        }

        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->getPayload()->getString('_token'))) {
            if (file_exists('uploads/' . $product->getImage()))
                unlink('uploads/' . $product->getImage());
            $entityManager->remove($product);
            $entityManager->flush();
        }

        $subcategoryId = $product->getSubcategory()->getId();

        return $this->redirectToRoute('app_subcategory_show', [
            'id' => $subcategoryId
        ]);
    }
}
