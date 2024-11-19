<?php

require_once 'DB.php';
require_once 'tools.php';

// TODO: Remove this line in production
ini_set('display_errors', 1);

header('Content-Type: application/json');

$DB = new DB();

$methode = $_SERVER['REQUEST_METHOD'];

switch ($methode) {
    case 'GET':                      # READ
        if (tools::methodAccepted('application/json')) {
            get_meetings($DB);
        }
        break;
    case 'POST':                     # CREATE
        if (tools::methodAccepted('multipart/form-data')) {
            create_accounting($DB);
        }
        break;
    case 'DELETE':                   # DELETE
        if (tools::methodAccepted('application/json')) {
            delete_accounting($DB);
        }
        break;
    default:
        # 405 Method Not Allowed
        http_response_code(405);
        break;
}

function get_meetings($DB) {
    if (isset($_GET['id'])) {
        // Si un ID est précisé, on renvoie les infos de l'utilisateur correspondant avec ses rôles
        $id = $_GET['id'];

        $data = $DB->select("SELECT *
                             FROM REUNION
                             WHERE REUNION.id_reunion = ?", "i", [$id]);

        if (count($data) == 1) {
            $data = $data[0];


            $user = $DB->select("SELECT id_membre, nom_membre, prenom_membre, pp_membre
                                 FROM MEMBRE
                                 WHERE MEMBRE.id_membre = ?", "i", [$data['id_membre']]);

            $data['user'] = $user[0];
            unset($data['id_membre']);

        } else {
            http_response_code(404);
            echo json_encode(["message" => "Meeting file not found"]);
            return;
        }

    } else {
        // Sinon, on renvoie la liste de tous les utilisateurs. On va juste préciser si ils ont des rôles ou non
        $data = $DB->select("SELECT id_reunion, date_reunion, fichier_reunion
                             FROM REUNION");
    }

    echo json_encode($data);
}


function create_accounting($DB)
{
    // TODO : Récupérer l'ID de membre grace au token PHP

    $file = tools::saveFile();

    if ($file) {
        $DB = new DB();

        $DB->query("INSERT INTO REUNION (date_reunion, fichier_reunion, id_membre)
                     VALUES (?, ?, ?)", "ssi", [$_POST['date_reunion'], $file, $_POST['id_membre']]);

        http_response_code(201);
        echo json_encode(["message" => "Meeting  file created"]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Meeting file not created"]);
    }

}


function delete_accounting($DB)
{
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["message" => "Missing parameters"]);
        return;
    }

    $data = $DB->select("SELECT fichier_reunion
                         FROM REUNION
                         WHERE REUNION.id_reunion = ?", "i", [$_GET['id']]);

    if (count($data) == 1) {
        $data = $data[0];

        if (tools::deleteFile($data['fichier_reunion'])) {
            $DB->delete("DELETE FROM REUNION WHERE id_reunion = ?", "i", [$_GET['id']]);

            http_response_code(200);
            echo json_encode(["message" => "Meeting file deleted"]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Meeting file could not deleted"]);
        }
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Meeting file not found"]);
    }
}

