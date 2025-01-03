<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon panier</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link rel="stylesheet" href="styles/general_style.css">
    <link rel="stylesheet" href="styles/cart_style.css">
    <link rel="stylesheet" href="styles/header_style.css">
    <link rel="stylesheet" href="styles/footer_style.css">

</head>

<body class="body_margin">



<!--------------->
<!------PHP------>
<!--------------->

<?php 

// Importer les fichiers
require_once "header.php" ;
require_once 'database.php';
require_once 'files_save.php';
require_once 'cart_class.php';

// Connexion à la base de donnees
$db = new DB();

// Initialisation du panier
$cart = new cart($db);
?>

<!-- Suppression d'un article du panier -->
<?php
    if(isset($_GET['del'])){
        $cart->del($_GET['del']);
    }
?>

<!-- On récupère les produits du panier -->
<?php
    $ids = array_keys($_SESSION['cart']);
    if(empty($ids)){
        $products = array();
    }
    else {
        //Préparation de la requete SELECT
        $placeholders = implode(",", array_fill(0, count($ids), "?"));
        $query = "SELECT * FROM ARTICLE WHERE id_article IN ($placeholders)";
        $types = str_repeat("i", count($ids));
        
        $products = $db->select(
            $query, 
            $types, 
            $ids
        );
    }
?>




<!--------------->
<!------HTML----->
<!--------------->


<H1>MON PANIER</H1>

<div>
    <button id="shop-button" >
        <a href="shop.php">Boutique</a>
    </button>
</div>


<div id="cart-container">
    <table id="cart-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Article</th>
                <th>Prix unitaire</th>
                <th>Quantité</th>
                <th>Sous-total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product) :?>

            <tr>
                <td><img src="/api/files/<?php echo $product['image_article']; ?>" alt="Image de l'article" /></td>
                <td><?= htmlspecialchars($product['nom_article']) ?></td>
                <td><?= number_format(htmlspecialchars($product['prix_article']), 2, ',', ' ') ?> €</td>                
                <td><?=$_SESSION['cart'][$product['id_article']]?></td>
                <td><?= number_format(htmlspecialchars($product['prix_article'] * $_SESSION['cart'][$product['id_article']]), 2, ',', ' ') ?> €</td>  
                <td>
                        <a href="update_cart.php?id=<?= $item['id'] ?>&action=add">+</a>
                        <a href="update_cart.php?id=<?= $item['id'] ?>&action=remove">-</a>
                        <a href="cart.php?del=<?= $product['id_article'] ?>">Supprimer</a>
                    </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                <td><?= number_format($cart->total(), 2, ',', ' ') ?> €</td>
            </tr>
            <tr>
                <th>Nombre d'articles</th>
                <td><?=$cart->count()?> articles</td>
            </tr>
        </tfoot>
    </table>
        
</div>





<?php require_once "footer.php" ?>

</body>
</html>