<div class="divAllMonsters">
    <div class='effect'></div>
    <?php
        $listMonster = $map->getAllMonsters();
        if(count($listMonster) > 0){
            $Monster = new Monster($mabase);
            // Affichage des Monster Enemis
            $MonsterContre = $map->getAllMonsterContre($Joueur1);
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
                                            $Monster->renderHTML();
                                        ?>
                                    </a>
                                </li>
                            <?php
                        }
                        // Affichage des Monster Capturés
                        $tabMonster = $map->getAllMonsterCapture($Joueur1);
                        foreach( $tabMonster as $MonsterID){
                            $Monster->setMonsterById($MonsterID);
                            ?>
                                <li id="Monster<?= $Monster->getIdEntite() ?>" class="liCaptured">
                                    <a id="aMonster<?= $Monster->getIdEntite() ?>" onclick="/*SoinMonster(<?= $Monster->getIdEntite() ?>,1)*/">
                                        <?php
                                            $Monster->renderHTML();
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