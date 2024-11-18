<?php
session_start();
$isUserLoggedIn = isset($_SESSION['user']); // Vérifie si l'utilisateur est connecté
?>

<!-- HEADER -->
<header>
    <a id="accueil" href="./index.html">
        <img src="assets/logo.png" alt="Logo de l'ADIIL">
    </a>
    <nav>
        <ul>
            <li>
                <a href="./evenements.html">Événements</a>
            </li>
            <li>
                <a href="./actualites.html">Actualités</a>
            </li>
            <li>
                <a href="./boutique.html">Boutique</a>
            </li>
            <?php if ($isUserLoggedIn): ?>
                <li>
                    <a href="./agenda.html">Agenda</a>
                </li>
            <?php endif; ?>
            <li>
                <a href="./aPropos.html">À propos</a>
            </li>
            <li>
                <a href="./monCompte.html">Mon compte</a>
            </li>
        </ul>
    </nav>
</header>
