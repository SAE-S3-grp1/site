<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link rel="stylesheet" href="styles/general_style.css">
    <link rel="stylesheet" href="styles/account_style.css">
    <link rel="stylesheet" href="styles/header_style.css">

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
<section> <!-- Ensemble des différents formulaires du compte -->
    <div id="account-generalInfo">
        <form method="POST" action="" id="account-profilPhoto-form">
            <div id="cadre-photo">
                <img src="assets/photo_mathis.png" alt="Photo de profil de l'utilisateur"/>
            </div>
        </form>
        <div>
            <p>210</p>
            <p>XP</p>
        </div>
        <div>
            <p>Grade diamant</p>
            <img src="assets/grade_diamant.png" alt="Illustration du grade diamant"/>
        </div>
    </div>

    <form method="POST" action="" id="account-personalInfo-form">
        <input type="text" id="name" name="name" placeholder="Prénom"required>

        <input type="text" id="lastName" name="lastName" placeholder="Nom de famille"required>

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
    
    <form method="POST" action="" id="account-loginInfo-form">
        <input type="email" id="mail" name="mail" placeholder="Adresse mail" required>

        <div>
            <input type="password" id="mdp" name="mdp" placeholder="Mot de passe actuel" required>
            <input type="password" id="newMdp" name="newMdp" placeholder="Nouveau mot de passe" required>
            <input type="password" id="newMdp" name="newMdp" placeholder="Confirmation du nouveau mot de passe" required>
        </div>
    </div>

<section> <!-- Ensemble des différents boutons du compte -->
    
    <!--Discord-->
    <button type="button">
        <a href="https://discord.com" target="_blank">
            <img src="assets/logo_discord.png" alt="Logo de Discord">
            Associer à Discord
        </a>
    </button>

    <!--Deconnexion-->
    <form action="" method="post">
    <input type="hidden" name="deconnexion" value="true">
    <button type="submit">Déconnexion</button>
    </form>

    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if($_POST['deconnexion']){
        unset($_SESSION['userid']);
        $message = "Vous avez été déconnecté.";
        }
        echo $message;
    }
    ?>

    <!--Supprimer son compte-->
    <button type="button">
        <a href="supprimer_compte.php" target="_blank">
            Supprimer mon compte
        </a>
    </button>










<!--
                <button type="button">
                    <a href="https://discord.com" target="_blank">
                    <img src="assets/logo_discord.png" alt="Logo de Discord">
                    Associer à Discord
                    </a>
                </button>
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
-->

<!-- FOOTER -->
<?php require_once "footer.php" ?>

</body>
</html>