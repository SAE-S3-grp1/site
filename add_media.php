<?php
require_once '~inf2pj01/files_save.php';
require_once '~inf2pj01/database.php';
$db = new DB();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'], $_POST['userid'], $_POST['eventid'])) {
        $fileName = saveImage();
        
        $date = new DateTime();
        $sqlDate = $date->format('Y-m-d H:i:s');

        if ($fileName !== null) {
            // Met à jour la base de données avec le nom du fichier
            $db->query(
                "INSERT INTO MEDIA VALUES (NULL, ?, ?, ?, ?);",
                "ssii",
                [$fileName, $sqlDate ,$_POST["userid"], $_POST["eventid"]]
            );
        }

        // Recharge la page pour afficher la nouvelle image
        header("Location: ~inf2pj01/my_gallery.php?eventid=".$_POST["eventid"]);
        exit();

    }else{
        header("Location: ~inf2pj01/index.php");
        exit();
    }

?>