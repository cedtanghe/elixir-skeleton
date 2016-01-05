<?php

namespace App\DI;

use Elixir\DB\DBFactory;
use Elixir\DI\ContainerInterface;
use Elixir\HTTP\Session\Session;
use Elixir\I18N\I18N;
use Elixir\I18N\Locale;
use Elixir\Module\AppBase\DI\Services as ParentServices;
use Elixir\Security\Authentification\Manager;
use Elixir\Security\Authentification\Storage\Session as SessionStorage;
use Elixir\Security\Firewall\Behavior\AccessForbidden;
use Elixir\Security\Firewall\Behavior\IdentityNotFound;
use Elixir\Security\Firewall\FirewallEvent;
use Elixir\Security\Firewall\Identity\Firewall as IdentityFirewall;
use Elixir\Security\Firewall\RBAC\Firewall as RBACFirewall;

class Services extends ParentServices
{
    public function load(ContainerInterface $container) 
    {
        parent::load($container);
        
        /************ CONGIGURATION ************/
        
        $container->extend('config', function($config, $container)
        {
            $config->load([
                __DIR__ . '/../resources/configs/config.php',
                __DIR__ . '/../resources/configs/private.php'
            ],
            ['recursive' => true]);
            
            return $config;
        });
        
        $config = $container->get('config');
        
        /************ SESSION ************/
        
        if ($config->get(['enabled', 'session'], false))
        {
            $session = Session::instance();
            
            if (null === $session)
            {
                $session = new Session();

                $config = $container->get('config');
                $sessionName = $config->get(['session', 'name']);

                if (!empty($sessionName))
                {
                    $session->setName($sessionName);
                }

                $session->start();
            }

            $container->singleton('session', function() use($session)
            {
                return $session;
            });
        }
        
        /************ CONNECTIONS ************/
        
        if ($config->get(['enabled', 'db'], false))
        {
            $container->singleton('db.default', function($container)
            {
                $config = $container->get('config');
                return DBFactory::create($config['db']);
            });
        }
        
        /************ ROUTER ************/
        
        $container->extend('router', function($router, $container)
        {
            $router->load(__DIR__ . '/../resources/routes/routes.php');
            return $router;
        });
        
        /************ IDENTITIES ************/
        
        if ($config->get(['enabled', 'security'], false))
        {
            $container->singleton('identities', function()
            {
                return new Manager(new SessionStorage(Session::instance()));
            });
        }
        
        /************ SECURITY ************/
        
        if ($config->get(['enabled', 'security'], false))
        {
            $container->singleton('security', function($container)
            {
                $config = $container->get('config');
                
                if ($config->get(['security', 'type']) === 'rbac')
                {
                    $firewall = new RBACFirewall($container->get('identities'));
                }
                else
                {
                    $firewall = new IdentityFirewall($container->get('identities'));
                }
                
                $firewall->load(__DIR__ . '/../resources/security/security.php');

                $firewall->addListener(FirewallEvent::IDENTITY_NOT_FOUND, function(FirewallEvent $e) use ($container)
                {
                    $options = $e->getAccessControl()->getOptions();

                    if (isset($options['identity_not_found_uri']))
                    {
                        $behavior = new IdentityNotFound($options['identity_not_found_uri']());
                    }
                    else
                    {
                        $behavior = new AccessForbidden();
                    }

                    $behavior($e->getTarget());
                });

                $firewall->addListener(FirewallEvent::ACCESS_FORBIDDEN, function(FirewallEvent $e) use($container)
                {
                    $behavior = new AccessForbidden();
                    $behavior($e->getTarget());
                });

                return $firewall;
            });
        }
        
        /************ INTERNATIONALISATION ************/
        
        if ($config->get(['enabled', 'i18n'], false))
        {
            $container->singleton('i18n', function($container)
            {
                $I18N = new I18N();
                
                $request = $container->get('request');
                $config = $container->get('config');
                $default = $config->get(['i18n', 'default']);
                
                $pathInfo = trim($request->getPathInfo(), '/');
                $locale = null;
                
                if ($request->isAjax())
                {
                    $locale = $request->get('_locale');
                }
                else
                {
                    $locale = substr($pathInfo, 0, 2);   
                }
                
                $found = false;

                if (in_array($locale, $config->get(['i18n', 'languages'])))
                {
                    if (strlen($pathInfo) == 2 || substr($pathInfo, 2, 1) === '/')
                    {
                        $found = true;
                    }
                    
                    if (!$found)
                    {
                        $locale = $default;
                    }
                }
                else
                {
                    $locale = $default;
                }
                
                Locale::setDefault($locale);
                
                if ($locale !== $default)
                {
                    $I18N->load(__DIR__ . '/../resources/languages/' . $locale . '.mo');
                }
                
                return $I18N;
            });
        }
    }
}
