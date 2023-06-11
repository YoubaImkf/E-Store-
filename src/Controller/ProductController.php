<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route("/product/{id}", name:"product.detail")]
    public function index(Product $product): Response
    {
        return $this->render('product/detail.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route("/category/{id}/products", name: "category.products")]
    public function showCategoryProducts(Category $category): Response
    {
        $products = $category->getProducts();

        return $this->render('product/products.html.twig', [
            'products' => $products,
        ]);
    }

}
