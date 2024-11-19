

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public_style.css">
</head>
<body>

<!-- HEADER -->
<?php require_once "header.php" ?>

<form action="" method="post">
    <input type="hidden" name="deconnexion" value="true">
    <button type="submit">Déconnexion</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //
    if($_POST['deconnexion']){
        unset($_SESSION['user']);
        $message = "Vous avez été déconnecté.";
    }
    echo $message;
}

?>

<!-- PARTIE MON COMPTE -->

<H2>MON COMPTE</H2>
<section>
    <div id=carreCompte>

        <div>
            <img src="assets/photo_mathis.png" alt="Photo de groupe du bureau du BDE 2024"/>
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

                <button type="submit">Enregistrer les modifications</button>
        </form>
    </div>

</section>


<!-- FOOTER -->
<?php require_once "footer.php" ?>

</body>
</html>