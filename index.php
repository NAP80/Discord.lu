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
                            <?php
                                if($Joueur1->isAdmin() === true){
                                    ?>
                                        <p>Bienvenue Administrateur <?= $Joueur1->getPseudo() ?>.</p>
                                        <p><a href='admin/'>Accéder au Panel Administrateur.</a></p>
                                    <?php
                                }
                                else{
                                    ?>
                                        <p>Bienvenue Joueur <?= $Joueur1->getPseudo() ?>.</p>
                                    <?php
                                }
                            ?>
                        </div>
                        <?php
                            // Si Envoie de request Faction-Id
                            if(isset($_POST['faction-id'])){
                                $Joueur1->setFaction($_POST['faction-id']);
                            }
                            // Si Faction est Null ou supérieur à 4
                            if((($Joueur1->getIdFaction() == NULL) || ($Joueur1->getIdFaction() >= 5)) && (!isset($_POST['faction-id']))){
                                $Joueur1->getFormFaction();
                            }
                            // Si Faction Non Null et inférieur ou égale à 4 (Donc qu'on a une faction valide)
                            else{
                                // Si un Personnage est assigné
                                if($Joueur1->getIdPersonnage() !== NULL){
                                    // Récupération Objet Personnage
                                    $ObjectPersonnage = $Joueur1->getPersonnage();
                                    $Joueur1->getChoixPersonnage(); // Fonction de selection du personnage
                                    // Formulaire création Personnage
                                    $Joueur1->CreatNewPersonnage();
                                    ?>
                                        <div class="divAction">
                                            <p><a href="combat.php">Viens combattre avec <?= $ObjectPersonnage->getNom() ?></a></p>
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