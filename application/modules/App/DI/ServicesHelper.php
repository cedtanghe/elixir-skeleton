<?php

namespace App\DI;

use Elixir\DI\ContainerInterface;
use Elixir\Module\AppBase\DI\ServicesHelper as ParentServicesHelper;

class ServicesHelper extends ParentServicesHelper
{
    public function load(ContainerInterface $pContainer) 
    {
        parent::load($pContainer);
    }
}