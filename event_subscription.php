<?php
session_start();
$isLoggedIn = isset($_SESSION["userid"]);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isLoggedIn) {

    $userid = $_SESSION["userid"];
    $eventid = $_POST["eventid"];

    require_once 'database.php';
    $db = new DB();
    if(isset($_POST["price"], $_POST["eventid"])){
        $db->query(
            "INSERT INTO `INSCRIPTION` (`id_membre`, `id_evenement`, `date_inscription`, `paiement_inscription`, `prix_inscription`)
            VALUES (?, ?, NOW(), 'WEB', ?);",
            "iid",
            [$userid, $eventid, $_POST["price"]]
        );
        $xp = $db->select("SELECT xp_evenement FROM EVENEMENT WHERE id_evenement = ?", "i", [$eventid])[0]['xp_evenement'];
        $db->query(
            "UPDATE MEMBRE SET MEMBRE.xp_membre = MEMBRE.xp_membre + ? where MEMBRE.id_membre = ?;",
            "ii",
            [$xp, $userid]
        );
        header("Location: events.php");
        exit;
    }
    elseif(isset($_POST["eventid"])){
            $event = $db->select(
                "SELECT nom_evenement, xp_evenement, prix_evenement, reductions_evenement FROM EVENEMENT WHERE id_evenement = ? ;",
                "i",
                [$eventid]
            );
            if(empty($event)){
                header("Location: index.php");
                exit;
            }
            $event = $event[0];
            $title = $event["nom_evenement"];
            $xp = $event["xp_evenement"];
            $price = $event["prix_evenement"];

            $isDiscounted = boolval($event["reductions_evenement"]);
            $user_reduction = 1;

            if($isDiscounted){
                $user_reduction = $db->select(
                    "SELECT reduction_grade FROM ADHESION 
                    JOIN GRADE ON ADHESION.id_grade = GRADE.id_grade
                    WHERE id_membre = ? AND reduction_grade > 0 order by ADHESION.date_adhesion DESC LIMIT 1",
                    "i",
                    [$userid]
                );
                if(!empty($user_reduction)){
                    $user_reduction = 1 - ($user_reduction[0]["reduction_grade"]/100);
                }
            }
            $price = $price*$user_reduction;
        }else{
            header("Location: login.php");
            exit;
        }
    }else{
        header("Location: login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription à <?php echo $title?></title>
    <link rel="stylesheet" href="styles/event_subscription_style.css">
</head>
<body>
    <div>
        <h1><?php echo strtoupper($title)?></h1>
        <h2><?php echo $price." €"?></h2>
        <br>
        <p>Page de présentation : Les éléments de payement sont pûrement décoratifs.</p>
    </div>
   

    
    <form method="POST" action="event_subscription.php">
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

        <input type="hidden" name="eventid" value="<?php echo $eventid?>">
        <input type="hidden" name="price" value="<?php echo $price?>">
        <!-- Logique pour verifier la validité avec banque / paypal (normalement) -->
        <button type="submit">Valider l'Inscription</button>
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
</body>
</html>
