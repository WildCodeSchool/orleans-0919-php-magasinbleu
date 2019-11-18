<?php

namespace App\Controller;

use App\Model\AbstractManager;

class ContactController extends AbstractController
{

    public function contact()
    {
        $name = $email = $subject = $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);
            $errors = [];

            if (strlen($data['name']) > 50) {
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

            if (empty($data['subject'])) {
                $errors['subject'][] = "Veuillez indiquer l'objet de votre message";
            } else {
                $subject = $data['subject'];
            }

            if (empty($data['message'])) {
                $errors['message'][] = "Veuillez indiquer votre message";
            } else {
                $message = $data['message'];
            }

            if (empty($errors)) {
                $name = $data['name'];
                $email = $data['email'];
                $subject = $data['subject'];
                $message = $data['message'];
                header('location: /Contact/contact/?success=ok');
            }
        }
        {
            return $this->twig->render('Contact/contact.html.twig', [
                'errors' => $errors ?? [],
                'success' => $_GET['success'] ?? null
            ]);
        }
    }
}
