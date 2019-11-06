<?php

namespace App\Controller;

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
        $countProducts = $productManager->countProducts($filterPage);
        $countPages = (int)($countProducts/12+1);
        $brands = $productManager->selectBrandFromUniverse($universe);
        $categories = $productManager->selectCategoryFromUniverse($universe);
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
