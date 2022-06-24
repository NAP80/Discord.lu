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
                    <div class="divReglement">
                        <div class="divWelcome">
                            <?php
                                if($Joueur1->isAdmin() === true){
                                    ?>
                                        <p>Bienvenue Administrateur <?= $Joueur1->getName() ?>.</p>
                                        <p><a href='admin/'>Accéder au Panel Administrateur.</a></p>
                                    <?php
                                }
                                else{
                                    ?>
                                        <p>Bienvenue Joueur <?= $Joueur1->getName() ?>.</p>
                                    <?php
                                }
                            ?>
                        </div>
                        <?php
                            // Si Faction est Null
                            if($Joueur1->getIdFaction() == NULL){
                                $Faction = new Faction($mabase);
                                $Faction->getFormFaction();
                            }
                            // Si Faction Non Null
                            if($Joueur1->getIdFaction() != NULL){
                                $PersoChoisie = new Personnage($mabase);
                                $PersoChoisie = $Joueur1->getPersonnage();
                                if(!is_null($PersoChoisie)){
                                    $PersoChoisie->getChoixPersonnage($Joueur1);
                                }
                                $PersoCree = new Personnage($mabase);
                                $PersoCree = $PersoCree->CreatNewPersonnage($Joueur1->getId());
                                if(!is_null($PersoCree)){
                                    $PersoChoisie = $PersoCree;
                                }
                                if(!is_null($PersoChoisie)){
                                    $Joueur1->setPersonnage($PersoChoisie);
                                    ?>
                                        <div class="divAction">
                                            <?php
                                                if(!empty($PersoChoisie->getNom())){
                                                    ?>
                                                        <p><a href="combat.php">Viens combattre avec <?= $PersoChoisie->getNom() ?></a></p>
                                                    <?php
                                                }
                                                else{
                                                    ?>
                                                        <p><a href="combat.php">Viens combattre avec <?= $Joueur1->getNomPersonnage() ?></a></p>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                    <?php
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