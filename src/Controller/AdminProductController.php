<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\BrandManager;
use App\Model\CategoryManager;
use App\Model\ProductManager;
use App\Model\UniverseManager;

/**
 * Class ItemController
 *
 */
class AdminProductController extends AbstractController
{


    public function add(): string
    {
        $productManager = new ProductManager();

        $brandManager = new BrandManager();
        $brand = $brandManager->selectAll();

        $brandManager = new CategoryManager();
        $category = $brandManager->selectAll();

        $universeManager = new UniverseManager();
        $universe = $universeManager->selectAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);
            $errors = $this->validate($data);
            if (empty($errors)) {
                // insert en bdd si pas d'erreur
                $productManager->insert($data);
                // redirection en GET
                header('Location: /adminProduct/list');
            }
        }
        return $this->twig->render('AdminProduct/add.html.twig',[
            'data'  => $data ?? [],
            'errors' => $errors ?? [],
            'brand' => $brand,
            'category' => $category,
            'universe'=> $universe,
        ]);
    }
}

