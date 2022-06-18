<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <?php
            $NameLocal = "FAQ";
            include "ihm/fonction-web/header.php";
        ?>
        <!-- Style CSS + -->
            <link rel="stylesheet" href="css/faq.css">
    </head>
    <body class="bodyFAQ">
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
                    <div class="divFAQ">
                        <h1>FAQ</h1>
                        <h3>Questions Fréquentes</h3>
                        <h4>Comment se déplacer sur la carte ?</h4>
                        <p>Pour pouvoir vous déplacez, cliquez sur les bords de la map.</p>
                        <h4>Comment utiliser un items ?</h4>
                        <p>Pour pouvoir utiliser un item, allez dans votre sacoche puis dans items.</p>
                        <h4>Comment créer un nouveau personnage ?</h4>
                        <p>Pour créer un nouveau personnage allez dans accueil.</p>
                    </div>
                <?php
            }else{
                echo $errorMessage;
            }
            include "ihm/fonction-web/footer.php";
        ?>
    </body>
</html>