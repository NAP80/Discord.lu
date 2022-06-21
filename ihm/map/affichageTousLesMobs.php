<div class="divAllMobs">
    <div class='effect'></div>
    <?php
        $listMob = $map->getAllMobs();
        if(count($listMob) > 0){
            $Mob = new Mob($mabase);
            // Affichage des Mob Enemis
            $mobContre = $map->getAllMobContre($Joueur1);
            if(count($mobContre) > 0){
                ?>
                    <div class='divInfoMobs'>
                        <p class='pInfoMobs'>Tu es bloqué, il y a des monstres qui te bloquent le passage...</p>
                    </div>
                <?php
            }
            ?>
                <ul id="ulMob" class="ulMob">
                    <?php
                        foreach($mobContre as $MobID){
                            $Mob->setMobById($MobID);
                            ?>
                                <li id="Mob<?= $Mob->getId() ?>" class="liAdverse" onmouseover="afficheDivPerso(event)" onmouseout="cacheDivPerso(event)">
                                    <a id="aMob<?= $Mob->getId() ?>" onclick="AttaquerPerso(<?= $Mob->getId() ?>,1, event)">
                                        <?php
                                            $Mob->renderHTML();
                                        ?>
                                    </a>
                                </li>
                            <?php
                        }
                        // Affichage des Mob Capturés
                        $tabmob = $map->getAllMobCapture($Joueur1);
                        foreach( $tabmob as $MobID){
                            $Mob->setMobById($MobID);
                            ?>
                                <li id="Mob<?= $Mob->getId() ?>" class="liCaptured">
                                    <a id="aMob<?= $Mob->getId() ?>" onclick="SoinMob(<?= $Mob->getId() ?>,1)">
                                        <?php
                                            $Mob->renderHTML();
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
            $ZoneMobEmpty++;
        }
    ?>
</div>