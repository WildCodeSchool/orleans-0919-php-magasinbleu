<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\CategoryManager;

class AdminCategoryController extends AbstractController
{
    public function edit(int $id): string
    {
        $errors = [];
        $categoryManager = new CategoryManager();
        $category = $categoryManager->selectOneById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);
            $errors = $this->validate($data);
            if (empty($errors)) {
                // update en bdd si pas d'erreur
                $categoryManager->update($data);
                // redirection en GET
                header('Location: /AdminCategory/edit/' . $id);
            }
        }
        return $this->twig->render('AdminCategory/edit.html.twig', [
            'category'  => $category,
            'data'  => $data ?? [],
            'errors' => $errors,
        ]);
    }

    private function validate(array $data) :array
    {
        // verif coté serveur
        if (empty($data['name'])) {
            $errors['name'] = 'Un nom de catégorie est requise';
        } elseif (strlen($data['name']) > 150) {
            $errors['name'] = 'Le nom de la catégorie est trop long';
        }
        return $errors ?? [];
    }


    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryManager = new CategoryManager();
            $categoryManager->delete($id);

            header('Location:/adminCategory/index');
        }
    }

    public function index()
    {
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();

        return $this->twig->render('AdminCategory/index.html.twig', ['categories' => $categories]);
    }

    public function add(): string
    {
        $errors = [];

        $categoryManager = new CategoryManager();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);
            $errors = $this->validate($data);
            if (empty($errors)) {
                // insert en bdd si pas d'erreur
                $categoryManager->insert($data);
                // redirection en GET
                header('Location: /adminCategory/index');
            }
        }
        return $this->twig->render('AdminCategory/add.html.twig', [
            'data'  => $data ?? [],
            'errors' => $errors,
        ]);
    }
}
