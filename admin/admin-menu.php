<nav class="navMenu">
    <ul class="ulMenu">
        <li><a href="../index.php">Accueil</a></li>
        <li><a href="../reglement.php">Règlement</a></li>
        <li><a href="../combat.php">Combat</a></li>
        <li><a href="../map.php">Map</a></li>
        <li><a href="../faq.php">FAQ</a></li>
        <li><a href="../classement.php">Classement</a></li>
        <li><a href="../credit.php">Crédit</a></li>
        <?php
            if($Joueur1->getPermAdmin()){
                ?>
                    <li><a href="statistique.php">Statistique</a></li>
                    <li><a href="index.php">Administration</a>
                        <ul class="ulSousMenu">
                            <li><a href="admin-map.php">Gestion Map</a></li>
                            <li><a href="admin-Monsters.php">Gestion Monstre</a></li>
                            <li><a href="admin-objet.php">Gestion Objet</a></li>
                            <li><a href="admin-perso.php">Gestion Personnage</a></li>
                            <li><a href="admin-user.php">Gestion Utilisateur</a></li>
                        </ul>
                    </li>
                <?php
            }
        ?>
    </ul>
</nav>