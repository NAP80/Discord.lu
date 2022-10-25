<div class="divMapEquipments">
    <?php
        $listEquipements = $MapPersonnage->getEquipements();
        if(count($listEquipements) > 0){
            ?> 
                <div class="divInfoEquipement">
                    <p class="pEquipement">Équipements Présent :</p>
                    <p class="divRarete">Commun - Rare</p>
                </div>
                <ul class="ulEquipement">
                    <?php
                        foreach($listEquipements as $Equipement){
                            ?>
                                <li id="equipement<?= $Equipement->getIdEquipement() ?>" style="<?= $Equipement->getClassRarete() ?>">
                                    <a onclick="CallApiAddEquipementInSac(<?= $Equipement->getIdEquipement() ?>)">
                                        <?= $Equipement->getNameEquipement() ?>
                                    </a>
                                </li>
                            <?php
                        }
                    ?>
                </ul>
            <?php
        }
        else{
            $ZoneObjectEmpty++;
        }
    ?>
</div>