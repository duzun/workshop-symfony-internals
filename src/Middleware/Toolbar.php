<?php

namespace App\Middleware;

use App\Tools\CacheDataCollector;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Stream;

class Toolbar implements MiddlewareInterface
{

    /**
     * @var CacheDataCollector
     */
    private $cache;

    public function __construct(CacheDataCollector $cache)
    {
        $this->cache = $cache;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        // Continue the chain of middlewares and get a response
        $response = $next($request, $response);

        $cache_calls = $this->cache->getCalls();
        $body = $response->getBody()->__toString();
        if ( strpos($body, '</body>') ) {
        	$body = str_replace('</body>', var_export($cache_calls, true) . '</body>', $body);
        }
    	else {
    		$body .= "<br />\n" . var_export($cache_calls, true);
    	}
        
        return $response->withBody(Stream::create($body));
    }
}
