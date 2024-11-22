<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link rel="stylesheet" href="styles/general_style.css">
    <link rel="stylesheet" href="styles/header_style.css">
    <link rel="stylesheet" href="styles/account_style.css">
    <link rel="stylesheet" href="styles/header_style.css">
    <link rel="stylesheet" href="styles/footer_style.css">

</head>

<body class="body_margin">
<?php require_once "header.php" ?>

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['deconnexion']) && $_POST['deconnexion'] === 'true') {
            unset($_SESSION['userid']);
            unset($_SESSION['isAdmin']);
            header("Location: index.php"); 
            exit();
        }
    }
?>


<!-- PARTIE MON COMPTE -->
<H2>MON COMPTE</H2>
<section> <!-- Ensemble des différents formulaires du compte -->
    <div id="account-generalInfo">
        <div>
            <div id="cadre-pp">
                <img src="assets/photo_mathis.png" alt="Photo de profil de l'utilisateur"/>
            </div>
            <button type="submit"><img src="assets/edit_logo.png" alt="Logo editer la photo de profil"/></button>
        </div>
        <div>
            <p>210</p>
            <p>XP</p>
        </div>
        <div>
            <p>Grade diamant</p>
            <div id="cadre-grade">
                <img src="assets/grade_diamant.png" alt="Illustration du grade diamant"/>
            </div>
        </div>
    </div>

    <form method="POST" action="" id="account-personalInfo-form">
        <div>
            <div>
                <input type="text" id="name" name="name" placeholder="Prénom"required>
                <input type="text" id="lastName" name="lastName" placeholder="Nom de famille"required>
            </div>
            <div>
                <input type="email" id="mail" name="mail" placeholder="Adresse mail" required>
                
                <select id="tp" name="tp">
                    <option value="11A">TP 11 A</option>
                    <option value="11B">TP 11 B</option>
                    <option value="12C">TP 12 C</option>
                    <option value="12D">TP 12 D</option>
                    <option value="21A">TP 21 A</option>
                    <option value="21B" selected>TP 21 B</option>  <!-- Cette option sera sélectionnée par défaut -->
                    <option value="22C">TP 22 C</option>
                    <option value="22D">TP 22 D</option>
                    <option value="31A">TP 31 A</option>
                    <option value="31B">TP 31 B</option>
                    <option value="32C">TP 32 C</option>
                    <option value="32D">TP 32 D</option>
                </select>
            </div>
        </div>

        <button type="submit"><img src="assets/save_logo.png" alt="Logo editer la photo de profil"/></button>
    </form>
    
    <form method="POST" action="" id="account-editPass-form">
        <div>
            <div>
                <p>Modifier mon mot de passe :</p>
                <input type="password" id="mdp" name="mdp" placeholder="Mot de passe actuel" required>
            </div>
            <div>
                <input type="password" id="newMdp" name="newMdp" placeholder="Nouveau mot de passe" required>
                <input type="password" id="newMdpVerif" name="newMdpVerif" placeholder="Confirmation du nouveau mot de passe" required>
            </div>
        </div>

        <button type="submit"><img src="assets/save_logo.png" alt="Logo editer la photo de profil"/></button>
    </form>

<section> <!-- Ensemble des différents boutons du compte -->
    <div id="buttons-section">
        <!--Discord-->
        <button type="button">
            <a href="https://discord.com" target="_blank">
                <img src="assets/logo_discord.png" alt="Logo de Discord">
                Associer mon compte à Discord
            </a>
        </button>

        <!--Deconnexion-->
        <form action="" method="post">
            <input type="hidden" name="deconnexion" value="true">
            <button type="submit">
                    <img src="assets/logOut_icon.png" alt="icone de deconnexion">
                    Déconnexion
            </button>
        </form>

        <!--Supprimer son compte-->
        <button type="button">
            <a href="supprimer_compte.php" target="_blank">
                <img src="assets/delete_icon.png" alt="icone de suppression">
                Supprimer mon compte
            </a>
        </button>
    </div>



<!-- FOOTER -->
<?php require_once "footer.php" ?>

</body>
</html>