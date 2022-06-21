<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <?php
            $NameLocal = "Règlement";
            include "ihm/fonction-web/header.php";
        ?>
        <!-- Style CSS + -->
            <link rel="stylesheet" href="css/reglement.css">
    </head>
    <body class="bodyRules">
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
                    <div class="divRules">
                        <h1 class="divRulesTitle">Règlement</h1>
                        <p><b>1</b> - Être gentil</p>
                        <p><b>2</b> - Ne pas être méchant</p>
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