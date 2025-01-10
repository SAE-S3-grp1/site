<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="/~inf2pj01/styles/event_subscription_style.css">

    <link rel="stylesheet" href="/~inf2pj01/styles/general_style.css">
    <link rel="stylesheet" href="/~inf2pj01/styles/header_style.css">
    <link rel="stylesheet" href="/~inf2pj01/styles/footer_style.css">
</head>
<body class="body_margin">



<!--------------->
<!------PHP------>
<!--------------->

<?php

// Importer les fichiers
require_once "header.php";
require_once 'database.php';
require_once 'files_save.php';


// Vérifie si l'utilisateur est connecté
$isLoggedIn = isset($_SESSION["userid"]);
if (!$isLoggedIn) {
    header("Location: /~inf2pj01/login.php");
    exit;
}

$userid = $_SESSION["userid"];

// Vérifie que la requête est POST et contient les données nécessaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        header("Location: /~inf2pj01/events.php");
        exit;
    }
    elseif(isset($_POST["eventid"])){
            $event = $db->select(
                "SELECT nom_evenement, xp_evenement, prix_evenement, reductions_evenement FROM EVENEMENT WHERE id_evenement = ? ;",
                "i",
                [$eventid]
            );
            if(empty($event)){
                header("Location: /~inf2pj01/index.php");
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
                }else{
                    $user_reduction = 1;
                }
            }
        }else{
            header("Location: /~inf2pj01/login.php");
            exit;
        }
    }else{
        header("Location: /~inf2pj01/login.php");
        exit;
    }
?>




<!--------------->
<!------HTML----->
<!--------------->

    <h1>INSCRIPTION</h1>

    <div>
        <button id="cart-button">
            <a href="/~inf2pj01/event_details.php?id=<?php echo $eventid?>">
                <img src="/~inf2pj01/assets/fleche_retour.png" alt="Flèche de retour">
                Retourner à l'évènement
            </a>
        </button>
    </div>

    <div>
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
                    <tr>
                        <td><?php echo strtoupper(htmlspecialchars($title)); ?></td>
                        <td>1</td>
                        <td><?= number_format($price, 2, ',', ' ') ?> €</td>
                        <td><?= number_format($price, 2, ',', ' ') ?> €</td>
                    </tr>
                </tbody>
            </table>

            <h3>Total &nbsp : &nbsp <?= number_format($price, 2, ',', ' ') ?> €</h3>
            <?php         var_dump($price);
            var_dump($user_reduction);?>
                        <h3>Total après réductions &nbsp : &nbsp <?= number_format($price*$user_reduction, 2, ',', ' ') ?> €</h3>
                   
        </div>

        <div>    
            <h3>Paiement</h3>

            <label for="mode_paiement">Mode de Paiement :</label>
            <select id="mode_paiement" name="mode_paiement" required>
                <option value="carte_credit">Carte de Crédit</option>
                <option value="paypal">PayPal</option>
            </select><br><br>
            <div id="carte_credit" class="mode_paiement_fields">
                <form method="POST" action="/~inf2pj01/event_subscription.php">
                    <input type="hidden" name="eventid" value="<?php echo $eventid; ?>">
                    <input type="hidden" name="price" value="<?php echo $price*$user_reduction; ?>">
                    <input type="hidden" name="mode_paiement" value="carte_credit">

                    <label for="numero_carte">Numéro de Carte :</label>
                    <input type="text" id="numero_carte" name="numero_carte" placeholder="XXXX XXXX XXXX XXXX" required><br><br>

                    <label for="expiration">Date d'Expiration :</label>
                    <input type="text" id="expiration" name="expiration" placeholder="MM/AA" required><br><br>

                    <label for="cvv">CVV :</label>
                    <input type="text" id="cvv" name="cvv" placeholder="XXX" required><br><br>

                    <button type="submit" id="finalise-order-button">Valider la commande</button>
                </form>
            </div>
            <div id="paypal" class="mode_paiement_fields" style="display: none;">
                <form method="POST" action="/~inf2pj01/event_subscription.php">
                    <input type="hidden" name="eventid" value="<?php echo $eventid; ?>">
                    <input type="hidden" name="price" value="<?php echo $price*$user_reduction; ?>">
                    <input type="hidden" name="mode_paiement" value="paypal">

                    <button type="button" id="paypal-button">Se connecter à PayPal</button><br><br>
                        
                    <button type="submit" id="finalise-order-button">Valider la commande</button>
                </form>
            </div>
        </div>
    </div>

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
