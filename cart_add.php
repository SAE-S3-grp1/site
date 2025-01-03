<!--------------->
<!------PHP------>
<!--------------->

<?php

// Importer les fichiers
require_once 'database.php';
require_once 'files_save.php';
require_once 'cart_class.php';

// Connexion à la base de donnees
$db = new DB();

// Initialisation du panier
$cart = new cart($db);

if(isset($_GET['id'])){
    $product = $db->select(
        "SELECT id_article FROM ARTICLE WHERE id_article = ?",
        "i",
        [$_GET['id']]
    );

    if(empty($product)){
        die("Ce produit n'existe pas"); 
    }

    $cart->add($product[0]['id_article']);
    die('Le produit a bien été ajouté à votre panier');

} else {
    die("Vous n'avez pas ajouté de produit à ajouter au panier");
}