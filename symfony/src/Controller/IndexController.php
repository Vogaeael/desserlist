<?php

namespace App\Controller;

class IndexController extends BaseController
{
    public function index()
    {
        $user = $this->getUser();

        return $this->render(
            'base/index.html.twig',
            [
                'controller_name' => 'BaseController',
                'user'            => $user,
                'messages'        => $this->popMessagesFromSession(
                ),
            ]
        );
    }
}
