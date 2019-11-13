<?php

namespace App\Controller;

use App\Model\BrandManager;
use App\Model\CategoryManager;
use App\Model\ProductManager;

class ProductController extends AbstractController
{

    const PRODUCTS_BY_PAGES = 12;

    public function indexUniverse(string $universe, string $page = '1')
    {
        if (isset($_GET['brand'])) {
            $filterPage['brand'] = $_GET['brand'];
        }
        if (isset($_GET['category'])) {
            $filterPage['category'] = $_GET['category'];
        }
        $filterPage['universe'] = (strpos($universe, '?'))
            ? substr($universe, 0, strpos($universe, '?')) : $universe;
        $pageNumber = (strpos($page, '?')) ? substr($page, 0, strpos($page, '?')) : $page;
        $pageNumber = (int)$pageNumber;
        $productManager = new ProductManager();
        $brandManager = new BrandManager();
        $categoryManager = new CategoryManager();
        $countProducts = $productManager->countProducts($filterPage);
        $countPages = (int)($countProducts/self::PRODUCTS_BY_PAGES+1);
        $brands = $brandManager->selectFromUniverse($filterPage['universe']);
        $categories = $categoryManager->selectFromUniverse($filterPage['universe']);
        $products = $productManager->selectUniverse($filterPage, $pageNumber, self::PRODUCTS_BY_PAGES);

        return $this->twig->render('Product/index.html.twig', ['products' => $products,
                                                                        'page' => $pageNumber,
                                                                        'countPages' => $countPages,
                                                                        'countProducts' => $countProducts,
                                                                        'brands' => $brands,
                                                                        'categories' => $categories,
                                                                        'actualFilter' => $filterPage,
                                                                    ]);
    }
}
