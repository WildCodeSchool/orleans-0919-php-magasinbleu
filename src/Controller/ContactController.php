<?php

namespace App\Controller;

use App\Model\AbstractManager;

class ContactController extends AbstractController
{

    public function cleanInput(array $data): array
    {
        foreach ($data as $key => $value) {
            $data[$key] = trim($value);
        }
        return $data;
    }

    public function contact()
    {
        $name = $email = $phone = $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->cleanInput($_POST);
            $errors = [];

            if (strlen($data['name']) > 35) {
                $errors['name'][] = 'Le nom est trop long';
            }
            if (empty($data['name'])) {
                $errors['name'][] = "Veuillez indiquer votre nom";
            } else {
                $name = $data['name'];
            }

            if (empty($data['email'])) {
                $errors['email'][] = "Veuillez indiquer une adresse mail";
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'][] = "Format invalide";
            } else {
                $email = $data['email'];
            }

            if (empty($data['phone'])) {
                $errors['phone'][] = "Veuillez indiquer un numéro de téléphone";
            } else {
                $phone = $data['phone'];
            }

            if (empty($data['message'])) {
                $errors['message'][] = "Veuillez indiquer votre message";
            } else {
                $message = $data['message'];
            }

            if (empty($errors)) {
                $name = htmlentities($data['name']);
                $email = htmlentities($data['email']);
                $phone = htmlentities($data['phone']);
                $message = htmlentities($data['message']);
                header('location: /Contact/contact');
            }
        }
        {
            return $this->twig->render('Contact/contact.html.twig', [
                'errors' => $errors ?? []]);
        }
    }
}
