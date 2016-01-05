<?php

namespace App\DI;

use Elixir\DI\ContainerInterface;
use Elixir\DI\ContainerInterface;
use Elixir\Helper\Security;
use Elixir\Module\AppBase\DI\ServicesHelper as ParentServicesHelper;
use Elixir\Module\AppBase\DI\ServicesHelper as ParentServicesHelper;
use Elixir\MVC\Controller\Helper\Container as ControllerHelper;
use Elixir\View\Helper\Container as ViewHelper;

class ServicesHelper extends ParentServicesHelper
{
    public function load(ContainerInterface $pContainer) 
    {
        parent::load($pContainer);
        
        /************ SECURITY ************/
        
        $pContainer->singleton('helper.security', function($pContainer)
        {
            $security = new Security();
            $security->setRequest($pContainer->get('request'));
            $security->setManager($pContainer->get('identities'));
            $security->setFirewall($pContainer->get('security'));

            return $security;
        },
        [
            ViewHelper::HELPER_TAG_KEY, 
            ControllerHelper::HELPER_TAG_KEY
        ]);
    }
}
