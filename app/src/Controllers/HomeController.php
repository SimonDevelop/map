<?php
namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HomeController extends Controller
{
    public function getHome(RequestInterface $request, ResponseInterface $response)
    {
        $user = 1;
        $key = $_ENV["API_KEY_MAP"];
        $params = compact("key", "user");
        return $this->render($response, 'pages/home.twig', $params);
    }

    public function getAjaxAddMarker(RequestInterface $request, ResponseInterface $response)
    {
        return $this->redirect($response, 'home');
    }
}
