<?php

require_once __DIR__ . '/init.php';

// Check maintenance mode
if(defined('MAINTENANCE_MODE') && true === MAINTENANCE_MODE)
{
    include 'maintenance.php';
}

// Create application
$container = new \Elixir\DI\Container();
$container->set('autoloader', $loader);

$application = new \Elixir\MVC\Application($container);
$application->setControllerResolver(new \Elixir\MVC\Controller\ControllerResolver());

// Register modules
$application->addModule(new \Elixir\Module\AppBase\Bootstrap());

if(APPLICATION_ENV == 'development')
{
    if(class_exists('\Elixir\Module\Console\Bootstrap'))
    {
        $application->addModule(new \Elixir\Module\Console\Bootstrap()); 
    }
}

$application->addModule(new \App\Bootstrap());

// Boot all modules
$application->boot();
