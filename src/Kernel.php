<?php 
namespace App;

use Nyholm\Psr7\Response;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


class Kernel {

	public $env;
	public $debug;

	public function __construct($env='prod', $debug=false) {
	    $this->env = $env;
	    $this->debug = $debug;
	}

	public function handle(RequestInterface $request): ResponseInterface {
	    $container = $this->getContainer();

		try {
			$middlewares[] = $container->get('cache');
		} catch(\Throwable $ex) {}
		$middlewares[] = $container->get('router');

		$runner = (new \Relay\RelayBuilder())->newInstance($middlewares);
		$response = $runner($request, new Response());

		return $response;
	}

	public function getContainer() {
		$container = new ContainerBuilder();
 		$container->setParameter('kernel.project_dir', $this->getProjectDir());
        $container->setParameter('kernel.environment', $this->env);		
		$loader = new YamlFileLoader($container, new FileLocator($this->getProjectDir() . '/config/'));

		try {
			$loader->load('services.yaml');
			$loader->load('service_'.$this->env.'.yaml');
		} catch(\Throwable $ex){}

		$container->compile();

		return $container;
	}

	private function getProjectDir() {
        return dirname(__DIR__);
    }
}
?>