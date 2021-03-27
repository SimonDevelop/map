<?php
namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ProfilController extends Controller
{
    /**
     * Retourne la page de profil utilisateur
     *
     * @return ResponseInterface
     */
    public function getProfil(RequestInterface $request, ResponseInterface $response)
    {
        $title = "Mon compte";
        $params = compact("title");
        return $this->render($response, "pages/profil.twig", $params);
    }

    /**
     * Retourne une redirection après le traitement de la requête POST
     *
     * @return ResponseInterface
     */
    public function postProfil(RequestInterface $request, ResponseInterface $response)
    {
        $params = $request->getParsedBody();
        if (!is_null($params) && isset($params["pass"]) && isset($params["newPass"]) && isset($params["newPass2"])) {
            if ($request->getAttribute("csrf_status") !== false) {
                if ($params["newPass"] === $params["newPass2"]) {
                    $user = $this->em->getRepository("App\Entity\User")->getUserByEmail($_SESSION["auth"]["email"]);
                    if ($user[0]->passwordVerify($params["pass"])) {
                        $user[0]->setPassword($params["newPass"]);
                        $this->em->persist($user[0]);
                        $this->em->flush();
                        $this->alert(["Nouveau mot de passe enregistré avec succès."]);
                        return $this->redirect($response, "profil");
                    } else {
                        $this->alert([
                            "Le mot de passe actuel saisi est incorrect."
                        ], "danger");
                        return $this->redirect($response, "profil", 400);
                    }
                } else {
                    $this->alert([
                        "Le nouveau mot de passe n'est pas identique au mot de passe de confirmation."
                    ], "danger");
                    return $this->redirect($response, "profil", 400);
                }
            } else {
                $this->alert(["Formulaire invalide, veuillez réessayer."], "danger");
                return $this->redirect($response, "profil", 400);
            }
        } else {
            return $this->redirect($response, "profil", 400);
        }
    }
}
