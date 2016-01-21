<?php

require_once __DIR__ . '/application/app.php';

use Elixir\HTTP\RequestFactory;

// Create request
$request = $container->get('request', null, RequestFactory::create());

// Launch application
$response = $application->handle($request);
$response->send();

$application->terminate($request, $response);
