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
     $isLoggedIn = isset($_SESSION["userid"]);
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
               //TODO FACTORISATION POSSIBLE (FOR)
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
            <div class="events-display">
                <?php
                    $date = getdate();
                    $sql_date = $date["year"]."-".$date["mon"]."-".$date["mday"];
                    $events_to_display = $db->select(
                        "SELECT id_evenement, nom_evenement, lieu_evenement, date_evenement FROM EVENEMENT WHERE date_evenement > ? ORDER BY date_evenement ASC LIMIT 2;",
                        "s",
                        [$sql_date]
                    );

                foreach ($events_to_display as $event):?>
                    <div>
                        <div>
                            <h2><?php echo $event['nom_evenement'];?></h2>
                            <?php
                                setlocale(LC_TIME, 'fr_FR.UTF-8');
                                $date = substr($event['date_evenement'], 0, 10);
                                $date = new DateTime($date);
                                
                                echo ucwords(strftime('%d %B', $date->getTimestamp()).", ".$event["lieu_evenement"]);
                            ?>
                        </div>

                        <h4
                            <?php
                            $eventid = $event["id_evenement"];

                            $isPlaceDisponible = $db->select(
                                "SELECT (EVENEMENT.places_evenement - (SELECT COUNT(*) FROM INSCRIPTION WHERE INSCRIPTION.id_evenement = EVENEMENT.id_evenement)) > 0 AS isPlaceDisponible FROM EVENEMENT WHERE EVENEMENT.id_evenement = ? ;",
                                "i",
                                [$eventid])[0]['isPlaceDisponible'];
                            
                            if($isPlaceDisponible){

                                $event_subscription_color_class = "event-not-subscribed hover_effect";
                                $event_subscription_label = "<a href=\"event_details.php?id=$eventid\">S'inscrire</a>";

                                if($isLoggedIn){
                                    
                                    $isSubscribed = !empty($db->select(
                                    "SELECT MEMBRE.id_membre FROM MEMBRE JOIN INSCRIPTION on MEMBRE.id_membre = INSCRIPTION.id_membre WHERE MEMBRE.id_membre = ? AND INSCRIPTION.id_evenement = ? ;",
                                    "ii",
                                    [$_SESSION['userid'], $event["id_evenement"]]
                                    ));
                                    
                                    if($isSubscribed){
                                        $event_subscription_color_class = "event-subscribed";
                                        $event_subscription_label = "Inscrit";
                                    }
                                }

                            }else{
                                $event_subscription_color_class = "event-full";
                                $event_subscription_label = "Plein";
                            }
                            echo "class=\"$event_subscription_color_class\"";
                            ?>>
                            <?php echo $event_subscription_label;?>

                        </h4>

                    </div>
                    <hr>
                <?php endforeach; ?>
                <h3><a href="events.php" class="hover_effect">Voir tous les événements</a></h3>

            </div>
            <h2 class="titre_vertical">EVENTS</h2>

    </section>
</body>
</html>
