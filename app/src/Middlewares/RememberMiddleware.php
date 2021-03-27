<?php
namespace App\Middlewares;

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class RememberMiddleware
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
            // Si cookie présent, alors vérifier
            if (isset($_COOKIE['remember']) && !empty($_COOKIE['remember'])) {
                $remember_token = $_COOKIE['remember'];
                $parts = explode('==', $remember_token);
                $user_id = $parts[0];

                $user = $this->container->get("em")->getRepository('App\Entity\User')->getUserById($user_id);

                // Si utilisateur trouvé, vérifier le token
                if (null != $user && !empty($user)) {
                    if ($user[0]->isActive() === true) {
                        $expected = $user_id.'=='.$user[0]->getRememberToken().sha1($user_id."clients");

                        // Si token valide, initialiser la session
                        if ($expected === $remember_token) {
                            $auth = [
                                "id_user" => $user[0]->getId(),
                                "email" => $user[0]->getEmail(),
                                "admin" => $user[0]->getAdmin(),
                                "date_create" => $user[0]->getDateCreate()
                            ];
                            $_SESSION["auth"] = $auth;
                            setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 1);

                            $user[0]->setDateLast((new \DateTime()));
                            $user[0]->setLastIp($request->getServerParams()["REMOTE_ADDR"]);
                            $this->container->get("em")->persist($user[0]);
                            $this->container->get("em")->flush();
                        } else {
                            // Si cookie non valide, rediriger vers login après avoir supprimer ce dernier
                            setcookie('remember', null, -1);
                        }
                    } else {
                        // Si utilisateur désactivé, rediriger vers login après avoir supprimer ce dernier
                        setcookie('remember', null, -1);
                    }
                } else {
                    // Si cookie mais utilisateur inexistant, expiration du cookie et rediriger vers login
                    setcookie('remember', null, -1);
                }
            }
        }
        return $next($request, $response);
    }
}
