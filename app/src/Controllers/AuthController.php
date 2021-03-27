<?php
namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator;

class AuthController extends Controller
{
    /**
     * Retourne le formulaire de connexion
     *
     * @return ResponseInterface
     */
    public function getLogin(RequestInterface $request, ResponseInterface $response)
    {
        if (isset($_SESSION["auth"]) && !empty($_SESSION["auth"])) {
            return $this->redirect($response, "home");
        } else {
            $title = "Se connecter";
            $params = compact("title");
            return $this->render($response, "auth/login.twig", $params);
        }
    }

    /**
     * Retourne une redirection après le traitement du formulaire de connexion
     *
     * @return ResponseInterface
     */
    public function postLogin(RequestInterface $request, ResponseInterface $response)
    {
        $params = $request->getParsedBody();
        if (isset($_SESSION["auth"]) && !empty($_SESSION["auth"])) {
            return $this->redirect($response, "home");
        } elseif (!is_null($params) && isset($params["email"]) && isset($params["password"])) {
            if ($request->getAttribute("csrf_status") !== false) {
                $errors = [];
                // Email
                if (!Validator::email()->validate($params["email"])) {
                    $errors[] = "Adresse email invalide.";
                }
                // Password
                if (!isset($params["password"]) || strlen($params["password"]) < 1) {
                    $errors[] = "Mot de passe requis.";
                }
                // Login
                if (empty($errors)) {
                    $user = $this->em->getRepository("App\Entity\User")->getUserByEmail($params["email"]);
                    if (is_array($user) && count($user) >= 1) {
                        if (!$user[0]->getActive()) {
                            $errors[] = "Le compte ".$user[0]->getEmail()." est désactivé.";
                            $this->alert($errors, "danger");
                            return $this->redirect($response, "login", 400);
                        } else {
                            if ($user[0]->passwordVerify($params["password"])) {
                                $auth = [
                                    "id_user" => $user[0]->getId(),
                                    "email" => $user[0]->getEmail(),
                                    "secret" => $user[0]->getSecret(),
                                    "admin" => $user[0]->getAdmin(),
                                    "date_create" => $user[0]->getDateCreate(),
                                    "date_last" => (new \DateTime()),
                                    "last_ip" => $request->getServerParams()["REMOTE_ADDR"]
                                ];
                                if (!is_null($user[0]->getDateLast())) {
                                    $auth["date_last"] = $user[0]->getDateLast();
                                }
                                if (!is_null($user[0]->getLastIp())) {
                                    $auth["last_ip"] = $user[0]->getLastIp();
                                }
                                // Remember token
                                $remember_token = $this->generateToken();
                                $user[0]->setRememberToken($remember_token);
                                $user[0]->setDateLast((new \DateTime()));
                                $user[0]->setLastIp($request->getServerParams()["REMOTE_ADDR"]);
                                $this->em->persist($user[0]);
                                $this->em->flush();
                                setcookie("remember", $user[0]->getId()."==".$remember_token.
                                sha1($user[0]->getId()."horyzone"), time() + 60 * 60 * 24 * 2);
                                // Notification
                                $_SESSION["auth"] = $auth;
                                $this->alert(["Vous êtes connecté !"], "success");
                                return $this->redirect($response, "home");
                            } else {
                                $errors[] = "Le mot de passe ne correspond pas à l'adresse email.";
                                $this->alert($errors, "danger");
                                return $this->redirect($response, "login", 400);
                            }
                        }
                    } else {
                        $errors[] = "Cette adresse email n'existe pas/plus.";
                        $this->alert($errors, "danger");
                        return $this->redirect($response, "login", 400);
                    }
                } else {
                    $this->alert($errors, "danger");
                    return $this->redirect($response, "login", 400);
                }
            } else {
                $this->alert(["Formulaire invalide, veuillez réessayer."], "danger");
                return $this->redirect($response, "login", 400);
            }
        } else {
            return $this->redirect($response, "login");
        }
    }

    /**
     * Retourne une redirection après la déconnexion de l'utilisateur
     *
     * @return ResponseInterface
     */
    public function getLogout(RequestInterface $request, ResponseInterface $response)
    {
        if (isset($_SESSION["auth"]) && !empty($_SESSION["auth"])) {
            unset($_SESSION["auth"]);
            unset($_SESSION["csrf"]);
            unset($_SESSION["token"]);
            $this->alert(["Vous êtes déconnecté !"], "success");
            return $this->redirect($response, "login");
        } else {
            return $this->redirect($response, "login");
        }
    }
}
