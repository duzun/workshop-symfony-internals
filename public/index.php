<?php

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Nyholm\Psr7Server\ServerRequestCreator;

require __DIR__.'/../vendor/autoload.php';

$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
$request = $creator->fromGlobals();

$response = (new \App\Kernel(getenv('PHP_ENV') ?: getenv('ENV'), true))->handle($request);

// Send response
(new \Zend\HttpHandlerRunner\Emitter\SapiEmitter())->emit($response);
