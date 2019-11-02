<?php

namespace App\Controller;

use App\Model\ProductManager;

class ProductController extends AbstractController
{
    public function index()
    {
        $productManager = new ProductManager();
        $products = $productManager->selectAll();
        return $this->twig->render('Product/index.html.twig', ['products' => $products]);
    }

    public function indexUniverse(string $universe)
    {
        $productManager = new ProductManager();
        $products = $productManager->selectUniverse($universe);
        var_dump($products);
        return $this->twig->render('Product/index.html.twig', ['products' => $products]);
    }
}
