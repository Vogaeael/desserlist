<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    public function index()
    {
        $user = $this->getUser();

        return $this->render(
            'base/index.html.twig',
            [
                'controller_name' => 'BaseController',
                'user' => $user,
            ]
        );
    }
}
