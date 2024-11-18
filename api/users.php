<?php
require_once 'DB.php';
require_once 'tools.php';

// TODO: Remove this line in production
ini_set('display_errors', 1);

header('Content-Type: application/json');

$methode = $_SERVER['REQUEST_METHOD'];

$DB = new DB();

switch ($methode) {
    case 'GET':                      # READ
        get_users($DB);
        break;
    case 'POST':                     # CREATE
        create_user($DB);
        break;
    case 'PUT':                      # UPDATE
        update_user($DB);
        break;
    case 'DELETE':                   # DELETE
        delete_user($DB);
        break;
    default:
        # 405 Method Not Allowed
        http_response_code(405);
        break;
}

function get_users($DB) {
    if (isset($_GET['id'])) {
        // Si un ID est précisé, on renvoie les infos de l'utilisateur correspondant avec ses rôles
        $id = $_GET['id'];

        $data = $DB->select("SELECT id_membre, nom_membre, prenom_membre, email_membre, xp_membre, pp_membre, tp_membre 
                             FROM MEMBRE 
                             WHERE MEMBRE.id_membre = ?", "i", [$id]);

        if (count($data) == 1) {
            $data = $data[0];
            $data['roles'] = $DB->select("SELECT ROLE.*
                         FROM ROLE
                         INNER JOIN ASSIGNATION
                         ON ROLE.id_role = ASSIGNATION.id_role
                         WHERE ASSIGNATION.id_membre = ?", "i", [$id]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "User not found"]);
            return;
        }

    } else {
        // Sinon, on renvoie la liste de tous les utilisateurs. On va juste préciser si ils ont des rôles ou non
        $data = $DB->select("SELECT id_membre, nom_membre, prenom_membre, email_membre, xp_membre, pp_membre, tp_membre, (SELECT COUNT(*) FROM ASSIGNATION WHERE MEMBRE.id_membre = ASSIGNATION.id_membre) as nb_roles
                             FROM MEMBRE");
    }

    http_response_code(200);
    echo json_encode($data);
}