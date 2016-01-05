<?php

require_once __DIR__ . '/application/app.php';

use Elixir\HTTP\RequestFactory;

$request = RequestFactory::create();

// Launch application
$response = $application->handle($request);
$response->send();

$application->terminate($request, $response);
