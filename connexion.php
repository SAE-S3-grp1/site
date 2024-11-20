<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="stylesheet" href="public_style.css">
</head>
    <body>
        <?php 
            require_once 'header.php';
            require_once 'database.php';
            $db = new DB();
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


                $selection_db = $db->select(
                    "SELECT id_membre, email_membre, password_membre FROM MEMBRE WHERE email_membre = ?",
                    "s",
                    [$mail]
                );

                $db_mail = $selection_db[0]["email_membre"];
                
                $db_password = $selection_db[0]["password_membre"];
    
                $mail_ok = ($db_mail == $mail);

                $password_ok = password_verify($password, $db_password);

                if($mail_ok && $password_ok){

                    $_SESSION['userid'] = $selection_db[0]["id_membre"];

                    //check if perm -> panel admin ok
                    if($db->select(
                        "SELECT COUNT(*) as nb_roles FROM ASSIGNATION WHERE id_membre = ? ;",
                        "i",
                        [$_SESSION['id']])[0]["nb_roles"] > 0){
                        
                            $_SESSION["isAdmin"] = true;
                    }

                    header("Location: index.php");
                    exit;
                }else{
                    echo "<h3 class=\"login-form\"> Erreur dans les identifiants. </h3>";
                }
            }
        ?>
    </body>
</html>