<?php

namespace App\Controller;

use App\Model\ProductManager;

class ProductController extends AbstractController
{

    const PRODUCTS_BY_PAGES = 12;


    public function index()
    {
        $productManager = new ProductManager();
        $products = $productManager->selectAll();
        return $this->twig->render('Product/index.html.twig', ['products' => $products]);
    }

    public function indexUniverse(string $universe, int $page)
    {
        $productManager = new ProductManager();
        $countProducts = $productManager->countProducts($universe);
        $countPages = (int)($countProducts/12+1);
        $products = $productManager->selectUniverse($universe, $page, self::PRODUCTS_BY_PAGES);
        return $this->twig->render('Product/index.html.twig', ['products' => $products,
                                                                        'page' => $page,
                                                                        'countPages' => $countPages,
                                                                        'countProducts' => $countProducts
                                                                    ]);
    }
}
