<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <?php
            $NameLocal = "Guide Items";
            include "ihm/fonction-web/header.php";
        ?>
        <!-- Style CSS + -->
            <link rel="stylesheet" href="css/guide.css">
    </head>
    <body class="bodyGuideItems">
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
                    <div class="divGuideItems">
                        <h1>Guide d'item</h1>
                        <h2>Les differents items :</h2>
                        <p>Il y a plusieurs types d'items.</p>
                        <p>Les objets :</p>
                        <p>Pourris,</p>
                        <p>Bizzarre,</p>
                        <p>Cassés,</p>
                        <p>Nuls,</p>
                        <p>Normaux.</p>
                    </div>
                <?php
            }else{
                echo $errorMessage;
            }
            include "ihm/fonction-web/footer.php";
        ?>
    </body>
</html>