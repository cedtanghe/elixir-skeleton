<?php

require_once __DIR__ . '/init.php';

use App\Bootstrap;
use Elixir\DI\Container;
use Elixir\Module\AppBase\Bootstrap as Bootstrap2;
use Elixir\Module\Console\Bootstrap as Bootstrap3;
use Elixir\MVC\Application;
use Elixir\MVC\Controller\ControllerResolver;

// Check maintenance mode
if (defined('MAINTENANCE_MODE') && true === MAINTENANCE_MODE)
{
    include 'maintenance.php';
}

// Create application
$container = new Container();
$container->set('autoloader', $loader);

$application = new Application($container);
$application->setControllerResolver(new ControllerResolver());

// Register modules
$application->addModule(new Bootstrap2());

if(APPLICATION_ENV == 'development')
{
    if(class_exists('\Elixir\Module\Console\Bootstrap'))
    {
        $application->addModule(new Bootstrap3()); 
    }
}

$application->addModule(new Bootstrap());

// Boot all modules
$application->boot();
