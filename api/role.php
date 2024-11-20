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
            get_role($DB);
        }
        break;
    case 'POST':                     # CREATE
        if (tools::methodAccepted('application/json')) {
            create_role($DB);
        }
        break;
    case 'PUT':
        if (tools::methodAccepted('application/json')) {
            update_role($DB);
        }
        break;

    case 'DELETE':                   # DELETE
        if (tools::methodAccepted('application/json')) {
            delete_role($DB);
        }
        break;
    default:
        # 405 Method Not Allowed
        http_response_code(405);
        break;
}

function get_role($DB)
{

    if (isset($_GET['id'])) {
        $data = $DB->select('SELECT * FROM ROLE WHERE id_role = ?', 's', $_GET['id']);

        if (count($data) == 1) {
            $data = $data[0];
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Role not found']);
            return;
        }

    } else {
        $data = $DB->select('SELECT * FROM ROLE');
    }
    http_response_code(200);
    echo json_encode($data);
}

function create_role($DB)
{
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['name'], $data['permissions'])) {

        $p_log = $data['permissions']['p_log'] ?? false;
        $p_boutique = $data['permissions']['p_boutique'] ?? false;
        $p_reunion = $data['permissions']['p_membres'] ?? false;
        $p_utilisateur = $data['permissions']['p_utilisateur'] ?? false;
        $p_grade = $data['permissions']['p_grade'] ?? false;
        $p_role = $data['permissions']['p_role'] ?? false;
        $p_actualite = $data['permissions']['p_actualite'] ?? false;
        $p_evenement = $data['permissions']['p_evenement'] ?? false;
        $p_comptabilite = $data['permissions']['p_comptabilite'] ?? false;
        $p_achat = $data['permissions']['p_achat'] ?? false;
        $p_moderation = $data['permissions']['p_moderation'] ?? false;

        $id = $DB->query('INSERT INTO ROLE (nom_role, p_log, p_boutique, p_reunion, p_utilisateur, p_grade, p_role, p_actualite, p_evenement, p_comptabilite, p_achat, p_moderation)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', 'siiiiiiiiiii'
                     , [$data['name'], $p_log, $p_boutique, $p_reunion, $p_utilisateur, $p_grade, $p_role, $p_actualite, $p_evenement, $p_comptabilite, $p_achat, $p_moderation]);

        $inserted = $DB->select('SELECT * FROM ROLE WHERE id_role = ?', 's', [$id]);
        http_response_code(201);
        echo json_encode($inserted);

    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Missing parameters']);
    }
}

function update_role($DB)
{

    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['name'], $data['permissions'], $_GET['id'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Missing parameters']);
        return;
    }

    $id = $DB->clean($_GET['id']);
    $name = $DB->clean($data['name']);
    $p_log = $data['permissions']['p_log'] ?? false;
    $p_boutique = $data['permissions']['p_boutique'] ?? false;
    $p_reunion = $data['permissions']['p_membres'] ?? false;
    $p_utilisateur = $data['permissions']['p_utilisateur'] ?? false;
    $p_grade = $data['permissions']['p_grade'] ?? false;
    $p_role = $data['permissions']['p_role'] ?? false;
    $p_actualite = $data['permissions']['p_actualite'] ?? false;
    $p_evenement = $data['permissions']['p_evenement'] ?? false;
    $p_comptabilite = $data['permissions']['p_comptabilite'] ?? false;
    $p_achat = $data['permissions']['p_achat'] ?? false;
    $p_moderation = $data['permissions']['p_moderation'] ?? false;

    $DB->query('UPDATE ROLE SET nom_role = ?, p_log = ?, p_boutique = ?, p_reunion = ?, p_utilisateur = ?, p_grade = ?, p_role = ?, p_actualite = ?, p_evenement = ?, p_comptabilite = ?, p_achat = ?, p_moderation = ? WHERE id_role = ?',
        'siiiiiiiiiiii', [$name, $p_log, $p_boutique, $p_reunion, $p_utilisateur, $p_grade, $p_role, $p_actualite, $p_evenement, $p_comptabilite, $p_achat, $p_moderation, $id]);

    $role = $DB->select('SELECT * FROM ROLE WHERE id_role = ?', 's', [$id]);

    if (count($role) == 1) {
        $role = $role[0];

        http_response_code(200);
        echo json_encode($role);

    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Role not found']);
    }
}

function delete_role($DB)
{
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Please provide an id']);
        return;
    }

    $id = $DB->clean($_GET['id']);

    $role = $DB->select('SELECT * FROM ROLE WHERE id_role = ?', 's', [$id]);

    if (count($role) == 0) {
        http_response_code(404);
        echo json_encode(['message' => 'Role not found']);
        return;
    }

    $DB->query('DELETE FROM ROLE WHERE id_role = ?', 's', [$id]);

    http_response_code(204);
}

