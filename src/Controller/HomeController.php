<?php

namespace App\Controller;

use App\Model\HomeManager;

class HomeController extends AbstractController
{
    public function index()
    {
        $universeManager = new HomeManager();
        $universes = $universeManager->selectAll();
        return $this->twig->render('Home/index.html.twig', ['universes' => $universes]);
    }
}
