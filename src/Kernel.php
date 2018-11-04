<?php 
namespace App;

use Nyholm\Psr7\Response;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


class Kernel {

	public function handle(RequestInterface $request): ResponseInterface {
	    
		$containerBuilder = new ContainerBuilder();
		$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../config/'));
		$server = $request->getServerParams();
		$loader->load('services.'.(getenv('PHP_ENV') ?: getenv('ENV')).'.yaml');

	    $response = new Response();

		// $middlewares[] = new \App\Middleware\Cache();
		// $middlewares[] = new \App\Middleware\Router();

		$runner = (new \Relay\RelayBuilder($loader->getResolver()))->newInstance(/*$middlewares*/);
		$response = $runner($request, $response);

		// Send response
		$response = (new Psr17Factory())->createReponse('200', 'Hello world');
		(new \Zend\HttpHandlerRunner\Emitter\SapiEmitter())->emit($response);

		return $response;
	}
}
?>