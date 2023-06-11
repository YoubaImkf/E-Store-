<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private RequestStack $requestStack;
    private EntityManagerInterface $entityManagerInterface;
    
    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManagerInterface) {
        $this->requestStack = $requestStack;
        $this->entityManagerInterface = $entityManagerInterface;
        
    }

    public function addToCart(int $id, int $quantity): void {
        
        $cart = $this->requestStack->getSession()->get('cart', []);
        
        if (!empty($cart[$id])) {
            $cart[$id] += $quantity;
        }else{
            $cart[$id] = $quantity;
        }

        $this->getSession()->set('cart', $cart);
    }

    public function removeToCart(int $id): void {
        
        $cart = $this->requestStack->getSession()->get('cart', []);
        unset($cart[$id]);

        $this->getSession()->set('cart', $cart);
    }


    public function getTotal(): array {
        $cart = $this->getSession()->get('cart');
        $cartData = [];
        
        if ($cart) {
            foreach ( $cart as $id => $quantity){
                $product = $this
                ->entityManagerInterface
                ->getRepository(Product::class)->findOneBy(['id'=>$id]);
                if ($product) {
                    $cartData[] = [
                        'product' => $product,
                        'quantity' => $quantity
                    ];
                }
            }
        }
        return $cartData;
    }

    public function updateQuantity(int $id, int $quantity): void
    {
        $cart = $this->getSession()->get('cart', []);
    
        if (!empty($cart[$id])) {
            $cart[$id] = $quantity;
        }
    
        $this->getSession()->set('cart', $cart);
    }
    
    public function clearCart(): void {
        $this->getSession()->remove('cart');
    }

    public function calculateTotal(array $cart): float {
        $total = 0;

        foreach ($cart as $item) {
            $product = $item['product'];
            $quantity = $item['quantity'];
            $total += $product->getPrice() * $quantity;
        }

        return $total;
    }

    # private methods
    private function getSession() {
        return $this->requestStack->getSession();
    }

}