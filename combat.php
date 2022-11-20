<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <?php
            $NameLocal = "Combat";
            include "ihm/fonction-web/header.php";
        ?>
        <!-- Style CSS + -->
            <link rel="stylesheet" href="css/combat.css">
            <link rel="stylesheet" href="css/perso.css">
            <link rel="stylesheet" href="css/item.css">
            <link rel="stylesheet" href="css/map.css">
            <link rel="stylesheet" href="css/entite.css">
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
                $Personnage = $Joueur1->getPersonnage();
                if(is_null($Personnage->getIdEntite())){
                    ?>
                        <div class="divMainPage">
                            <p>Il faut créer un personnage d'abord.</p>
                            <p><a href="index.php">Retour à l'origine du tout.</a></p>
                        </div>
                    <?php
                }
                else{
                    ?>
                        <div class="divMainPage">
                            <?php
                                $Personnage->getListPersonnage($Joueur1);
                                $MapPersonnage = $Personnage->getMapEntite();
                                ?>
                                    <p class="pWelcome">Bienvenue <?= $Joueur1->getPseudo() ?></p>
                                    <p class="pChoixCombattant">Tu as décidé de combattre avec <?= $Joueur1->getNomPersonnage() ?>, il a une fortune de <?= $Personnage->getValeur() ?>§</p>
                                    <!-- AFFICHAGE EN-TÊTE PERSONNAGE ET SAC -->
                                    <div class="divEntete">
                                        <div class="divAvatar">
                                            <?php $Personnage->displayHTML() ?>
                                        </div>
                                        <div class="divSac">
                                            <p class="pTitleSac">Sacoche</p>
                                            <?php
                                                // Include Items / Equipement
                                                include "ihm/map/affichageSacItem.php";
                                                include "ihm/map/affichageSacEquipement.php";
                                            ?>
                                        </div>
                                    </div>
                                    <div class="divInfoCombat">
                                        <p class="pPositionCombattant">Ton combattant est sur la position : <?= $MapPersonnage->getNameMap() ?> (<?= $MapPersonnage->getX() ?>/<?= $MapPersonnage->getY() ?>) </p>
                                    </div>
                                    <div class="BoxCreatureCaptured ulCreature">
                                        <p class="pTitleCreatureCaptured">Voici tes créatures capturés :</p>
                                        <ul class="ulCreature">
                                            <?php
                                                $MyCreature = new Creature($mabase);
                                                foreach($Joueur1->getAllMyCreatureIds() as $Creature){
                                                    $MyCreature->setCreatureById($Creature);
                                                    $MapCreature = $MyCreature->getMapEntite();
                                                    ?>
                                                        <li id="Creature<?= $MyCreature->getIdEntite() ?>" class="liCaptured" style="ho">
                                                            <a id="aCreature<?= $MyCreature->getIdEntite() ?>">
                                                                <?php
                                                                    $MyCreature->displayHTML();
                                                                ?>
                                                            </a>
                                                        </li>
                                                        <?php  ?>
                                                        <p class="hoverCreatureCaptured"><?= $MapCreature->getNameMap() ?> (<?= $MapCreature->getX() ?>/<?= $MapCreature->getY() ?>)</p>
                                                    <?php
                                                }
                                            ?>
                                        </ul>
                                    </div>
                                    <p><a href="index.php" >Créer un autre personnage.</a></p>
                                <?php
                            ?>
                        </div>
                    <?php
                }
            }
            else{
                echo $errorMessage;
            }
            include "ihm/fonction-web/footer.php";
            include "ihm/jsdespages/jsMap.php";
            include "ihm/jsdespages/jsAnimation.php";
        ?>
    </body>
</html>