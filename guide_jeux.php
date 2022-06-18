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
                        <p>Le but du jeu est de capturer le "super Jedi Légendaire".</p>
                        <h2>Comment avancer sur la map ?</h2>
                        <p>Allez dans l'onglet map et cliquez sur les directions indiquées sur les cotés de la map.</p>
                        <h2>Comment tuer un monstre ?</h2>
                        <p>Cliquez sur le monstre que vous souhaitez tuer.</p>
                        <h2>Comment selectioner un item ?</h2>
                        <p>Cliquez sur l'item dans l'onglet item pour se soigner.</p>
                        <p>Cliquez sur l'item dans l'onglet équipement pour selectioner une armure ou objet d'attaque.</p>
                    </div>
                <?php
            }else{
                echo $errorMessage;
            }
            include "ihm/fonction-web/footer.php";
        ?>
    </body>
</html>