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

