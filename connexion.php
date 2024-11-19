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
            <input type="password" id="password" name="password">

            <button type="submit">Se connecter</button>
        </form>

        <!-- Gestion de la connexion -->
        <?php

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $mail = htmlspecialchars(trim($_POST['mail']));
                $password = htmlspecialchars(trim($_POST['password']));
                //Injections SQL ? :


                $selection_db = executeSelectQuery(
                    $db,
                    "SELECT id_membre, email_membre, password_membre FROM MEMBRE WHERE email_membre = ?",
                    [$mail]);

                $db_mail = $selection_db[0]["email_membre"];
                
                $db_password = $selection_db[0]["password_membre"];

                $db_id = $selection_db[0]["id_membre"];
    
                $mail_ok = ($db_mail == $mail);

                $password_ok = password_verify($password, $db_password);

                if($mail_ok && $password_ok){
                    $_SESSION['user'] = $mail;
                    
                    //check if perm -> panel admin ok
                    if(executeSelectQuery($db,
                    "SELECT COUNT(*) as nb_roles FROM ASSIGNATION WHERE id_membre = ? ;",
                    [$db_id])[0]["nb_roles"] > 0){
                        $_SESSION["isAdmin"] = true;
                    }

                    $db->close();

                    header("Location: index.php");
                    exit;
                }else{
                    echo "<h3 class=\"login-form\"> Erreur dans les identifiants. </h3>";
                }


            }
        ?>
    </body>
</html>