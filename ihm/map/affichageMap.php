<?php
    $TelportationPositionDepart = $MapPersonnage->getPosition();
    // Gestion de la téléportation
    $cardinalite = '';
    if(isset($_GET["cardinalite"])){
        $cardinalite = $_GET["cardinalite"];
    }
    if($MapPersonnage->LogVisiteMap($Personnage) === true){
        if(isset($_GET["position"]) && $Personnage->getHealthNow() > 0){
            $MapPersonnage = $MapPersonnage->loadMap($_GET["position"],$cardinalite,$Joueur1);
        }
        else{
            if($Personnage->getHealthNow() == 0){
                $Personnage->resurection();
                $MapPersonnage = $Personnage->getMapEntite();
            }
            $MapPersonnage = $MapPersonnage->loadMap($MapPersonnage->getPosition(),'nord',$Joueur1);
        }
        // Puis on déplace le joueur
        $Joueur1->getPersonnage()->ChangeMap($MapPersonnage);
    }
    $BousoleDeplacement = $MapPersonnage->getMapAdjacenteLienHTML($cardinalite,$Joueur1);
?>
<div class="divMap">
    <?= $BousoleDeplacement['nord'] ?>
    <img src="assets/image/Fleche-225px.png" class="NESO">
    <div class="divMapOuest">
        <?= $BousoleDeplacement['ouest'] ?>
        <div class="divMapEst">
            <div class="divMapCentre">
                <?php $Joueur1->getVisitesHTML(6) ?>
            </div>
            <?= $BousoleDeplacement['est'] ?>
        </div>
    </div>
    <?= $BousoleDeplacement['sud'] ?>
</div>