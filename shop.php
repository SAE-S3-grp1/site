<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link rel="stylesheet" href="styles/general_style.css">
    <link rel="stylesheet" href="styles/shop_style.css">
    <link rel="stylesheet" href="styles/header_style.css">
    <link rel="stylesheet" href="styles/footer_style.css">

</head>

<body class="body_margin">



<!--------------->
<!------PHP------>
<!--------------->

 <!-- Importer les fichiers -->
<?php 
require_once "header.php" ;
require_once 'database.php';
require_once 'files_save.php';

// Connexion à la base de donnees
$db = new DB();

//Gestion des filtres et tris
$filters = [];
$orderBy = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset'])) {
        // Réinitialiser les filtres et tri
        $filters = [];
        $orderBy = "";
    } else {
        if (isset($_POST['category'])) {
            $filters = $_POST['category'];
        }
        if (isset($_POST['sort'])) {
            $orderBy = $_POST['sort'];
        }
    }
}

$query = "SELECT * FROM ARTICLE";
$whereClauses = [];
$params = [];

// Ajout des filtres par catégorie
if (!empty($filters)) {
    $placeholders = implode(", ", array_fill(0, count($filters), "?"));
    $whereClauses[] = "categorie_article IN ($placeholders)";
    $params = array_merge($params, $filters);
}

// Ajout des clauses WHERE
if (!empty($whereClauses)) {
    $query .= " WHERE " . implode(" AND ", $whereClauses);
}

// Ajout du tri
if ($orderBy === "price_asc") {
    $query .= " ORDER BY prix_article ASC";
} elseif ($orderBy === "price_desc") {
    $query .= " ORDER BY prix_article DESC";
} elseif ($orderBy === "name_asc") {
    $query .= " ORDER BY nom_article ASC";
} elseif ($orderBy === "name_desc") {
    $query .= " ORDER BY nom_article DESC";
}

// Exécution de la requête
$products = $db->select($query, str_repeat("s", count($params)), $params);
?>

<!--------------->
<!------HTML----->
<!--------------->

<H1>LA BOUTIQUE</H1>

<div class="filter-section">
    <form method="post">
        <fieldset>
            <legend>Catégories</legend>
            <label><input type="checkbox" name="category[]" value="Sucré" <?= in_array('Sucré', $filters) ? 'checked' : '' ?>> Sucré</label><br>
            <label><input type="checkbox" name="category[]" value="Salé" <?= in_array('Salé', $filters) ? 'checked' : '' ?>> Salé</label><br>
            <label><input type="checkbox" name="category[]" value="Boisson" <?= in_array('Boisson', $filters) ? 'checked' : '' ?>> Boisson</label><br>
            <label><input type="checkbox" name="category[]" value="Merch" <?= in_array('Merch', $filters) ? 'checked' : '' ?>> Merch</label>
        </fieldset>
        <fieldset>
            <legend>Trier par</legend>
            <select name="sort">
                <option value="name_asc" <?= $orderBy === 'name_asc' ? 'selected' : '' ?>>Alphabétique (A-Z)</option>
                <option value="name_desc" <?= $orderBy === 'name_desc' ? 'selected' : '' ?>>Alphabétique (Z-A)</option>
                <option value="price_asc" <?= $orderBy === 'price_asc' ? 'selected' : '' ?>>Prix croissant</option>
                <option value="price_desc" <?= $orderBy === 'price_desc' ? 'selected' : '' ?>>Prix décroissant</option>
            </select>
        </fieldset>
        <button type="submit" name="reset">Réinitialiser</button>
        <button type="submit">Appliquer</button>
    </form>
</div>

<div class="product-list">
    <?php if (!empty($products)) : ?>
        <ul>
            <?php foreach ($products as $product) : ?>
                <li>
                    <h3><?= htmlspecialchars($product['nom_article']) ?></h3>
                    <p>Prix : <?= htmlspecialchars($product['prix_article']) ?> €</p>
                    <p>Catégorie : <?= htmlspecialchars($product['categorie_article']) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>Aucun produit trouvé pour les critères sélectionnés.</p>
    <?php endif; ?>
</div>


<?php require_once "footer.php" ?>

</body>
</html>