<?php
    $map = $Personnage->getMap();
    $TelportationPositionDepart = $map->getPosition();
    // Gestion de la téléportation
    $cardinalite = '';
    if(isset($_GET["cardinalite"])){
        $cardinalite = $_GET["cardinalite"];
    }
    if($map->LogVisiteMap($Personnage) === true){
        if(isset($_GET["position"]) && $Personnage->getVie() > 0){
            $map = $map->loadMap($_GET["position"],$cardinalite,$Joueur1);
        }else{
            if($Personnage->getVie() == 0){
                $Personnage->resurection();
                $map = $Personnage->getMap();
            }
            $map = $map->loadMap($map->getPosition(),'nord',$Joueur1);
        }
        // Puis on déplace le joueur
        $Joueur1->getPersonnage()->ChangeMap($map);
    }
    $BousoleDeplacement = $map->getMapAdjacenteLienHTML($cardinalite,$Joueur1);
?>