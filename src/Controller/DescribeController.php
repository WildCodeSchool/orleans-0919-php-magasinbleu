<?php

namespace App\Controller;

use App\Model\ProductManager;

class DescribeController extends AbstractController
{
    public function describe()
    {
        $productManager = new ProductManager();
        $products = $productManager->selectAll();
        return $this->twig->render('Describe/describe.html.twig', ['products' => $products]);
    }
}
