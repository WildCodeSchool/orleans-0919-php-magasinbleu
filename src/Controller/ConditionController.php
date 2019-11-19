<?php


namespace App\Controller;

use App\Model\ProductManager;

class ConditionController extends AbstractController
{
    public function condition()
    {
        return $this->twig->render('Condition/condition.html.twig');
    }
}
