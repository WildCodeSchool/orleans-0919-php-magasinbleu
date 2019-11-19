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

class AdminBrandController extends AbstractController
{
    public function edit(int $id): string
    {
        $errors = [];
        $brandManager = new BrandManager();
        $brand = $brandManager->selectOneById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);
            $errors = $this->validate($data);
            if (empty($errors)) {
                // update en bdd si pas d'erreur
                $brandManager->update($data);
                // redirection en GET
                header('Location: /AdminBrand/index');
            }
        }
        return $this->twig->render('AdminBrand/edit.html.twig', [
            'brand'  => $brand,
            'data'  => $data ?? [],
            'errors' => $errors,
        ]);
    }

    private function validate(array $data) :array
    {
        if (empty($data['name'])) {
            $errors['name'] = 'Un nom de marque est requise';
        } elseif (strlen($data['name']) > 150) {
            $errors['name'] = 'Le nom de la marque est trop long';
        }
        return $errors ?? [];
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $brandManager = new BrandManager();
            $brandManager->delete($id);

            header('Location:/adminBrand/index');
        }
    }

    public function index()
    {
        $brandManager = new BrandManager();
        $brands = $brandManager->selectAll();

        return $this->twig->render('AdminBrand/index.html.twig', ['brands' => $brands]);
    }

    public function add(): string
    {
        $errors = [];

        $brandManager = new BrandManager();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);
            $errors = $this->validate($data);
            if (empty($errors)) {
                // insert en bdd si pas d'erreur
                $brandManager->insert($data);
                // redirection en GET
                header('Location: /adminBrand/index');
            }
        }
        return $this->twig->render('AdminBrand/add.html.twig', [
            'data'  => $data ?? [],
            'errors' => $errors,
        ]);
    }
}
