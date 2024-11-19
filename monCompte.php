

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public_style.css">
</head>
<body class="body_margin">

<?php require_once "header.php" ?>

<form action="" method="post">
    <input type="hidden" name="deconnexion" value="true">
    <button type="submit">Déconnexion</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //
    if($_POST['deconnexion']){
        unset($_SESSION['userid']);
        $message = "Vous avez été déconnecté.";
    }
    echo $message;
}

?>

<!-- PARTIE MON COMPTE -->

<H2>MON COMPTE</H2>
<section>
    <div id=carreCompte>
        
        <div id="account-info">
                <div id="cadre-photo">
                    <img src="assets/photo_mathis.png" alt="Photo de profil de l'utilisateur"/>
                </div>
                <p>210 XP</p>
                <div>
                    <p>Grade diamant</p>
                    
                </div>
                <button type="button"> <img src="assets/logo_discord.png" alt="Logo de Discord"/>Associer à Discord</button>
        </div>

        <form method="POST" action="" id="account-form">

            <div>
                <label for="user_name">Prénom :</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div>
                <label for="user_lastName">Nom :</label>
                <input type="text" id="lastName" name="lastName" required>
            </div>

            <div>
                <label for="user_mail">Adresse mail :</label>
                <input type="email" id="mail" name="mail" required>
            </div>

            <div>
                <label for="tp">Groupe TP : </label>
                <select id="tp" name="tp">
                    <option value="11A">TP 11 A</option>
                    <option value="11B">TP 11 B</option>
                    <option value="12C">TP 12 C</option>
                    <option value="12D">TP 12 D</option>
                    <option value="21A">TP 21 A</option>
                    <option value="21B">TP 21 B</option>
                    <option value="22C">TP 22 C</option>
                    <option value="22D">TP 22 D</option>
                    <option value="31A">TP 31 A</option>
                    <option value="31B">TP 31 B</option>
                    <option value="32C">TP 32 C</option>
                    <option value="32D">TP 32 D</option>
                </select>
            </div>

                <button type="submit">Enregistrer les modifications</button>
        </form>


    </div>

</section>


<!-- FOOTER -->
<?php require_once "footer.php" ?>

</body>
</html>