<?php
namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;

class Controller
{
    /**
     * @var object $container
     */
    protected $container;

    /**
     * Constructeur du controller parent
     *
     * @param object $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Constructeur du controller parent
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->container->get($name);
    }

    /**
     * Ajout de messages d'alerte en session pour la prochaine redirection
     *
     * @param array $messages
     * @param string $type
     */
    public function alert($message, $type = "success")
    {
        if (!isset($_SESSION['alert2'])) {
            $_SESSION['alert2'] = [];
        }
        $_SESSION['alert2'][$type] = $message;
    }

    /**
     * Permet de vérifier le token en session
     *
     * @param string $token
     * @return bool
     */
    public function tokenCheck($token)
    {
        if (!isset($_SESSION['token']) || empty($_SESSION['token'])) {
            return false;
        } elseif ($_SESSION['token'] === $token) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Génère un token aléatoire
     *
     * @param int $length
     * @return string
     */
    public function generateToken(int $length = 250)
    {
        $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
        return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
    }

    /**
     * Envoie la vue d'une page php
     *
     * @param ResponseInterface $response
     * @param string $file
     * @param array $params
     * @return ResponseInterface
     */
    public function render(ResponseInterface $response, $file, $params = [])
    {
        return $this->container->get("view")->render($response, $file, $params);
    }

    /**
     * Effectue une redirection http
     *
     * @param ResponseInterface $response
     * @param string $name
     * @param int $status
     * @param array $params
     * @return ResponseInterface
     */
    public function redirect(ResponseInterface $response, $name, $status = 302, $params = [])
    {
        if (empty($params)) {
            return $response->withHeader('Location', $this->container->get("router")->urlFor($name))
                ->withStatus($status);
        } else {
            return $response->withHeader('Location', $this->container->get("router")->urlFor($name, $params))
                ->withStatus($status);
        }
    }
}
