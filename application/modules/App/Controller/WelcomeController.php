<?php

namespace App\Controller;

use Elixir\MVC\Controller\ControllerAbstract;

class WelcomeController extends ControllerAbstract
{
    public function indexAction()
    {
        return $this->helper('helper.render')->renderResponse(null, []);
    }
}
