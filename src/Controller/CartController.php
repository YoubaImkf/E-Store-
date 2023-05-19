<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\CartService;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'cart' => $cartService->getTotal(),
        ]);
    }

    #[Route('/add/{id}', name:'add', requirements: ['id' => '\d+'])]
    public function addToCart(int $id, CartService $cartService, Request $request)
    {
        $quantity = $request->request->get('quantity');
        $cartService->addToCart($id, $quantity);
        return $this->redirectToRoute('cart');
    }

    #[Route('/remove/{id}', name:'remove', requirements: ['id' => '\d+'])]
    public function removeToCart(int $id, CartService $cartService)
    {
        $cartService->removeToCart($id);
        return $this->redirectToRoute('cart');
    }
}
