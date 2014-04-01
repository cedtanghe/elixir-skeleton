<?php

require_once __DIR__ . '/init.php';

$container = new \Elixir\DI\Container();
$container->set('autoloader', $loader);

$application = new \Elixir\MVC\Application($container);
$application->setControllerResolver(new \Elixir\MVC\Controller\ControllerResolver());

// Register modules
$application->addModule(new \Elixir\Module\Application\Bootstrap());

if(APPLICATION_ENV == 'development')
{
   $application->addModule(new \Elixir\Module\Console\Bootstrap()); 
}

$application->addModule(new \Elixir\Module\Facade\Bootstrap());
$application->addModule(new \AppExtend\Bootstrap());

// Boot all modules
$application->boot();