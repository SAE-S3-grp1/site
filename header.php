<?php
session_start();
$isUserLoggedIn = isset($_SESSION['user']);
$isAdmin = $_SESSION['isAdmin']; // Vérifie si l'utilisateur est connecté
?>

<!-- HEADER -->
<header>
    <a id="accueil" href="./index.php">
        <img src="assets/logo.png" alt="Logo de l'ADIIL">
    </a>
    <nav>
        <ul>
            <li>
                <a href="./evenements.php">Événements</a>
            </li>
            <li>
                <a href="./actualites.php">Actualités</a>
            </li>
            <li>
                <a href="./boutique.php">Boutique</a>
            </li>
            
            <?php if ($isUserLoggedIn): ?>
                <li>
                    <a href="./agenda.php">Agenda</a>
                </li>
            <?php endif; ?>

            <li>
                <a href="./aPropos.php">À propos</a>
            </li>

            <?php if ($isUserLoggedIn): ?>
                <li>
                    <a href="./monCompte.php">Mon compte</a>
                </li>

                <?php if ($isAdmin): ?>
                  <li>
                      <a id="header_admin" href="./adminPanel.php">Panel Admin</a>
                  </li>
                <?php endif; ?>

            <?php else: ?>
                <li>
                    <a href="./connexion.php">Se connecter</a>
                </li>
            <?php endif; ?>

      
        </ul>
    </nav>
</header>
