<?php 
namespace App\Controllers;

use Nyholm\Psr7\Response;
use Psr\Http\Message\RequestInterface;

class HomeController {

	public function index(RequestInterface $request) {
	    $response = new Response(200, [], 'Welcome to index!');
	    return $response;
	}

}
