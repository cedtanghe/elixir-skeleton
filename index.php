<?php

require_once __DIR__ . '/application/app.php';

$request = \Elixir\HTTP\RequestFactory::create();

// Launch application
$response = $application->handle($request);
$response->send();

$application->terminate($request, $response);
