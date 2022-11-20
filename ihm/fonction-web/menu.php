<nav class="navMenu">
    <ul class="ulMenu">                
        <li><a href="index.php">Accueil</a></li>
        <!--<li><a href="reglement.php">Règlement</a></li>-->
        <li><a href="combat.php">Combat</a></li>
        <li><a href="map.php">Map</a></li>
        <!--<li><a href="faq.php">FAQ</a></li>-->
        <!--<li><a href="#">Guides</a>
            <ul class="ulSousMenu">
                <li><a href="guide_jeux.php">Guide Général</a></li>
                <li><a href="guide_equipements.php">Guide Équipements</a></li>
                <li><a href="guide_items.php">Guide Items</a></li>
            </ul>
        </li>-->
        <li><a href="classement.php">Classement</a></li>
        <?php
            if($Joueur1->getPermAdmin()){
                ?>
                    <li><a href="admin/statistique.php">Statistique</a></li>
                    <li><a href="admin/index.php">Administration</a>
                        <ul class="ulSousMenu">
                            <li><a href="admin/admin-map.php">Gestion Map</a></li>
                            <li><a href="admin/admin-mods.php">Gestion Créature</a></li>
                            <li><a href="admin/admin-map.php">Gestion Objet</a></li>
                            <li><a href="admin/admin-map.php">Gestion Personnage</a></li>
                            <li><a href="admin/admin-map.php">Gestion Utilisateur</a></li>
                        </ul>
                    </li>
                <?php
            }
        ?>
    </ul>
</nav>