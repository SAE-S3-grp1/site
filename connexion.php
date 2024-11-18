<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public_style.css">
</head>
    <body>
        <p>JDBFJDSBFJBSJDFDJSVFJSVJDVF</p>
        <?php 
            require_once 'header.php';
            require_once 'database.php';
        ?>

        <!-- Formulaire de connexion -->
        <form method="POST" action="" class="login-form">
            <h1>Connexion</h1>
            <label for="mail">Nom d'utilisateur :</label>
            <input type="email" id="mail" name="mail" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Se connecter</button>
        </form>

        <!-- Gestion de la connexion -->
        <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $mail = htmlspecialchars(trim($_POST['mail']));
                $password = password_hash(htmlspecialchars(trim($_POST['password'])), PASSWORD_DEFAULT);

                //Injections SQL ? :
                $loginOk = !empty(
                    executeSelectQuery(
                        $db,
                        "SELECT email_membre from MEMBRE where email_membre = ?",
                        [$mail]
                    )) && !empty(
                        $db,
                        "SELECT password_membre from MEMBRE where password_membre = ?",
                        [$password]
                    )

                if($loginOk){
                    $_SESSION['user'] = $mail;
                    $_SESSION['password'] = $password;
                    echo 'Connection OK'
                }
                // header("Location: index.php");
                // exit;
            }

        ?>
    </body>
</html>