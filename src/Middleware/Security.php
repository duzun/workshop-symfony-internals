<?php

namespace App\Middleware;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Security implements MiddlewareInterface
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $uri = $request->getUri();

        if ( $uri->getPath() != '/admin' ) {
            return $next($request, $response);
        }

        $auth = $request->getServerParams()['PHP_AUTH_USER'] ?? '';
        $pass = $request->getServerParams()['PHP_AUTH_PW'] ?? '';

        if ( !$auth ) {
            return new Response(401, ['WWW-Authenticate'=>'Basic realm="Admin area"'], 'This page is protected');
        }

        if ( $auth != 'alice' ) {
            return new Response(403, [], 'This page is forbidden for "' . $auth . '"!');
        }
        
        return $next($request, $response);
    }
}
