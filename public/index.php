<?php

use App\Application;
use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/../vendor/autoload.php';

$app = new Application();

$request = Request::createFromGlobals();

$response = $app->handleRequest($request);

$response->send();
