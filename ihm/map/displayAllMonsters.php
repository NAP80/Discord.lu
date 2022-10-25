<div class="divAllMonsters">
    <div class='effect'></div>
    <?php
        $listMonster = $MapPersonnage->getAllMonsters();
        if(count($listMonster) > 0){
            $Monster = new Monster($mabase);
            // Affichage des Monster Enemis
            $MonsterContre = $MapPersonnage->getAllMonsterContre($Joueur1);
            if(count($MonsterContre) > 0){
                ?>
                    <div class='divInfoMonsters'>
                        <p class='pInfoMonsters'>Tu es bloqué, il y a des monstres qui te bloquent le passage...</p>
                    </div>
                <?php
            }
            ?>
                <ul id="ulMonster" class="ulMonster">
                    <?php
                        foreach($MonsterContre as $MonsterID){
                            $Monster->setMonsterById($MonsterID);
                            ?>
                                <li id="Monster<?= $Monster->getIdEntite() ?>" class="liAdverse" onmouseover="afficheDivPerso(event)" onmouseout="cacheDivPerso(event)">
                                    <a id="aMonster<?= $Monster->getIdEntite() ?>" onclick="AttaquerPerso(<?= $Monster->getIdEntite() ?>,<?= $Monster->getIdTypeEntite() ?>, event)">
                                        <?php
                                            $Monster->displayHTML();
                                        ?>
                                    </a>
                                </li>
                            <?php
                        }
                        // Affichage des Monster Capturés
                        $MonsterCaptured = $MapPersonnage->getAllMonsterCapture($Joueur1);
                        foreach($MonsterCaptured as $MonsterID){
                            $Monster->setMonsterById($MonsterID);
                            ?>
                                <li id="Monster<?= $Monster->getIdEntite() ?>" class="liCaptured">
                                    <a id="aMonster<?= $Monster->getIdEntite() ?>" onclick="/*SoinMonster(<?= $Monster->getIdEntite() ?>,1)*/">
                                        <?php
                                            $Monster->displayHTML();
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
            $ZoneMonsterEmpty++;
        }
    ?>
</div>