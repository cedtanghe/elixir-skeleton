<?php

namespace App\DI;

use Elixir\DI\ContainerInterface;
use Elixir\Module\AppBase\DI\ServicesValidator as ParentServicesValidator;

class ServicesValidator extends ParentServicesValidator
{
    public function load(ContainerInterface $container) 
    {
        parent::load($container);
    }
}
