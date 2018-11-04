<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Response;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Cache\Adapter\Filesystem\FilesystemCachePool;

class Cache implements MiddlewareInterface
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $uri = $request->getUri()->getPath();

       	$filesystemAdapter = new Local(ROOT_DIR.'/cache/');
		$filesystem        = new Filesystem($filesystemAdapter);

		$pool = new FilesystemCachePool($filesystem);

		$item = $pool->getItem(sha1($uri));

		if ( $item->isHit() ) {
			list($status, $headers, $body) = $item->get();
			$response = new Response($status, $headers, $body);
		}
		else {
			$response = $next($request, $response);
			$item
				->set([
					$response->getStatusCode(),
					$response->getHeaders(),
					$response->getBody() . '',
				])
				->expiresAfter(60)
			;


			$pool->save($item);
		}

        return $response;
    }
}
