<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title>Evenements</title>
    
    <link rel="stylesheet" href="styles/general_style.css">
    <link rel="stylesheet" href="styles/header_style.css">
    <link rel="stylesheet" href="styles/footer_style.css">

    <link rel="stylesheet" href="styles/events_style.css">

</head>
<body>
<?php
    require_once 'header.php';
    require_once 'database.php';
    $db = new DB();
?>
<h1>LES EVENEMENTS</h1>
<section>
    <div class="events-display">
                <?php
                    $date = getdate();
                    $sql_date = $date["year"]."-".$date["mon"]."-".$date["mday"];
                    $events_to_display = $db->select(
                        "SELECT id_evenement, nom_evenement, lieu_evenement, date_evenement FROM EVENEMENT WHERE date_evenement >= ? ORDER BY date_evenement ASC;",
                        "s",
                        [$sql_date]
                    );
                    $passed_events = $db->select(
                        "SELECT id_evenement, nom_evenement, lieu_evenement, date_evenement FROM EVENEMENT WHERE date_evenement < ? ORDER BY date_evenement ASC LIMIT 2;",
                        "s",
                        [$sql_date]
                    );
                    $events_to_display = array_merge($passed_events, $events_to_display);
                    $current_date = new DateTime(date("Y-m-d"));

                foreach ($events_to_display as $event):
                    $eventid = $event["id_evenement"];

                    $event_date = substr($event['date_evenement'], 0, 10);
                    $event_date_info = getdate(strtotime($event_date));

                    $event_date = new DateTime($event_date);
                    $other_classes = "";
                    $isPassed = false;
                    
                    if ($event_date < $current_date) {
                        $date_pin_class = "passed";
                        $date_pin_label = "Passé";
                        $other_classes = 'passed';
                        $isPassed = true;
                    } elseif ($event_date == $current_date) {
                        $date_pin_class = "today";
                        $date_pin_label = "Aujourd'hui";
                    }else{
                        $date_pin_class = "upcoming";
                        $date_pin_label = "A venir";
                    }
                    ?>
                    <div class="event-box <?php echo "$other_classes";?>">

                        <div class="timeline-event">
                            <p> <?php echo "$date_pin_label";?></p>
                            <div class="timeline-marker <?php echo " $date_pin_class" ?>">
                                <div class="time-line"></div>
                            </div>
                        </div>

                        <div class="event" event-id=" <?php echo $eventid;?>">
                            <div>
                                <h2><?php echo $event['nom_evenement'];?></h2>
                                <?php
                                    $moisFr = [
                                        'January'   => 'Janvier',
                                        'February'  => 'Février',
                                        'March'     => 'Mars',
                                        'April'     => 'Avril',
                                        'May'       => 'Mai',
                                        'June'      => 'Juin',
                                        'July'      => 'Juillet',
                                        'August'    => 'Août',
                                        'September' => 'Septembre',
                                        'October'   => 'Octobre',
                                        'November'  => 'Novembre',
                                        'December'  => 'Décembre'
                                    ];

                                    echo ucwords($event_date_info["mday"]." ".$moisFr[$event_date_info['month']].", ".$event["lieu_evenement"]);
                                ?>
                            </div>

                            <h4
                                <?php
                                $isPlaceDisponible = $db->select(
                                    "SELECT (EVENEMENT.places_evenement - (SELECT COUNT(*) FROM INSCRIPTION WHERE INSCRIPTION.id_evenement = EVENEMENT.id_evenement)) > 0 AS isPlaceDisponible FROM EVENEMENT WHERE EVENEMENT.id_evenement = ? ;",
                                    "i",
                                    [$eventid])[0]['isPlaceDisponible'];
                                
                                if($isPlaceDisponible){
                                    //editable
                                    $event_subscription_color_class = "event-not-subscribed hover_effect";
                                    $event_subscription_label = "S'inscrire";
                                }else{
                                    //editable
                                    $event_subscription_color_class = "event-full";
                                    $event_subscription_label = "Complet";
                                }

                                if($isLoggedIn){
                                    $isSubscribed = !empty($db->select(
                                    "SELECT MEMBRE.id_membre FROM MEMBRE JOIN INSCRIPTION on MEMBRE.id_membre = INSCRIPTION.id_membre WHERE MEMBRE.id_membre = ? AND INSCRIPTION.id_evenement = ? ;",
                                    "ii",
                                    [$_SESSION['userid'], $event["id_evenement"]]
                                    ));
                                    
                                    if($isSubscribed){
                                        //editable
                                        $event_subscription_color_class = "event-subscribed";
                                        $event_subscription_label = "Inscrit";
                                    }
                                }
                                
                                if($isPassed){
                                    $event_subscription_color_class = "event-full";
                                    $event_subscription_label = "Passé";
                                }
                                echo "class=\"$event_subscription_color_class\"";
                                ?>>
                                <?php echo $event_subscription_label;?>

                            </h4>
                        </div>
                        <div class="vertical-line"></div>
                    </div>
                <?php endforeach; ?>
            </div>
    </section>

    <?php require_once 'footer.php';?>
</body>
</html>