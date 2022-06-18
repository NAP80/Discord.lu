<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <?php
            $NameLocal = "Classement";
            include "ihm/fonction-web/header.php";
        ?>
        <!-- Style CSS + -->
            <link rel="stylesheet" href="css/classement.css">
    </head>
    <body class="bodyAccueil">
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
                    <div class="divClassement">
                        <h1>Classement</h1>
                        <table>
                            <tr>
                                <th>Pseudo<th>
                                <th>Monstre capturé</th>
                                <th>NFT</th>
                                <th>LV</th>
                                <th>XP</th>
                                <th>Dégats</th>
                                <th>Vie</th>
                            </tr>
                            <?php
                                //$Test = new User($mabase);
                                //$user =  $Test -> showusers($mabase) ;

                                //while ($NB < 30) {
                                    ?>
                                        <p> </p>
                                    <?php
                                //    $NB++;
                                //}
                            ?>
                                    <tr>
                                        <td><?= "Test" ?></td>
                                        <td><?= 42 ?></td>
                                        <td><?= 5 ?></td>
                                        <td><?= 21 ?></td>
                                        <td><?= 400 ?></td>
                                        <td><?= 500 ?></td>
                                        <td><?= 600 ?></td>
                                    </tr>
                        </table>
                    </div>
                <?php
            }else{
                echo $errorMessage;
            }
            include "ihm/fonction-web/footer.php";
        ?>
    </body>
</html>