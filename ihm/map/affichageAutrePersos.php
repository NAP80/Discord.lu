<div class="divAllPerso">
    <?php
        $listPersos = $MapPersonnage->getAllPersonnages();
        if(count($listPersos) > 1){
            ?>
                <div class='divInfoPlayers'>
                    <p class='pInfoPlayers'>Visiblement tu n'es pas seul ici, il y a aussi :</p>
                </div>
                <ul id="ulPersos" class="ulPersonnages">
                    <?php
                        $PersoJoueur = $Joueur1->getPersonnage();
                        foreach($listPersos as $Perso){
                            if($Perso->getIdEntite() != $PersoJoueur->getIdEntite()){
                                ?>
                                    <li class="liAdverse" onmouseover="afficheDivPerso(event)" onmouseout="cacheDivPerso(event)">
                                        <a id="aPerso<?= $Perso->getIdEntite() ?>"<?php if(($PersoJoueur->getIdUser() !== $Perso->getIdUser()) && ($MapPersonnage->getPvP())){?>onclick="AttaquerPerso(<?= $Perso->getIdEntite() ?>,<?= $Perso->getIdTypeEntite() ?>, event)"<?php } ?>>
                                            <?php $Perso->displayHTML() ?>
                                        </a>
                                    </li>
                                <?php
                            }
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