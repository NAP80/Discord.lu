<div class="divSacEquipement">
    <p class="pTitleSacEquipement">Equipement</p>
    <ul id="SacEquipement" class="ulSac">
        <?php
            $listEquipements = $Joueur1->getPersonnage()->getEquipementNonPorte();
            if(count($listEquipements) > 0){

                foreach($listEquipements as $Equipement){
                     $class = "standard";
                     $idcat = $Equipement->getCategorie()['id'];
                    
                        switch ($idcat) {
                            case 1:
                                $class =  "standard";
                                break;
                            case 2:
                                $class =  "standard";
                                break;
                            case 3:
                                $class =  "magic";
                                break;
                            case 4:
                                $class =  "magic";
                                break;
                            default:
                                $class =  "standard";
                                
                                
                                
                        }
                            
                        
                    ?>
                   
                        <li id="equipementSac<?= $Equipement->getId() ?>" class='<?= $class;?>'>
                            <a onclick="useEquipement(<?= $Equipement->getId() ?>)">
                                <img class='imgEquipementSac' src='<?= $Equipement->getLienImage(); ?>'/>
                                <span class='spanEquipementSac'>
                                    <?= $Equipement->getNom() ?> lvl <?= $Equipement->getLvl() ?>
                                </span>
                            </a>
                        </li>
                    <?php
                }
            }
        ?>
    </ul>
</div>