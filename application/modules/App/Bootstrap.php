<?php

namespace App;

use Elixir\MVC\Module\ModuleAbstract;

class Bootstrap extends ModuleAbstract
{
    public function getParent() 
    {
        return 'AppBase';
    }
    
    public function boot() 
    {
        // Not yet
    }
}
