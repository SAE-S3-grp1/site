<form action="" method="post">
    <input type="hidden" name="deconnexion" value="true">
    <button type="submit">Déconnexion</button>
</form>

<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //
    if($_POST['deconnexion']){
        unset($_SESSION['user']);
        $message = "Vous avez été déconnecté.";
    }
    echo $message;
}

?>
