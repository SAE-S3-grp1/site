<?php

use model\File;
use model\Meeting;
use model\Member;

require_once 'filter.php';
require_once 'models/File.php';
require_once 'models/Meeting.php';
require_once 'models/Member.php';
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
            create_meeting($DB);
        }
        break;
    case 'DELETE':                   # DELETE
        if (tools::methodAccepted('application/json')) {
            delete_meeting($DB);
        }
        break;
    default:
        # 405 Method Not Allowed
        http_response_code(405);
        break;
}

function get_meetings($DB) {
    if (isset($_GET['id'])) {
        $id = filter::int($_GET['id']);
        $meeting = Meeting::getInstance($id);

        if ($meeting) {
            http_response_code(200);
            echo json_encode($meeting);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Meeting not found"]);
        }
    } else {
        $meetings = Meeting::bulkFetch();

        http_response_code(200);
        echo json_encode($meetings);
    }
}


function create_meeting() : void
{
    // TODO : Récupérer l'ID de membre grace au token PHP

    if (isset($_POST['date'], $_POST['user'])) {

        $date = filter::string($_POST['date']);
        $user = Member::getInstance(filter::int($_POST['user']));

        $file = File::saveFile();

        if ($file && $user) {
            $meeting = Meeting::create($date, $file, $user);
            http_response_code(201);
            echo json_encode($meeting);
        } else if (!$file) {
            http_response_code(500);
            echo json_encode(["message" => "Error while saving file"]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "User not found"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Missing parameters"]);
    }

}


function delete_meeting() : void
{
    if (isset($_GET['id'])) {
        $id = filter::int($_GET['id']);

        $meeting = Meeting::getInstance($id);

        if ($meeting) {
            $meeting->delete();
            http_response_code(200);
            echo json_encode(["message" => "Meeting file deleted"]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Meeting file not found"]);
        }
    }
}

