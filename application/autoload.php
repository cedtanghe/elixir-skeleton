<?php

// Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Standard autoloader
$loader = new \Elixir\ClassLoader\Loader();
$loader->register();

$loader->addNamespace('App', __DIR__ . '/modules/App/');

