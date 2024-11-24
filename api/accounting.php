<?php

use model\Accounting;
use model\File;

require_once 'DB.php';
require_once 'tools.php';
require_once 

require_once 'models/Accounting.php';

// TODO: Remove this line in production
ini_set('display_errors', 1);

header('Content-Type: application/json');

$DB = new DB();

$methode = $_SERVER['REQUEST_METHOD'];

switch ($methode) {
    case 'GET':                      # READ
        get_accounting($DB);
        break;

    case 'POST':                     # CREATE
        if (tools::methodAccepted('multipart/form-data')) {
            create_accounting($DB);
        }
        break;
    case 'DELETE':                   # DELETE
            delete_accounting($DB);
        break;
    default:
        # 405 Method Not Allowed
        http_response_code(405);
        break;
}


function get_accounting(): void
{
    if (isset($_GET['id'])) {
        // Si un ID est précisé, on renvoie en plus les infos de l'utilisateur qui a crée le fichier
        $id = $_GET['id'];

        $data = Accounting::getInstance($id);

        if ($data == null) {
            http_response_code(404);
            echo json_encode(["message" => "Accounting file not found"]);
            return;
        }

    } else {

        $data = Accounting::bulkFetch();
    }

    echo json_encode($data);
}


function create_accounting($DB): void
{
    // TODO : Récupérer l'ID de membre grace au token PHP

    $file = File::saveFile();

    if ($file == null) {
        http_response_code(400);
        echo json_encode(["message" => "Accounting file not created"]);
        return;
    }

    if ($file) {

        $DB->query("INSERT INTO COMPTABILITE (date_comptabilite, nom_comptabilite, url_comptabilite, id_membre)
                     VALUES (?, ?, ?, ?)", "sssi", [$_POST['date_comptabilite'], $_POST['nom_comptabilite'], $file, $_POST['id_membre']]);

        http_response_code(201);
        echo json_encode(["message" => "Accounting file created"]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Accounting file not created"]);
    }

}

function delete_accounting($DB)
{
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["message" => "Missing parameters"]);
        return;
    }

    $data = $DB->select("SELECT url_comptabilite
                         FROM COMPTABILITE
                         WHERE COMPTABILITE.id_comptabilite = ?", "i", [$_GET['id']]);

    if (count($data) == 1) {
        $data = $data[0];

        if (tools::deleteFile($data['url_comptabilite'])) {
            $DB->delete("DELETE FROM COMPTABILITE WHERE id_comptabilite = ?", "i", [$_GET['id']]);
            http_response_code(200);
            echo json_encode(["message" => "Accounting file deleted"]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Accounting file could not deleted"]);
        }
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Accounting file not found"]);
    }
}

