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
<body class="body_margin">
<?php
    require_once 'header.php';

    $isLoggedIn = isset($_SESSION["userid"]);
?>
<section class="event-details">
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

</section>


<section class="gallery">
    <h2>GALLERIE</h2>
    <?php if($isLoggedIn):?>

    <h3>Mes photos</h3>
    <div class="my-medias">
        <?php
            $medias = $db->select(
                "SELECT url_media FROM `MEDIA` WHERE id_membre = ? and id_evenement = ? ORDER by date_media ASC LIMIT 5;",
                "ii",
                [$_SESSION["userid"], $eventid]
            );
            foreach($medias as $media => $img):?>
                <img src="api/files/<?php echo trim($img['url_media']);?>" alt="Image Personelle de l'événement">
        <?php endforeach;?>

        <form id="add-media" action="add_media.php" method="post" enctype="multipart/form-data">
            <label for="file-picker">
                <img src="/assets/add_media.png" alt="Ajouter un média">
            </label>
            <input type="hidden" name="eventid" value="<?php echo $eventid?>">
            <input type="hidden" name="userid" value="<?php echo $_SESSION['userid']?>">

            <input type="file" id="file-picker" name="file" accept="image/jpeg, image/png, image/webp" hidden>
            <button type="submit" style="display:none;">Envoyer</button>
        </form>


    </div>
    <?php endif;?>
    <h3>Collection Generale</h3>

    <div class="general-medias">
    </div>

</section>


<?php require_once 'footer.php';?>
<script src="/scripts/open_media.js"></script>
</body>
</html>