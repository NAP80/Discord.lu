<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <?php
            $NameLocal = "Crédit";
            include "ihm/fonction-web/header.php";
        ?>
        <!-- Style CSS + -->
            <link rel="stylesheet" href="css/credit.css">
    </head>
    <body class="bodyCredit">
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
                    <h1 class="h1SN1">Les Acteurs du Projet</h1>
                    <div class="divProf">
                        <h3>Les Prof</h3>
                        <p>Mr.Langlace</p>
                        <p>Mr.Gremont</p>
                    </div>
                    <div class="divSN1">
                        <h3>Les BTS SN1</h3>
                        <p>Léa Bernard</p>
                        <p>Thomas Berthier</p>
                        <p>Louis Boucher</p>
                        <p>Alexandre Caré</p>
                        <p>Jeremy Caruelle</p>
                        <p>Clément Caudron</p>
                        <p>Clément Cauet</p>
                        <p>Baptiste Colson</p>
                        <p>Nicolas De almeida</p>
                        <p>Mathis Dechir</p>
                        <p>Gaëtan Deneufgermain</p>
                        <p>Maël Drelon</p>
                        <p>Kylian Duval</p>
                        <p>Gregory Febvin</p>
                        <p>Lucas Ghyselen</p>
                        <p>Julien Laridant</p>
                        <p>Romain Lienard</p>
                        <p>Julien Lussiez</p>
                        <p>Nicolas Marrocchi</p>
                        <p>Vincent Martel</p>
                        <p>Yann Martin</p>
                        <p>Valentin Pesant</p>
                        <p>Florian Rabasté</p>
                        <p>Evans Varnier</p>
                        <p>Maxence Vollet</p>
                    </div>
                <?php
            }else{
                echo $errorMessage;
            }
            include "ihm/fonction-web/footer.php";
        ?>
    </body>
</html>