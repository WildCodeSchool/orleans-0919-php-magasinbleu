<?php

namespace App\Controller;

use App\Model\HomeManager;
use App\Model\ProductManager;

class HomeController extends AbstractController
{
    public function index()
    {
        $universeManager = new HomeManager();
        $universes = $universeManager->selectAll();
        $productManager = new ProductManager();
        $products = $productManager->lastProduct();
        return $this->twig->render('Home/index.html.twig', ['universes' => $universes, 'products' => $products]);
    }
}
