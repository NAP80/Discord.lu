<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <?php
            $NameLocal = "Acceuil";
            include "ihm/fonction-web/header.php";
        ?>
    </head>
    <body class="bodyAccueil">
        <?php
            include "session.php";
            if($access === true){
                $access = $Joueur1->DeconnectToi();
            }
            if($access === true){
                include "ihm/fonction-web/menu.php";
                ?>
                    <div class="divMainPage">
                        <div class="divWelcome">
                            <p>Bienvenue <?= $Joueur1->getNameTypeUser() ?> <?= $Joueur1->getPseudo() ?>.</p>
                            <?php
                                if($Joueur1->getPermAdmin()){
                                    ?>
                                        <p><a href='admin/'>Accéder au Panel Administrateur.</a></p>
                                    <?php
                                }
                                if($Joueur1->getPermStaff()){
                                    ?>
                                        <p><a href='staff/'>Accéder au Panel Staff.</a></p>
                                    <?php
                                }
                            ?>
                        </div>
                        <?php
                            // Si Envoie de request Faction-Id
                            if(isset($_POST['faction-id'])){
                                $Joueur1->setFaction($_POST['faction-id']);
                            }
                            // Si Faction est Null ou inférieur à 1 ou supérieur à 4
                            if((($Joueur1->getIdFaction() == NULL) || ($Joueur1->getIdFaction() <= 0) || ($Joueur1->getIdFaction() >= 5))){
                                $Joueur1->getFormFaction();
                            }
                            // Si Faction Non Null et inférieur ou égale à 4 (Donc qu'on a une faction valide)
                            else{
                                // Si un Personnage est assigné
                                if($Joueur1->getIdPersonnage() !== NULL){
                                    $NbPersonnage = $Joueur1->getNbPersonnages();
                                    ?>
                                        <p class="NbPersonnage">Vous avez <?= $NbPersonnage ?> personnages sur 10.</p>
                                    <?php
                                    // Récupération Objet Personnage
                                    $ObjectPersonnage = $Joueur1->getPersonnage();
                                    if($NbPersonnage < 10){
                                        // Formulaire création Personnage
                                        $Joueur1->CreatNewPersonnage();
                                    }
                                    if($NbPersonnage > 1){
                                        // Selection Personnage
                                        $Joueur1->getChoixPersonnage();
                                    }
                                    $ObjectPersonnage = $Joueur1->getPersonnage();
                                    ?>
                                        <div class="divAction">
                                            <p><a href="combat.php">Viens combattre avec <?= $ObjectPersonnage->getNameEntite() ?></a></p>
                                        </div>
                                    <?php
                                }
                                // Si aucun Personnage choisis.
                                else{
                                    // Formulaire création Personnage
                                    $Joueur1->CreatNewPersonnage();
                                }
                            }
                        ?>
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