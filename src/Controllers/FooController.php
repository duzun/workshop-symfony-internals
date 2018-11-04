<?php 
namespace App\Controllers;

use Nyholm\Psr7\Response;
use Psr\Http\Message\RequestInterface;

class FooController {

	public function index(RequestInterface $request) {
    	$response = new Response(200, [], 'Foo page');
	    return $response;
	}

}
