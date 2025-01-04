<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commander</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link rel="stylesheet" href="styles/general_style.css">
    <link rel="stylesheet" href="styles/order_style.css">
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



$isLoggedIn = isset($_SESSION["userid"]);
if (!$isLoggedIn) {
    header("Location: login.php");
    exit;
}

$userid = $_SESSION["userid"];

// Récupérer le panier
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Calculer le total de la commande
$total = 0;
$cart = $_SESSION['cart'];
$product_ids = array_keys($cart);
$placeholders = implode(",", array_fill(0, count($product_ids), "?"));
$query = "SELECT id_article, nom_article, prix_article FROM ARTICLE WHERE id_article IN ($placeholders)";
$types = str_repeat("i", count($product_ids));
$products = $db->select($query, $types, $product_ids);

$cart_items = [];
foreach ($products as $product) {
    $cart_items[$product['id_article']] = [
        'nom_article' => $product['nom_article'], // Ajout du nom de l'article
        'prix_article' => $product['prix_article'],
        'quantite' => $cart[$product['id_article']],
    ];
    $total += $product['prix_article'] * $cart[$product['id_article']];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['mode_paiement']) && !empty($_POST['mode_paiement'])) {
        $mode_paiement = $_POST['mode_paiement'];

        // Enregistrer la commande dans la base de données
        foreach ($cart_items as $product_id => $item) {
            $db->query(
                "CALL achat_article(?, ?, ?, ?)",
                "iiis",
                [$userid, $product_id, $item['quantite'], $mode_paiement]
            );
        }
        $_SESSION['cart'] = [];
        
        $_SESSION['message'] = "Commande réalisée avec succès !";
        $_SESSION['message_type'] = "success";

        header("Location: cart.php"); // Rediriger vers le panier
        exit;
    } else {
    }
}
?>



<!--------------->
<!------HTML----->
<!--------------->

<h1>MA COMMANDE</h1>

<div>
    <button id="cart-button" >
        <a href="cart.php">Retourner au panier</a>
    </button>
</div>

<div>
    <table>
        <thead>
            <tr>
                <th>Article</th>
                <th>Quantité</th>
                <th>Prix Unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $product_id => $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['nom_article']); ?></td>
                    <td><?php echo $item['quantite']; ?></td>
                    <td><?php echo number_format($item['prix_article'], 2, ',', ' ') . " €"; ?></td>
                    <td><?php echo number_format($item['prix_article'] * $item['quantite'], 2, ',', ' ') . " €"; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<h3>Total : <?php echo number_format($total, 2, ',', ' ') . " €"; ?></h3>

<form method="POST" action="order.php">

    <label for="mode_paiement">Mode de Paiement :</label>
    <select id="mode_paiement" name="mode_paiement" required>
        <option value="carte_credit">Carte de Crédit</option>
        <option value="paypal">PayPal</option>
    </select><br><br>

    <div id="carte_credit" class="mode_paiement_fields">
        <label for="numero_carte">Numéro de Carte :</label>
        <input type="text" id="numero_carte" name="numero_carte" placeholder="XXXX XXXX XXXX XXXX" required><br><br>

        <label for="expiration">Date d'Expiration :</label>
        <input type="text" id="expiration" name="expiration" placeholder="MM/AA" required><br><br>

        <label for="cvv">CVV :</label>
        <input type="text" id="cvv" name="cvv" placeholder="XXX" required><br><br>
    </div>

    <div id="paypal" class="mode_paiement_fields" style="display: none;">
        <label for="compte_paypal">Connectez-vous à votre compte PayPal :</label><br>
        <button type="button">Se connecter à PayPal</button><br><br>
    </div>

    <button type="submit">Valider la commande</button>
</form>




<script>
    document.getElementById('mode_paiement').addEventListener('change', function() {
        var modePaiement = this.value;
        if (modePaiement === 'carte_credit') {
            document.getElementById('carte_credit').style.display = 'block';
            document.getElementById('paypal').style.display = 'none';
        } else if (modePaiement === 'paypal') {
            document.getElementById('carte_credit').style.display = 'none';
            document.getElementById('paypal').style.display = 'block';
        }
    });
</script>


<?php require_once "footer.php" ?>

</body>
</html>
