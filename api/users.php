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
    case 'PUT':                      # UPDATE (données seulement)
        update_user($DB);
        break;
    case 'PATCH':                    # UPDATE (image seulement)
        update_image($DB);
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

function create_user($DB)
{
    if (!isset($_POST['name'], $_POST['surname'], $_POST['email'], $_POST['tp'])) {
        http_response_code(400);
        echo json_encode(["message" => "Missing parameters"]);
        return;
    }

    $imagename = tools::saveImage();

    if (!$imagename) {
        http_response_code(415);
        echo json_encode(["message" => "Image could not be processed"]);
        return;
    }

    $id = $DB->query("INSERT INTO MEMBRE (nom_membre, prenom_membre, email_membre, tp_membre, pp_membre) VALUES (?, ?, ?, ?, ?)", "sssss", [$_POST['name'], $_POST['surname'], $_POST['email'], $_POST['tp'], $imagename]);

    http_response_code(201);
    echo json_encode(["id" => $id]);
}

function update_user($DB)
{

    $_PUT =  json_decode(file_get_contents('php://input'),true);
    if (!isset($_PUT['name'], $_PUT['surname'], $_PUT['email'], $_PUT['tp'])) {
        http_response_code(400);
        echo json_encode(["message" => "Missing parameters"]);
        return;
    }

    $id = $DB->clean($_PUT['id']);
    $name = $DB->clean($_PUT['name']);
    $surname = $DB->clean($_PUT['surname']);
    $email = $DB->clean($_PUT['email']);
    $tp = $DB->clean($_PUT['tp']);

    $DB->query("UPDATE MEMBRE SET nom_membre = ?, prenom_membre = ?, email_membre = ?, tp_membre = ? WHERE id_membre = ?", "ssssi", [$name, $surname, $email, $tp, $id]);

    $user = $DB->select("SELECT * FROM MEMBRE WHERE id_membre = ?", "i", [$id]);

    if (count($user) == 1) {
        $user = $user[0];
    } else {
        http_response_code(404);
        echo json_encode(["message" => "User not found"]);
        return;
    }

    http_response_code(200);
    echo json_encode(
        [
            "id" => $user['id_membre'],
            "name" => $user['nom_membre'],
            "surname" => $user['prenom_membre'],
            "email" => $user['email_membre'],
            "tp" => $user['tp_membre']
        ]
    );
}

function update_image($DB)
{
    $_PATCH =  json_decode(file_get_contents('php://input'),true);
    $id = $DB->clean($_PATCH['id']);

    $user = $DB->select("SELECT pp_membre FROM MEMBRE WHERE id_membre = ?", "i", [$id]);

    if (count($user) == 0) {
        http_response_code(404);
        echo json_encode(["message" => "User not found"]);
        return;
    }

    $imagename = tools::saveImage();

    if (!$imagename) {
        http_response_code(415);
        echo json_encode(["message" => "Image could not be processed"]);
        return;
    }

    tools::deleteFile($user[0]['pp_membre']);

    $DB->query("UPDATE MEMBRE SET pp_membre = ? WHERE id_membre = ?", "si", [$imagename, $id]);

    $user[0]['pp_membre'] = $imagename;

    http_response_code(200);
    echo json_encode($user[0]);
}

function delete_user($DB)
{
    $_DELETE =  json_decode(file_get_contents('php://input'),true);
    $id = $DB->clean($_DELETE['id']);

    $user = $DB->select("SELECT pp_membre FROM MEMBRE WHERE id_membre = ?", "i", [$id]);

    if (count($user) == 0) {
        http_response_code(404);
        echo json_encode(["message" => "User not found"]);
        return;
    }

    tools::deleteFile($user[0]['pp_membre']);

    $DB->query("DELETE FROM MEMBRE WHERE id_membre = ?", "i", [$id]);

    http_response_code(204);
}


