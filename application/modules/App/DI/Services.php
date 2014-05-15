<?php

namespace App\DI;

use Elixir\DB\DBFactory;
use Elixir\DI\ContainerInterface;
use Elixir\HTTP\Session\Session;
use Elixir\Module\AppBase\DI\Services as ParentServices;

class Services extends ParentServices
{
    public function load(ContainerInterface $pContainer) 
    {
        parent::load($pContainer);
        
        /************ CONGIGURATION ************/
        
        $pContainer->extend('config', function($pConfig, $pContainer)
        {
            $pConfig->load(array(
                __DIR__ . '/../resources/configs/config.php',
                __DIR__ . '/../resources/configs/private.php'
            ),
            array('recursive' => true));
            
            return $pConfig;
        });
        
        /************ SESSION ************/
        
        $session = Session::instance();
        
        if(null === $session)
        {
            $session = new Session();
            
            $config = $pContainer->get('config');
            $sessionName = $config->get(array('session', 'name'));
            
            if(!empty($sessionName))
            {
                $session->setName($sessionName);
            }
            
            $session->start();
        }
        
        $pContainer->singleton('session', function() use($session)
        {
            return $session;
        });
        
        /************ CONNECTIONS ************/
        
        $pContainer->singleton('db.default', function($pContainer)
        {
            $config = $pContainer->get('config');
            return DBFactory::create($config['db']);
        });
        
        /************ ROUTER ************/
        
        $pContainer->extend('router', function($pRouter, $pContainer)
        {
            $pRouter->load(__DIR__ . '/../resources/routes/routes.php');
            return $pRouter;
        });
    }
}