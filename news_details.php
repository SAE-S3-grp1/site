<!DOCTYPE html>
<html lang="fr">
<?php 
        require_once 'database.php';
        $db = new DB();

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $eventid = $_GET['id'];
            $event = $db->select(
                "SELECT *
                FROM ACTUALITE WHERE id_actualite = ?",
                "i",
                [$eventid]
            );
            if(empty($event) || is_null($event)){
                header("Location: /~inf2pj01/index.php");
                exit;
            }
            $event = $event[0];
        }else{
            header("Location: /~inf2pj01/index.php");
            exit;
        }
        if($event['image_actualite'] == null){
            $event['image_actualite'] = 'default-event.png';
        }
    ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title><?php echo $event['titre_actualite']?></title>

    <link rel="stylesheet" href="/~inf2pj01/styles/general_style.css">
    <link rel="stylesheet" href="/~inf2pj01/styles/header_style.css">
    <link rel="stylesheet" href="/~inf2pj01/styles/footer_style.css">

    <link rel="stylesheet" href="/~inf2pj01/styles/event_details_style.css">



</head>

<body>
    <?php
    require_once 'header.php';
    $isLoggedIn = isset($_SESSION["userid"]);
?>
    <section class="event-details">
        <img src="/~inf2pj01/api/files/<?php echo $event['image_actualite']; ?>" alt="Image de l'actualité">

        <h1><?php echo strtoupper($event['titre_actualite']); ?></h1>

        <div>
            <h2>
                <?php
                    $current_date = new DateTime(date("Y-m-d"));
                    $event_date = new DateTime(substr($event['date_actualite'], 0, 10));
                    echo date('d/m/Y', strtotime($event['date_actualite']));
                ?>
            </h2>
        </div>

        <p>
            <?php echo nl2br(htmlspecialchars($event['contenu_actualite'])); ?>
        </p>

    </section>


    <?php require_once 'footer.php';?>
</body>

</html>