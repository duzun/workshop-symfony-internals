<?php 
namespace App\Controllers;

use Nyholm\Psr7\Response;
use Psr\Http\Message\RequestInterface;

class NotFoundController {

	public function index(RequestInterface $request) {
    	$response = new Response(404, [], '404 Not found');
	    return $response;
	}

}
