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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);
            $errors = $this->validate($data);

            if (empty($errors)) {
                // TODO : envoi du mail
                header('location: /Home/index/?success=ok');
            }
        }

        return $this->twig->render('Home/index.html.twig', [
            'errors' => $errors ?? [],
            'contact' => $data ?? [],
            'universes' => $universes,
            'products' => $products,
            ]);
    }

    private function validate(array $data) :array
    {
        if (strlen($data['name']) > 50) {
            $errors['name'][] = 'Le nom est trop long';
        }
        if (empty($data['name'])) {
            $errors['name'][] = "Veuillez indiquer votre nom";
        }

        if (empty($data['email'])) {
            $errors['email'][] = "Veuillez indiquer une adresse mail";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'][] = "Format invalide";
        }

        if (empty($data['subject'])) {
            $errors['subject'][] = "Veuillez indiquer l'objet de votre message";
        }

        if (empty($data['message'])) {
            $errors['message'][] = "Veuillez indiquer votre message";
        }

        return $errors ?? [];
    }
}
