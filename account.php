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
    <link rel="stylesheet" href="styles/footer_style.css">

</head>

<body class="body_margin">

<?php 
require_once "header.php" ;
require_once 'database.php';

$db = new DB();
$isLoggedIn = isset($_SESSION["userid"]);
?>


<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['deconnexion']) && $_POST['deconnexion'] === 'true') {
            session_destroy();
            header("Location: index.php"); 
            exit();
        }
    }
?>


<!-- PARTIE MON COMPTE -->
<?php
    $infoUser = $db->select("SELECT pp_membre, xp_membre, prenom_membre, nom_membre, email_membre, tp_membre, discord_token_membre, nom_grade, image_grade FROM MEMBRE LEFT JOIN ADHESION ON MEMBRE.id_membre = ADHESION.id_membre LEFT JOIN GRADE ON ADHESION.id_grade = GRADE.id_grade WHERE MEMBRE.id_membre = ?;",
    "i",
    [$_SESSION['userid']]);
?>


<H2>MON COMPTE</H2>
<section> <!-- Ensemble des différents formulaires du compte -->
    <div id="account-generalInfo">
        <div>
            <div id="cadre-pp">

            <?php
            ?>
                <img src="/api/files/<?php echo $infoUser[0]['pp_membre'];?>" alt="Photo de profil de l'utilisateur"/>
            </div>
            <button type="submit"><img src=assets/edit_logo.png alt="Logo editer la photo de profil"/></button>
        </div>
        <div>
            <p><?php echo $infoUser[0]['xp_membre'];?></p>
            <p>XP</p>
        </div>
        <div>
            <?php if (empty($infoUser[0]['nom_grade'])): ?>
            <p>Vous n'avez pas de grade</p>
            <?php else: ?>
            <p><?php echo $infoUser[0]['nom_grade']; ?></p>
            <div id="cadre-grade">
                <img src="/api/files/<?php echo $infoUser[0]['image_grade']; ?>" alt="Illustration du grade de l'utilisateur"/>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <form method="POST" action="" id="account-personalInfo-form">
        <div>
            <div>
                <input type="text" id="name" name="name" placeholder="Prénom" value="<?php echo $infoUser[0]['prenom_membre']; ?>" required>
                <input type="text" id="lastName" name="lastName" placeholder="Nom de famille" value="<?php echo $infoUser[0]['nom_membre']; ?>" required>
            </div>
            <div>
                <input type="email" id="mail" name="mail" placeholder="Adresse mail" value="<?php echo $infoUser[0]['email_membre']; ?>"required>
                
                <?php if (!is_null($infoUser[0]['tp_membre'])): ?>
                <select id="tp" name="tp">
                    <option value="11A" <?php echo $infoUser[0]['tp_membre'] === '11a' ? 'selected' : ''; ?>>TP 11 A</option>
                    <option value="11B" <?php echo $infoUser[0]['tp_membre'] === '11b' ? 'selected' : ''; ?>>TP 11 B</option>
                    <option value="12C" <?php echo $infoUser[0]['tp_membre'] === '12c' ? 'selected' : ''; ?>>TP 12 C</option>
                    <option value="12D" <?php echo $infoUser[0]['tp_membre'] === '12d' ? 'selected' : ''; ?>>TP 12 D</option>
                    <option value="21A" <?php echo $infoUser[0]['tp_membre'] === '21a' ? 'selected' : ''; ?>>TP 21 A</option>
                    <option value="21B" <?php echo $infoUser[0]['tp_membre'] === '21b' ? 'selected' : ''; ?>>TP 21 B</option>
                    <option value="22C" <?php echo $infoUser[0]['tp_membre'] === '22c' ? 'selected' : ''; ?>>TP 22 C</option>
                    <option value="22D" <?php echo $infoUser[0]['tp_membre'] === '22d' ? 'selected' : ''; ?>>TP 22 D</option>
                    <option value="31A" <?php echo $infoUser[0]['tp_membre'] === '31a' ? 'selected' : ''; ?>>TP 31 A</option>
                    <option value="31B" <?php echo $infoUser[0]['tp_membre'] === '31b' ? 'selected' : ''; ?>>TP 31 B</option>
                    <option value="32C" <?php echo $infoUser[0]['tp_membre'] === '32c' ? 'selected' : ''; ?>>TP 32 C</option>
                    <option value="32D" <?php echo $infoUser[0]['tp_membre'] === '32d' ? 'selected' : ''; ?>>TP 32 D</option>
                </select>
                <?php endif; ?>
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
</section>

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

        <form action="delete_account.php" method="post">
            <input type="hidden" name="delete_account" value="true">
            <button type="submit">
                <img src="assets/delete_icon.png" alt="icone de suppression">
                Supprimer mon compte
            </button>
        </form>
    </div>
</section>



<!-- PARTIE MES COMMANDES -->
<H2>MES COMMANDES</H2>
<section>
</section>



<!-- FOOTER -->
<?php require_once "footer.php" ?>
</body>
</html>