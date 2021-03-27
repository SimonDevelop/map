<?php

namespace App\Middlewares;

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ConnectedMiddleware
{
    /**
     * @var object $container
     */
    private $container;

    /**
     * Constructeur du middleware
     *
     * @param object $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param RequestHandler $handler
     */
    public function __invoke(Request $request, RequestHandler $handler)
    {
        if (!isset($_SESSION["auth"]) || empty($_SESSION["auth"])) {
            return (new Response())->withHeader('Location', $this->container->get("router")->urlFor("login"))
                ->withStatus(301);
        } else {
            $userSession = $_SESSION["auth"];
            $user = $this->container->get("em")->getRepository("App\Entity\User")->getUserById($userSession['id_user']);
            if (!is_null($user) && is_array($user) && count($user) === 1) {
                if (!$user[0]->getActive()) {
                    unset($_SESSION["auth"]);
                    unset($_SESSION["csrf"]);
                    unset($_SESSION["token"]);
                    setcookie('remember', null, -1);
                    $_SESSION["alert"] = [
                        "warning" => ["Le compte ".$userSession["email"]." est désactivé"]
                    ];
                    return (new Response())->withHeader('Location', $this->container->get("router")->urlFor("login"))
                        ->withStatus(301);
                } else {
                    $auth = [
                        "id_user" => $user[0]->getId(),
                        "email" => $user[0]->getEmail(),
                        "secret" => $user[0]->getSecret(),
                        "admin" => $user[0]->getAdmin(),
                        "date_create" => $user[0]->getDateCreate(),
                        "date_last" => $userSession["date_last"],
                        "last_ip" => $userSession["last_ip"]
                    ];
                    $_SESSION["auth"] = $auth;
                    $this->container->get("view")->getEnvironment()->addGlobal(
                        'auth',
                        $_SESSION["auth"]
                    );
                }
            } else {
                unset($_SESSION["auth"]);
                unset($_SESSION["csrf"]);
                unset($_SESSION["token"]);
                setcookie('remember', null, -1);
                $_SESSION["alert"] = [
                    "danger" => ["Le compte ".$userSession["email"]." n'existe pas/plus"]
                ];
                return (new Response())->withHeader('Location', $this->container->get("router")->urlFor("login"))
                    ->withStatus(301);
            }
        }
        return $handler->handle($request);
    }
}
