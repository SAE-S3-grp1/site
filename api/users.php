<?php
require_once 'DB.php';
require_once 'tools.php';

// TODO: Remove this line in production
ini_set('display_errors', 1);

header('Content-Type: application/json');

$methode = $_SERVER['REQUEST_METHOD'];

$DB = new DB();


# On accepte le format multipart/form-data UNIQUEMENT sur les requetes POST et PATCH
# Sinon, il faudrait coder un parser de multipart/form-data
switch ($methode) {
    case 'GET':                      # READ
        if (tools::methodAccepted('application/json')) {
            get_users($DB);
        }
        break;
    case 'POST':                     # CREATE
        if (tools::methodAccepted('multipart/form-data')) {
            create_user($DB);
        }
        break;
    case 'PUT':                      # UPDATE (données seulement)
        if (tools::methodAccepted('application/json')) {
            update_user($DB);
        }
        break;
    case 'PATCH':                    # UPDATE (image seulement)
        if (tools::methodAccepted('multipart/form-data')) {
            update_image($DB);
        }
        break;
    case 'DELETE':                   # DELETE
        if (tools::methodAccepted('application/json')) {
            delete_user($DB);
        }
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
    if (!isset($_POST['name'], $_POST['firstname'], $_POST['email'], $_POST['tp'])) {
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

    $id = $DB->query("INSERT INTO MEMBRE (nom_membre, prenom_membre, email_membre, tp_membre, pp_membre) VALUES (?, ?, ?, ?, ?)", "sssss", [$_POST['name'], $_POST['firstname'], $_POST['email'], $_POST['tp'], $imagename]);

    $result = $DB->select("SELECT * FROM MEMBRE WHERE id_membre = ?", "i", [$id]);

    http_response_code(201);
    echo json_encode($result[0]);
}

function update_user($DB)
{

    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['name'], $data['firstname'], $data['email'], $data['tp'], $_GET['id'])) {
        http_response_code(400);
        echo json_encode(["message" => "Missing parameters"]);
        return;
    }

    $id = $DB->clean($_GET['id']);
    $name = $DB->clean($data['name']);
    $surname = $DB->clean($data['firstname']);
    $email = $DB->clean($data['email']);
    $tp = $DB->clean($data['tp']);

    $DB->query("UPDATE MEMBRE SET nom_membre = ?, prenom_membre = ?, email_membre = ?, tp_membre = ? WHERE id_membre = ?", "ssssi", [$name, $surname, $email, $tp, $id]);

    $user = $DB->select("SELECT * FROM MEMBRE WHERE id_membre = ?", "i", [$id]);

    if (count($user) == 1) {
        $user = $user[0];
        $user['roles'] = $DB->select("SELECT ROLE.*
                         FROM ROLE
                         INNER JOIN ASSIGNATION
                         ON ROLE.id_role = ASSIGNATION.id_role
                         WHERE ASSIGNATION.id_membre = ?", "i", [$id]);

    } else {
        http_response_code(404);
        echo json_encode(["message" => "User not found"]);
        return;
    }

    http_response_code(200);
    echo json_encode($user);
}

function update_image($DB)
{
    $id = $DB->clean($_GET['id']);

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
    $id = $DB->clean($_GET['id']);

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


