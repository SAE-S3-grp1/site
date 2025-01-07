<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link rel="stylesheet" href="~inf2pj01/styles/general_style.css">
    <link rel="stylesheet" href="~inf2pj01/styles/header_style.css">
    <link rel="stylesheet" href="~inf2pj01/styles/planner_style.css">
    <link rel="stylesheet" href="~inf2pj01/styles/footer_style.css">
    
</head>
<body class="body_margin">

<!--------------->
<!------PHP------>
<!--------------->

<?php 

// Importer les fichiers
require_once "~inf2pj01/header.php" ;
require_once '~inf2pj01/database.php';
require_once '~inf2pj01/files_save.php';
?>



<!--------------->
<!------HTML----->
<!--------------->

<H1>Agenda</H1>
<div>
    <iframe src="https://edt.gemino.dev">
    </iframe>
</div>


<?php require_once "~inf2pj01/footer.php" ?>
</body>
</html>