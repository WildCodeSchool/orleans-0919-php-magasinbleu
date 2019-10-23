<?php

namespace App\Controller;

use App\Model\UniverseManager;

class UniverseController extends AbstractController
{
    public function index()
    {
        $universeManager = new UniverseManager();
        $universes = $universeManager->selectAll();
        return $this->twig->render('Home/index.html.twig', ['universes' => $universes]);
    }
}
