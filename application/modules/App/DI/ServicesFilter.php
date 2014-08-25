<?php

namespace App\DI;

use Elixir\DI\ContainerInterface;
use Elixir\Module\AppBase\DI\ServicesFilter as ParentServicesFilter;

class ServicesFilter extends ParentServicesFilter
{
    public function load(ContainerInterface $pContainer) 
    {
        parent::load($pContainer);
    }
}
