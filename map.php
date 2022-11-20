<?php //Cette Page HTML est modifié par : M. De Almeida
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <?php
            $NameLocal = "Map";
            include "ihm/fonction-web/header.php";
        ?>
        <!-- Style CSS + -->
            <link rel="stylesheet" href="css/map.css">
            <link rel="stylesheet" href="css/perso.css">
            <link rel="stylesheet" href="css/item.css">
            <link rel="stylesheet" href="css/entite.css">
    </head>
    <body class="bodyMap">
        <?php
            include "session.php";

            // Vérifie que la Session est Valide avec le bon Mot de Passe.
            if($access === true){
                $access = $Joueur1->DeconnectToi();
            }
            // Vérifie qu'il ne s'est pas déconnecté.
            if($access === true){
                include "ihm/fonction-web/menu.php";
                //gestion accès map:
                $Personnage = $Joueur1->getPersonnage();
                $MapPersonnage = $Personnage->getMapEntite();
                if(is_null($Personnage->getIdEntite())){
                    ?>
                        <div class="divMainPage">
                            <p>Il faut créer un personnage d'abord.</p>
                            <p><a href="index.php">Retour à l'origine du tout</a></p>
                        </div>
                    <?php
                }
                else{
                    ?>
                        <div class="divMapPage">
                            <?php
                                // Quand on ne génère pas de nouvelle position ou que aucune position
                                // n'est renseignée, on peut appeler un autre personnage.
                                if(!(isset($_GET["position"]) && $_GET["position"]==='Generate')){
                                    ?>
                                        <div class="divChoixPersonnageap">
                                            <div class="divAppelPersonnage">
                                                <p class="pAppelPersonnage">Tu peux appeler un autre personnage :</p>
                                            </div>
                                            <div class="divAppelPersonnage">
                                                <?php
                                                    $Personnage->getListPersonnage($Joueur1);
                                                    $Joueur1->setPersonnage($Personnage);
                                                ?>
                                            </div>
                                        </div>
                                    <?php
                                }
                                // AFFICHAGE EN-TÊTE PERSONNAGE ET SAC
                                ?>
                                    <div class='divEntete'>
                                        <div class="divAvatar" id="divAvatar">
                                            <?php $Personnage->displayHTML() ?>
                                        </div>
                                        <div class="divSac">
                                            <p class="pTitleSac">Sacoche</p>
                                            <!-- Include Items / Equipement-->
                                            <?php
                                                include "ihm/map/affichageSacItem.php";
                                                include "ihm/map/affichageSacEquipement.php";
                                            ?>
                                        </div>
                                    </div>
                                <?php
                            ?>
                            <div class="divInformation">
                                <?php include "ihm/map/affichageTooltip.php" ?>
                                <div class="divInformationMap">
                                    <?php
                                        include "ihm/map/affichageMap.php"
                                    ?>
                                    <div class="divInfoMap">
                                        <?= $MapPersonnage->getInfoMap() ?>
                                    </div>
                                    <div class="divActionsPerso">
                                        <?= $Personnage->getActionsPerso($Joueur1) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="divMapContent">
                                <?php $MapPersonnage->getImageCssBack() ?>
                                <div class="divBuild">
                                    <?php
                                        // AFFICHAGE SI FORGE
                                        if($MapPersonnage->isForge() === true){
                                            ?>
                                                <div class="divForge">
                                                    <?php include "ihm/map/afficherForge.php.php" ?>
                                                </div>
                                            <?php
                                        }
                                    ?>
                                </div>
                                <div class="divEntity">
                                    <?php
                                        $ZoneCreatureEmpty = 0;
                                        // AFFICHAGE AUTRES JOUEURS PRESENTS
                                            include "ihm/map/affichageAutrePersos.php";
                                        // AFFICHAGE DES CREATURES
                                            include "ihm/map/displayAllCreatures.php";
                                        // SI JOUEUR OU CREATURE
                                        if($ZoneCreatureEmpty == 2){
                                            ?>
                                                <p><i>Personne à l'horizon, peu rassurant...</i></p>
                                            <?php
                                            $CreatureEmpty = 1 ;
                                        }
                                    ?>
                                </div>
                                <div class="divObjects">
                                    <?php
                                        $ZoneObjectEmpty = 0;
                                        // AFFICHAGE DES ITEMS DE LA MAP
                                            include "ihm/map/affichageItemsMap.php";
                                        // AFFICHAGE DES EQUIPEMENT DE LA MAP
                                            include "ihm/map/affichageEquipementsMap.php";
                                        // SI AUCUN ITEM ET EQUIPEMENT
                                        if($ZoneObjectEmpty == 2){
                                            if(isset($CreatureEmpty)){
                                                ?>
                                                    <p><i>Et l'absence d'objets ne me réconforte pas dans cette solitude...</i></p>
                                                <?php
                                            }
                                            else{
                                                ?>
                                                    <p><i>Il n'y a visiblement rien d'intéressant ici...</i></p>
                                                <?php
                                            }
                                        }
                                    ?>
                                </div>
                                <div class="divLog">
                                    <ul id="log"></ul>
                                </div>
                            </div>
                        </div>
                    <?php
                }
            }
            else{
                echo $errorMessage;
            }
            include "ihm/fonction-web/footer.php";
        ?>
    </body>
    <?php
        include "ihm/jsDesPages/jsMap.php";
        include "ihm/jsDesPages/jsAnimation.php";
    ?>
    <script src="javascript/map.js"></script>
</html>