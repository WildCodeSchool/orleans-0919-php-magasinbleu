<?php


namespace App\Controller;




use App\Model\ProductManager;

class ConditionsController extends AbstractController
{
    public function conditions()
    {
        return $this->twig->render('Conditions/conditions.html.twig');
    }
}
