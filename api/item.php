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
        get_items($DB);
        break;
    case 'POST':                     # CREATE
        if (tools::methodAccepted('multipart/form-data')) {
            create_item($DB);
        }
        break;
    case 'PUT':                      # UPDATE (données seulement)
        if (tools::methodAccepted('application/json')) {
            update_item($DB);
        }
        break;
    case 'PATCH':                    # UPDATE (image seulement)
        if (tools::methodAccepted('multipart/form-data')) {
            update_image($DB);
        }
        break;
    case 'DELETE':                   # DELETE
        delete_item($DB);
        break;
    default:
        # 405 Method Not Allowed
        http_response_code(405);
        break;
}


function get_items($DB)
{
    if (isset($_GET['id']))
    {
        $id = $DB->clean($_GET['id']);
        $items = $DB->select("SELECT * FROM ARTICLE WHERE id_article = ?", "i", [$id]);
        $items = $items[0];

        if (count($items) === 0) {
            http_response_code(404);
            echo json_encode(['error' => 'Item not found']);
            return;
        }

    } else {
        $items = $DB->select("SELECT * FROM ARTICLE");
    }

    echo json_encode($items);
}

function create_item($DB)
{
    if (!isset($_POST['name'], $_POST['xp'], $_POST['stocks'], $_POST['reduction'], $_POST['price']))
    {
        http_response_code(400);
        echo json_encode(['error' => 'Missing parameters']);
        return;
    }

    $imageName = tools::saveImage();

    if (!$imageName)
    {
        http_response_code(400);
        echo json_encode(['error' => 'Image could not be processed']);
        return;
    }

    $name = $DB->clean($_POST['name']);
    $xp = $DB->clean($_POST['xp']);
    $stocks = $DB->clean($_POST['stocks']);
    $reduction = $DB->clean($_POST['reduction']);
    $price = $DB->clean($_POST['price']);

    $id = $DB->query("INSERT INTO ARTICLE (nom_article, xp_article, stock_article, reduction_article, prix_article, image_article) VALUES (?, ?, ?, ?, ?, ?)", "siiids", [$name, $xp, $stocks, $reduction, $price, $imageName]);

    $reuslt = $DB->select("SELECT * FROM ARTICLE WHERE id_article = ?", "i", [$id]);

    http_response_code(201);
    echo json_encode($reuslt[0]);
}

function update_item($DB)
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($_GET['id'], $data['name'], $data['xp'], $data['stocks'], $data['reduction'], $data['price']))
    {
        http_response_code(400);
        echo json_encode(['error' => 'Missing parameters']);
        return;
    }

    $id = $DB->clean($_GET['id']);
    $name = $DB->clean($data['name']);
    $xp = $DB->clean($data['xp']);
    $stocks = $DB->clean($data['stocks']);
    $reduction = $DB->clean($data['reduction']);
    $price = $DB->clean($data['price']);

    $DB->query("UPDATE ARTICLE SET nom_article = ?, xp_article = ?, stock_article = ?, reduction_article = ?, prix_article = ? WHERE id_article = ?", "siiidi", [$name, $xp, $stocks, $reduction, $price, $id]);

    $reuslt = $DB->select("SELECT * FROM ARTICLE WHERE id_article = ?", "i", [$id]);

    echo json_encode($reuslt[0]);
}

function update_image($DB)
{
    if (!isset($_GET['id']))
    {
        http_response_code(400);
        echo json_encode(['error' => 'Missing parameters']);
        return;
    }

    $imageName = tools::saveImage();

    if (!$imageName)
    {
        http_response_code(400);
        echo json_encode(['error' => 'Image could not be processed']);
        return;
    }

    $oldImage = $DB->select("SELECT image_article FROM ARTICLE WHERE id_article = ?", "i", [$DB->clean($_GET['id'])]);


    $id = $DB->clean($_GET['id']);

    $DB->query("UPDATE ARTICLE SET image_article = ? WHERE id_article = ?", "si", [$imageName, $id]);



}