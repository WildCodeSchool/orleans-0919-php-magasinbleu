<?php
namespace App\Controller;

use App\Model\ProductManager;
use App\Model\UniverseManager;
use App\Model\BrandManager;
use App\Model\CategoryManager;

class AdminProductController extends AbstractController
{
    public function index()
    {
        $productManager = new ProductManager();
        $products = $productManager->selectAll();

        return $this->twig->render('AdminProduct/index.html.twig', ['products' => $products]);
    }

    public function edit($id): string
    {
        $errors = [];
        $productManager = new ProductManager();
        $product = $productManager->selectOneById($id);
        $brandManager = new BrandManager();
        $brands = $brandManager->selectAll();
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();
        $universeManager = new UniverseManager();
        $universes = $universeManager->selectAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);
            $errors = $this->validate($data);
            if (empty($errors)) {
                // update en bdd si pas d'erreur
                $productManager->update($data);
                // redirection en GET
                header('Location: /adminProduct/edit/' . $id);
            }
        }
        return $this->twig->render('AdminProduct/edit.html.twig', [
            'product' => $product,
            'data' => $data ?? [],
            'errors' => $errors,
            'brands' => $brands,
            'categories' => $categories,
            'universes' => $universes,
        ]);
    }

    public function add(): string
    {
        $errors = [];

        $productManager = new ProductManager();

        $brandManager = new BrandManager();
        $brand = $brandManager->selectAll();

        $categoriesManager = new CategoryManager();
        $categories = $categoriesManager->selectAll();

        $universeManager = new UniverseManager();
        $universe = $universeManager->selectAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);
            $errors = $this->validate($data);
            if (empty($errors)) {
                // insert en bdd si pas d'erreur
                $productManager->insert($data);
                // redirection en GET
                header('Location: /adminProduct/index');
            }
        }
        return $this->twig->render('AdminProduct/add.html.twig', [
             'data'  => $data ?? [],
             'errors' => $errors,
             'brands' => $brand,
             'categories' => $categories,
             'universes' => $universe,
        ]);
    }

    private function validate(array $data) :array
    {
        // verif coté serveur
        if (empty($data['name'])) {
            $errors['name'] = 'Le nom du produit est requis';
        } elseif (strlen($data['name']) > 150) {
            $errors['name'] = 'Le nom du produit est trop long';
        }
        if (empty($data['image'])) {
            $errors['image'] = 'Une image est requise';
        } elseif (strlen($data['image']) > 255) {
            $errors['image'] = 'Le lien  de l\'image est trop long';
        }
        if (empty($data['reference'])) {
            $errors['reference'] = 'Une référence est requise';
        } elseif (strlen($data['reference']) > 45) {
            $errors['reference'] = 'Le nom de référence est trop long';
        }
        if (empty($data['price'])) {
            $errors['price'] = 'Le prix doit être renseigné';
        } elseif ($data['price'] < 0) {
            $errors['price'] = 'Le prix doit être positif';
        }
        return $errors ?? [];
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productManager = new ProductManager();
            $productManager->delete($id);

            header('Location:/adminProduct/index');
        }
    }
}
