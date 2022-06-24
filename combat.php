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

                $personnage = $Joueur1->getPersonnage();
                if(is_null($personnage->getId())){
                    ?>
                        <div class="divReglement">
                            <p>Il faut créer un personnage d'abord.</p>
                            <p><a href="index.php">Retour à l'origine du tout.</a></p>
                        </div>
                    <?php
                }
                else{
                    ?>
                        <div class="divReglement">
                            <?php
                                $personnage->getChoixPersonnage($Joueur1);
                                $map = $personnage->getMap();
                                $tabDirection = $map->getMapAdjacenteLienHTML('nord',$Joueur1); 
                                ?>
                                    <?= $tabDirection['nord'] ?>
                                    <p class="pWelcome">Bienvenue <?= $Joueur1->getName() ?></p>
                                    <p class="pChoixCombattant">Tu as décidé de combattre avec <?= $Joueur1->getNomPersonnage() ?>, il a une fortune de <?= $personnage->getValeur() ?> (NFT)</p>
                                    <!-- AFFICHAGE EN-TÊTE PERSONNAGE ET SAC -->
                                    <div class="divEntete">
                                        <div class="divAvatar">
                                            <?php $personnage->renderHTML() ?>
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
                                        <p class="pPositionCombattant">Ton combattant est sur la position : <?= $map->getNom() ?> </p>
                                        <p>Tu peux maintenant ramasser des conneries par terre.</p>
                                        <p>Si tu en trouves qui sont parfaitement identiques, elles prennent de la valeur 😄 !</p>
                                        <p>But du jeu : Capture le "Super Jedi Légendaire".</p>
                                    </div>
                                    <div class="divAllMonsterCaptured">
                                        <p class="pTitleMonsterCaptured">Voici tes monstres capturés :</p>
                                        <?php
                                            $MysMob = new Mob($mabase);
                                            foreach($Joueur1->getAllMyMobIds() as $mob){
                                                ?>
                                                    <div class="divMonsterCaptured">
                                                        <?php
                                                            $MysMob->setMobById($mob);
                                                            $MysMob->renderHTML();
                                                        ?>
                                                    </div>
                                                <?php
                                            }
                                        ?>
                                        <p class="pMonsterCapturedInfo">Seul un certain pouvoir peut protéger tes monstres d'une capture...</p>
                                    </div>
                                    <p><a href="index.php" >Créer un autre personnage.</a></p>
                                <?php
                                $tabDirection = $map->getMapAdjacenteLienHTML('nord',$Joueur1);
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