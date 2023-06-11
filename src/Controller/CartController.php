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
use Symfony\Component\HttpFoundation\JsonResponse;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cartService->getTotal(),
        ]);
    }
    

    #[Route('/add/{id}', name: 'add', requirements: ['id' => '\d+'])]
    public function addToCart(int $id, CartService $cartService, Request $request)
    {
        $quantity = $request->get('quantity'); // Retrieve quantity directly from request
        $cartService->addToCart($id, $quantity);
        return $this->redirectToRoute('cart');
    }
    
    

    #[Route('/remove/{id}', name:'remove', requirements: ['id' => '\d+'])]
    public function removeToCart(int $id, CartService $cartService)
    {
        $cartService->removeToCart($id);
        return $this->redirectToRoute('cart');
    }

    #[Route('/update/{id}', name: 'update_quantity', requirements: ['id' => '\d+'])]
    public function updateCartItem(int $id, CartService $cartService, Request $request)
    {
        $quantity = $request->get('quantity');
        $cartService->updateQuantity($id, $quantity);
        return $this->redirectToRoute('cart');
    }
    
    #[Route('/checkout', name: 'checkout')]
    public function thankYou(CartService $cartService): Response
    {
        $cart = $cartService->getTotal();
        $total = $cartService->calculateTotal($cart); // Replace `calculateTotal()` with your logic to calculate the total
        
        // Clear the cart
        $cartService->clearCart();
    
        return $this->render('cart/checkout.html.twig', [
            'cart' => $cart,
            'total' => $total,
        ]);
    }
    
    
}
