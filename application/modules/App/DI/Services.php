<?php

namespace App\DI;

use Elixir\DB\DBFactory;
use Elixir\DI\ContainerInterface;
use Elixir\HTTP\Session\Session;
use Elixir\Module\AppBase\DI\Services as ParentServices;
use Elixir\Security\Authentification\Manager;
use Elixir\Security\Authentification\Storage\Session as SessionStorage;
use Elixir\Security\Firewall\Behavior\AccessForbidden;
use Elixir\Security\Firewall\Behavior\IdentityNotFound;
use Elixir\Security\Firewall\FirewallEvent;
use Elixir\Security\Firewall\RBAC\Firewall;

class Services extends ParentServices
{
    public function load(ContainerInterface $pContainer) 
    {
        parent::load($pContainer);
        
        /************ CONGIGURATION ************/
        
        $pContainer->extend('config', function($pConfig, $pContainer)
        {
            $pConfig->load([
                __DIR__ . '/../resources/configs/config.php',
                __DIR__ . '/../resources/configs/private.php'
            ],
            ['recursive' => true]);
            
            return $pConfig;
        });
        
        /************ SESSION ************/
        
        $session = Session::instance();
        
        if(null === $session)
        {
            $session = new Session();
            
            $config = $pContainer->get('config');
            $sessionName = $config->get(['session', 'name']);
            
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
        
        /************ IDENTITIES ************/
        
        $pContainer->singleton('identities', function()
        {
            return new Manager(new SessionStorage(Session::instance()));
        });
        
        /************ SECURITY ************/
        
        $pContainer->singleton('security', function($pContainer)
        {
            $firewall = new Firewall($pContainer->get('identities'));
            $firewall->load(__DIR__ . '/../resources/security/security.php');
            
            $firewall->addListener(FirewallEvent::IDENTITY_NOT_FOUND, function(FirewallEvent $e) use ($pContainer)
            {
                $options = $e->getAccessControl()->getOptions();

                if(isset($options['identity_not_found_uri']))
                {
                    $behavior = new IdentityNotFound($options['identity_not_found_uri']());
                }
                else
                {
                    $behavior = new AccessForbidden();
                }

                $behavior($e->getTarget());
            });

            $firewall->addListener(FirewallEvent::ACCESS_FORBIDDEN, function(FirewallEvent $e) use($pContainer)
            {
                $behavior = new AccessForbidden();
                $behavior($e->getTarget());
            });

            return $firewall;
        });
    }
}
