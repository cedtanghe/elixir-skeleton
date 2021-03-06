<?php

define('APPLICATION_PATH', realpath(__DIR__));
define('PUBLIC_PATH', realpath(__DIR__ . '/../'));
define('APPLICATION_ENV', 'development');
define('MAINTENANCE_MODE', false);
define('MAINTENANCE_IP_AUTHORIZATION', '');

set_error_handler(function($severity, $message, $filename, $line) 
{
    throw new \ErrorException($message, 0, $severity, $filename, $line);
});

switch (APPLICATION_ENV) 
{
    case 'development':
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        break;
    default:
        ini_set('display_errors', '0');
        error_reporting(0);
        break;
}

require_once __DIR__ . '/autoload.php';
