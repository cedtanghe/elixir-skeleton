<?php

namespace App\DI;

use Elixir\DI\ContainerInterface;
use Elixir\Helper\I18N;
use Elixir\Helper\Security;
use Elixir\Module\AppBase\DI\ServicesHelper as ParentServicesHelper;
use Elixir\MVC\Controller\Helper\Container as ControllerHelper;
use Elixir\View\Helper\Container as ViewHelper;

class ServicesHelper extends ParentServicesHelper
{
    public function load(ContainerInterface $container) 
    {
        parent::load($container);
        
        $config = $container->get('config');
        
        /************ INTERNATIONALISATION ************/
        
        if ($config->get(['enabled', 'i18n'], false))
        {
            $container->singleton('helper.i18n', function($container)
            {
                return new I18N($container->get('i18n'));
            }, 
            [
                ViewHelper::HELPER_TAG_KEY, 
                ControllerHelper::HELPER_TAG_KEY
            ]);
        }
        
        /************ SECURITY ************/
        
        if ($config->get(['enabled', 'security'], false))
        {
            $container->singleton('helper.security', function($container)
            {
                $security = new Security();
                $security->setRequest($container->get('request'));
                $security->setManager($container->get('identities'));
                $security->setFirewall($container->get('security'));

                return $security;
            },
            [
                ViewHelper::HELPER_TAG_KEY, 
                ControllerHelper::HELPER_TAG_KEY
            ]);
        }
    }
}
