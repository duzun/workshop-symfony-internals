<?php

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Nyholm\Psr7Server\ServerRequestCreator;

require __DIR__.'/../vendor/autoload.php';

$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
$request = $creator->fromGlobals();

switch($request->getUri()->getPath()) {
	case '/': {
		$controllerClass = App\Controllers\HomeController::class;
	} break;

	case '/foo': {
		$controllerClass = App\Controllers\FooController::class;
	} break;

	default: {
		$controllerClass = App\Controllers\NotFoundController::class;
	    
	}
}

// Send response
echo (new $controllerClass)->index($request)->getBody();
