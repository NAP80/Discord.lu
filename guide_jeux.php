<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <?php
            $NameLocal = "Guide Jeux";
            include "ihm/fonction-web/header.php";
        ?>
        <!-- Style CSS + -->
            <link rel="stylesheet" href="css/guide.css">
    </head>
    <body class="bodyGuideJeux">
        <?php
            include "session.php";

            // Vérifie que la Session est Valide avec le bon Mot de Passe.
            if($access === true){
                $access = $Joueur1->DeconnectToi();
            }
            // Vérifie qu'il ne s'est pas déconnecté.
            if($access === true){
                include "ihm/fonction-web/menu.php";
                ?>
                    <div class="divGuideJeux">
                        <h1>Guide du Jeux</h1>
                        <h2>But du jeu :</h2>
                        <p>Le but du jeu est d'explorer la map et de capturez des animaux.</p>
                        <h2>Comment avancer sur la map ?</h2>
                        <p>Allez dans l'onglet map et cliquez sur les directions indiquées sur les cotés de la map.</p>
                        <h2>Comment tuer une Créature ?</h2>
                        <p>Cliquez sur la Créature que vous souhaitez combattre.</p>
                        <h2>Comment selectioner un item ?</h2>
                        <p>Cliquez sur l'item dans l'onglet item pour se soigner.</p>
                        <p>Cliquez sur l'item dans l'onglet équipement pour selectioner une armure ou objet d'attaque.</p>
                    </div>
                <?php
            }
            else{
                echo $errorMessage;
            }
            include "ihm/fonction-web/footer.php";
        ?>
    </body>
</html>