<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <?php
            $NameLocal = "Guide Équipement";
            include "ihm/fonction-web/header.php";
        ?>
        <!-- Style CSS + -->
            <link rel="stylesheet" href="css/guide.css">
    </head>
    <body class="bodyGuideEquipements">
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
                    <div class="divGuideEquipements">
                        <h1>Guide d'équipement</h1>
                        <h2>Les differents équipements :</h2>
                        <p>Il y a plusieurs type d'équipement.</p>
                        <p>Les objets :</p>
                        <p>En poussiere,</p>
                        <p>Tout Moue,</p>
                        <p>Cassé,</p>
                        <p>Normal,</p>
                        <p>Neuf.</p>
                    </div>
                <?php
            }else{
                echo $errorMessage;
            }
            include "ihm/fonction-web/footer.php";
        ?>
    </body>
</html>