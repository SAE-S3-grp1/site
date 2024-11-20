<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title>Accueil</title>
    
    <link rel="stylesheet" href="styles/index_style.css">
    <link rel="stylesheet" href="styles/general_style.css">
    <link rel="stylesheet" href="styles/header_style.css">


</head>
<body id="index" class="body_margin">
    <?php
     require_once 'header.php';
     require_once 'database.php';
     $db = new DB();
    ?>

    <!--H1 A METTRE -->
    <section>
        <h2 class="titre_vertical">ADIIL</h2>
        <div id="index_carrousel">
            <img src="assets/photo_bureau_ADIIL.png" alt="Carrousel ADIIL">
        </div>
    </section>

    <section>
        <div class="paragraphes">
            <p>
                <b class="underline">L'ADIIL</b>, ou l'<b>Association</b> du <b>Département</b> <b>Informatique</b> de l'<b>IUT</b> de <b>Laval</b>, 
                est une organisation étudiante dédiée à créer un environnement propice à l'épanouissement dans le campus. 
                Participer a des évèvements, et plus globalement a la vie du département.
            </p>
            <p>
                L'ADIIL, véritable moteur de la vie étudiante à l'IUT de Laval, 
                offre un cadre propice à l'épanouissement académique et social des étudiants en informatique. 
                En participant à ses événements variés, les étudiants enrichissent leur expérience universitaire,
                tout en renforçant les liens au sein de la communauté.
            </p>
        </div>
        <h2 class="titre_vertical">L'ASSO</h2>
    </section>

    <section>
        <h2 class="titre_vertical">SCORES</h2>

        <div id="podium">
            <?php
                $podium = $db->select(
                    "SELECT prenom_membre, xp_membre, pp_membre FROM MEMBRE ORDER BY xp_membre DESC LIMIT 3;"
                );
            ?>
            <!--Deuxieme-->
            <div>
                <h3>#02</h3>
                <h4><?php echo $podium[1]['prenom_membre'];?></h4>
                <div>
                    <img src="/api/files/<?php echo $podium[1]['pp_membre'];?>" alt="Profile Picture" class="profile_picture">
                    <?php echo $podium[1]['xp_membre'];?> px
                </div>
            </div>
            <!--Premier-->
            <div>
                <h3>#01</h3>
                <h4><?php echo $podium[0]['prenom_membre'];?></h4>
                <div>
                    <img src="/api/files/<?php echo $podium[0]['pp_membre'];?>" alt="Profile Picture" class="profile_picture"> 
                    <?php echo $podium[0]['xp_membre'];?> px
                </div>
            </div>
            <!--Troiseme-->
            <div>
                <h3>#03</h3>
                <h4><?php echo $podium[2]['prenom_membre'];?></h4>
                <div>
                    <img src="/api/files/<?php echo $podium[2]['pp_membre'];?>" alt="Profile Picture" class="profile_picture">
                    <?php echo $podium[2]['xp_membre'];?> px
                </div>
            </div>
        </div>
    </section>

    <section>

    </section>
</body>
</html>
