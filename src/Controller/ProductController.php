<?php

namespace App\Controller;

use App\Model\BrandManager;
use App\Model\CategoryManager;
use App\Model\ProductManager;

class ProductController extends AbstractController
{

    const PRODUCTS_BY_PAGES = 12;

    public function indexUniverse(string $universe, string $brand = '%', string $category = '%', int $page = 1)
    {
        $filterPage = ['universe' =>$universe,
                        'brand' => $brand,
                        'category' => $category,
                        ];
        $productManager = new ProductManager();
        $brandManager = new BrandManager();
        $categoryManager = new CategoryManager();
        $countProducts = $productManager->countProducts($filterPage);
        $countPages = (int)($countProducts/self::PRODUCTS_BY_PAGES+1);
        $brands = $brandManager->selectFromUniverse($universe);
        $categories = $categoryManager->selectFromUniverse($universe);
        $products = $productManager->selectUniverse($filterPage, $page, self::PRODUCTS_BY_PAGES);
        return $this->twig->render('Product/index.html.twig', ['products' => $products,
                                                                        'page' => $page,
                                                                        'countPages' => $countPages,
                                                                        'countProducts' => $countProducts,
                                                                        'brands' => $brands,
                                                                        'categories' => $categories,
                                                                        'actualFilter' => $filterPage,
                                                                    ]);
    }
}
