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
        if (tools::methodAccepted('application/json')) {
            get_grades($DB);
        }
        break;
    case 'POST':                     # CREATE
        if (tools::methodAccepted('multipart/form-data')) {
            create_grade($DB);
        }
        break;
    case 'PUT':                      # UPDATE (données seulement)
        if (tools::methodAccepted('application/json')) {
            update_grade($DB);
        }
        break;
    case 'PATCH':                    # UPDATE (image seulement)
        if (tools::methodAccepted('multipart/form-data')) {
            update_image($DB);
        }
        break;
    case 'DELETE':                   # DELETE
        if (tools::methodAccepted('application/json')) {
            delete_grade($DB);
        }
        break;
    default:
        # 405 Method Not Allowed
        http_response_code(405);
        break;
}


function get_grades($DB){

    if (isset($_GET['id']))
    {
        $id = $DB->clean($_GET['id']);
        $grades = $DB->select("SELECT * FROM GRADE WHERE id_grade = ?", "i", [$id]);

        if (count($grades) === 0) {
            http_response_code(404);
            echo json_encode(['error' => 'Grade not found']);
            return;
        }

        $grades = $grades[0];

    } else {
        $grades = $DB->select("SELECT * FROM GRADE");
    }

    echo json_encode($grades);

}

function create_grade($DB)
{
    if (!isset($_POST['name'], $_POST['description'], $_POST['price'], $_POST['reduction'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Incomplete data']);
        return;
    }


    $image_name = tools::saveImage();

    if ($image_name === false) {
        http_response_code(415);
        echo json_encode(['error' => 'Image could not be processed']);
        return;
    }

    $name = $DB->clean($_POST['name']);
    $description = $DB->clean($_POST['description']);
    $price = $DB->clean($_POST['price']);
    $reduction = $DB->clean($_POST['reduction']);

    # Génération d'un nom de l'image unique, au format (chaine de 16 caractères random).(extension)

    $id = $DB->query("INSERT INTO GRADE (nom_grade, description_grade, prix_grade, reduction_grade, image_grade)
                           VALUES (?, ?, ?, ?, ?)",
                        "ssdds", [$name, $description, $price, $reduction, $image_name]);

    http_response_code(201);
    echo json_encode(['id' => $id]);
}

function update_grade($DB){

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!isset($_GET['id'], $data['name'], $data['description'], $data['price'], $data['reduction'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Incomplete data']);
        return;
    }

    $id = $DB->clean($_GET['id']);
    $name = $DB->clean($data['name']);
    $description = $DB->clean($data['description']);
    $price = $DB->clean($data['price']);
    $reduction = $DB->clean($data['reduction']);

    $DB->query("UPDATE GRADE SET nom_grade = ?, description_grade = ?, prix_grade = ?, reduction_grade = ? WHERE id_grade = ?",
                "ssddi", [$name, $description, $price, $reduction, $id]);

    $grade = $DB->select("SELECT * FROM GRADE WHERE id_grade = ?", "i", [$id]);

    http_response_code(204);
    echo json_encode($grade[0]);

}

function update_image($DB)
{
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Please provide an id']);
        return;
    }

    $id = $DB->clean($_GET['id']);

    $image_name = tools::saveImage();

    if ($image_name === false) {
        http_response_code(415);
        echo json_encode(['error' => 'Image couldn\'t be processed']);
        return;
    }

    // On récupère l'ancienne image pour la supprimer, et on en profite pour vérifier que le grade existe
    $old_image = $DB->select("SELECT image_grade FROM GRADE WHERE id_grade = ?", "i", [$id]);

    if (count($old_image) === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Grade not found']);
        return;
    }

    tools::deleteFile($old_image[0]['image_grade']);

    $result = $DB->query("UPDATE GRADE SET image_grade = ? WHERE id_grade = ?",
                "si", [$image_name, $id]);

    http_response_code(204);
    echo json_encode($result[0]);

}

function delete_grade($DB)
{

    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Please provide an id']);
        return;
    }

    $id = $DB->clean($_GET['id']);


    // On vérifie que le grade existe, et on récupère l'image pour la supprimer
    $image = $DB->select("SELECT image_grade FROM GRADE WHERE id_grade = ?", "i", [$id]);

    if (count($image) === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Grade not found']);
        return;
    }

    tools::deleteFile($image[0]['image_grade']);

    $DB->query("DELETE FROM GRADE WHERE id_grade = ?", "i", [$id]);


    http_response_code(204);
    echo json_encode(['id' => $id]);
}

