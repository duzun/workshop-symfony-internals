<?php
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use Nyholm\Psr7Server\ServerRequestCreator;

require_once __DIR__ . '/vendor/autoload.php';


$psr17Factory = new Psr17Factory();

$creator = new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);

$request = $creator->fromGlobals();

if ( isset($request->getQueryParams()['page']) && $request->getQueryParams()['page'] === 'foo') {
	$response = new Response('200', [], 'Foo page <br>');
} else {
	$response = new Response('200', [], "Welcome to index! <br>");
}

if ( $request->getServerParams()['REMOTE_ADDR'] === '127.0.0.1') {
	$response = new Response('200', [], $response->getBody() . "(admin stuff)");
}

(new SapiEmitter())->emit($response);