<?php
session_start();

use model\Member;

require_once 'filter.php';
require_once 'models/Role.php';
require_once 'models/Member.php';
require_once 'DB.php';
require_once 'tools.php';

// TODO: Remove this line in production
ini_set('display_errors', 1);

header('Content-Type: application/json');


tools::checkPermission('p_role');
tools::checkPermission('p_utilisateur');


$methode = $_SERVER['REQUEST_METHOD'];


switch ($methode) {
    case 'GET':                      # READ
        get_userRoles();
        break;
    case 'POST':                     # CREATE
        create_role();
        break;
    case 'PUT':
        if (tools::methodAccepted('application/json')) {
            update_role();
        }
        break;

    case 'DELETE':                   # DELETE
        delete_role();
        break;
    default:
        # 405 Method Not Allowed
        http_response_code(405);
        break;
}


function get_userRoles() : void
{
    if (isset($_GET['id'])) {
        // Si un ID est précisé, on renvoie les infos de l'utilisateur correspondant avec ses rôles
        $id = filter::int($_GET['id']);

        $data = Member::getInstance($id);

        if (!$data) {
            http_response_code(404);
            echo json_encode(["message" => "User not found"]);
            return;
        }

        http_response_code(200);
        echo json_encode($data->getRoles());


    } else {
        http_response_code(400);
        echo json_encode(["message" => "Missing id"]);
    }
}

function setUserRoles() : void
{
    if (isset($_GET['id'])) {

        $id = filter::int($_GET['id']);

        $data = Member::getInstance($id);

        if (!$data) {
            http_response_code(404);
            echo json_encode(["message" => "User not found"]);
            return;
        }

        $roles = filter::json($_POST['roles']);

        $success = $data->setRoles($roles);

        if ($success) {
            http_response_code(200);
            echo json_encode($data->getRoles());
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Error while updating roles"]);
        }

    }
}
