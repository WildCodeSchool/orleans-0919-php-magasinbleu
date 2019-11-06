<?php

namespace App\Controller;

use App\Model\ProductManager;

class DescribeController extends AbstractController
{
    public function describe()
    {
        return $this->twig->render('Describe/describe.html.twig');
    }
}
