<?php
namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator;

use App\Entity\Marker;

class HomeController extends Controller
{
    public function getHome(RequestInterface $request, ResponseInterface $response)
    {
        $auth = $_SESSION["auth"];
        $user = $auth["id_user"];
        $markers = $this->em->getRepository("App\Entity\Marker")->getMarkersByUser($user);
        $key = $_ENV["API_KEY_MAP"];
        $params = compact("key", "user", "markers");
        return $this->render($response, 'pages/home.twig', $params);
    }

    public function postAjaxAddMarker(RequestInterface $request, ResponseInterface $response)
    {
        if ($request->getAttribute("token")) {
            if (!$this->tokenCheck($request->getAttribute("token"))) {
                $response->getBody()->write(json_encode([
                    "error" => "Token invalide",
                    "code" => 403
                ]));
                $response->withStatus(403);
                return $response->withHeader('Content-Type', 'application/json');
            }
        } else {
            $response->getBody()->write(json_encode([
                "error" => "Token invalide",
                "code" => 403
            ]));
            $response->withStatus(403);
            return $response->withHeader('Content-Type', 'application/json');
        }
        $params = $request->getParsedBody();
        if (isset($params["lat"]) && $params["lat"] != "" && isset($params["lng"]) && $params["lng"] != ""
        && isset($params["date"]) && $params["date"] != "" && isset($params["user"]) && $params["user"] != ""
        && Validator::date('d/m/Y')->validate($params["date"])) {
            $date = explode("/", $params["date"]);
            $user = $this->em->getRepository("App\Entity\User")->getUserById($params["user"]);
            if (is_array($user) && count($user) >= 1) {
                if ($user[0]->isActive()) {
                    $marker = new Marker();
                    $marker->setLat($params["lat"]);
                    $marker->setLng($params["lng"]);
                    $marker->setDate(new \DateTime($date[2]."-".$date[1]."-".$date[0]));
                    $marker->setUser($user[0]);
                    $this->em->persist($marker);
                    $this->em->flush();
                    $response->getBody()->write(json_encode([
                        "message" => "Marqueur ajouté",
                        "code" => 201
                    ]));
                    $response->withStatus(201);
                    return $response->withHeader('Content-Type', 'application/json');
                } else {
                    $response->getBody()->write(json_encode([
                        "error" => "Utilisateur invalide",
                        "code" => 403
                    ]));
                    $response->withStatus(403);
                    return $response->withHeader('Content-Type', 'application/json');
                }
            } else {
                $response->getBody()->write(json_encode([
                    "error" => "Utilisateur invalide",
                    "code" => 403
                ]));
                $response->withStatus(403);
                return $response->withHeader('Content-Type', 'application/json');
            }
        } else {
            $response->getBody()->write(json_encode([
                "error" => "Champs vides ou invalides",
                "code" => 206
            ]));
            $response->withStatus(206);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
}
