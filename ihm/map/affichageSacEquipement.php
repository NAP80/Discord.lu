<div class="divSacEquipement">
    <p class="pTitleSacEquipement">Équipement</p>
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
                        <li id="equipementSac<?= $Equipement->getIdObject() ?>" class='<?= $class;?>'>
                            <a onclick="useEquipement(<?= $Equipement->getIdObject() ?>)">
                                <img class='imgEquipementSac' src='<?= $Equipement->getImgEquipement(); ?>'/>
                                <span class='spanEquipementSac'>
                                    <?= $Equipement->getNameObject() ?> lvl <?= $Equipement->getLvl() ?>
                                </span>
                            </a>
                        </li>
                    <?php
                }
            }
        ?>
    </ul>
</div>