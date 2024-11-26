<!DOCTYPE html>
<html lang="fr">
    <?php 
        require_once 'database.php';
        $db = new DB();

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $eventid = $_GET['id'];
            $event = $db->select(
                "SELECT `nom_evenement`, `xp_evenement`, `places_evenement`, `prix_evenement`, `reductions_evenement`, `lieu_evenement`, `date_evenement`, `image_evenement`, `description_evenement`
                FROM EVENEMENT WHERE id_evenement = ?",
                "i",
                [$eventid]
            );
            if(empty($event) || is_null($event)){
                header("Location: index.php");
                exit;
            }
            $event = $event[0];
        }else{
            header("Location: index.php");
            exit;
        }
    ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title><?php echo $event['nom_evenement']?></title>
    
    <link rel="stylesheet" href="styles/general_style.css">
    <link rel="stylesheet" href="styles/header_style.css">
    <link rel="stylesheet" href="styles/footer_style.css">

    <link rel="stylesheet" href="styles/event_details_style.css">

</head>
<body>
<?php
    require_once 'header.php';

    $isLoggedIn = isset($_SESSION["userid"]);

    
?>
<section>
    <div class="event-container">
        <img src="api/files/<?php echo $event['image_evenement']; ?>" alt="Image de l'événement">

        <h1><?php echo strtoupper($event['nom_evenement']); ?></h1>

        <div>
            <h2><?php echo date('d/m/Y', strtotime($event['date_evenement'])); ?></h2>
            <button class="registration-button">Inscription</button>
        </div>

        <ul>
            <li><div>📍<h3><?php echo $event['lieu_evenement']; ?></h3></div></li>
            <li><div>💸<h3><?php echo $event['prix_evenement']; ?>€ par personne</h3></div></li>
            <?php if(boolval($event['reductions_evenement'])){echo "<li><div>💎<h3>-10% pour les membres Diamants</h3></div></li>";} ?>
        </ul>

        <p>
            <?php echo nl2br(htmlspecialchars($event['description_evenement'])); ?>
        </p>


    </div>
</section>

<?php require_once 'footer.php';?>

</body>
</html>