<link rel="shortcut icon" href="/~inf2pj01/admin/ressources/favicon.png" type="image/x-icon">

<?php
    @session_start();
    $isUserLoggedIn = isset($_SESSION['userid']);
    $isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] ;
?>


<!-- HEADER -->
<header>
    <a id="accueil" href="/~inf2pj01/index.php">
        <img src="/~inf2pj01/assets/logo.png" alt="Logo de l'ADIIL">
    </a>
    <nav>
        <ul>
            <li>
                <a href="/~inf2pj01/events.php">Événements</a>
            </li>
            <li>
                <a href="/~inf2pj01/news.php">Actualités</a>
            </li>
            <li>
                <a href="/~inf2pj01/shop.php">Boutique</a>
            </li>
            <li>
                <a href="/~inf2pj01/grade.php">Grades</a>
            </li>
            
            <?php if ($isUserLoggedIn): ?>
                <li>
                    <a href="/~inf2pj01/agenda.php">Agenda</a>
                </li>
            <?php endif; ?>

            <li>
                <a href="/~inf2pj01/about.php">À propos</a>
            </li>

            <?php if ($isUserLoggedIn): ?>
                <li>
                    <a href="/~inf2pj01/account.php">Mon compte</a>
                </li>

                <?php if ($isAdmin): ?>
                  <li>
                      <a id="header_admin" href="/~inf2pj01/admin/admin.php">Panel Admin</a>
                  </li>
                <?php endif; ?>

            <?php else: ?>
                <li>
                    <a href="/~inf2pj01/login.php">Se connecter</a>
                </li>
            <?php endif; ?>

      
        </ul>
    </nav>
</header>
