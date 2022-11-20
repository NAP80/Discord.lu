<div class="divAllCreatures">
    <div class='effect'></div>
    <?php
        $listCreature = $MapPersonnage->getAllCreatures();
        if(count($listCreature) > 0){
            $Creature = new Creature($mabase);
            // Affichage des Creature Enemis
            $CreatureContre = $MapPersonnage->getAllCreatureContre($Joueur1);
            if(count($CreatureContre) > 0){
                ?>
                    <div class='divInfoCreatures'>
                        <p class='pInfoCreatures'>Tu es bloqué, il y a des créatures qui te bloquent le passage...</p>
                    </div>
                <?php
            }
            ?>
                <ul id="ulCreature" class="ulCreature">
                    <?php
                        foreach($CreatureContre as $CreatureID){
                            $Creature->setCreatureById($CreatureID);
                            ?>
                                <li id="Creature<?= $Creature->getIdEntite() ?>" class="liAdverse" onmouseover="afficheDivPerso(event)" onmouseout="cacheDivPerso(event)">
                                    <a id="aCreature<?= $Creature->getIdEntite() ?>" onclick="AttaquerPerso(<?= $Creature->getIdEntite() ?>,<?= $Creature->getIdTypeEntite() ?>, event)">
                                        <?php
                                            $Creature->displayHTML();
                                        ?>
                                    </a>
                                </li>
                            <?php
                        }
                        // Affichage des Creature Capturés
                        $CreatureCaptured = $MapPersonnage->getAllCreatureCapture($Joueur1);
                        foreach($CreatureCaptured as $CreatureID){
                            $Creature->setCreatureById($CreatureID);
                            ?>
                                <li id="Creature<?= $Creature->getIdEntite() ?>" class="liCaptured">
                                    <a id="aCreature<?= $Creature->getIdEntite() ?>" onclick="/*SoinCreature(<?= $Creature->getIdEntite() ?>,1)*/">
                                        <?php
                                            $Creature->displayHTML();
                                        ?>
                                    </a>
                                </li>
                            <?php
                        }
                    ?>
                </ul>
            <?php
        }
        else{
            $ZoneCreatureEmpty++;
        }
    ?>
</div>