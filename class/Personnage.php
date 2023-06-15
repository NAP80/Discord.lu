<?php
    class Personnage extends Entite{
        public $_idTypePersonnage;
        public $_levelPersonnage;
        public $_expPersonnage;
        public $_moneyPersonnage;
        public $_idMapSpawnPersonnage;
        public $_effectPersonnage; // Todo : Ajouter des effets - Paralysé, Poison, etc

        public $sacItems=array();

        public function __construct($bdd){
            Parent::__construct($bdd);
        }

        /** Set Personnage by Id Personnage */
        public function setPersonnageById($idPersonnage){
            Parent::setEntiteById($idPersonnage);
            $req = $this->_bdd->prepare("SELECT * FROM `Personnage` WHERE idPersonnage=:idPersonnage");
            $req->execute(['idPersonnage' => $idPersonnage]);
            if($tab = $req->fetch()){
                $this->_idTypePersonnage    = $tab['idTypePersonnage'];
                $this->_levelPersonnage     = $tab['levelPersonnage'];
                $this->_expPersonnage       = $tab['expPersonnage'];
                $this->_moneyPersonnage     = $tab['moneyPersonnage'];
                $this->_idMapSpawnPersonnage= $tab['idMapSpawnPersonnage'];
                $this->_effectPersonnage    = $tab['effectPersonnage'];
            }
            //select les items déjà présent
            $req = $this->_bdd->prepare("SELECT idItem FROM `PersoSacItems` WHERE idPersonnage=:idPersonnage");
            $req->execute(['idPersonnage' => $idPersonnage]);
            while($tab = $req->fetch()){
                array_push($this->sacItems,$tab[0]);
            }
        }

        /** Return id Type Personnage */
        public function getIdTypePersonnage(){
            return $this->_idTypePersonnage;
        }

        /** Return Level Personnage */
        public function getLevelPersonnage(){
            return $this->_levelPersonnage;
        }

        /** Return Experience Personnage */
        public function getExpPersonnage(){
            return $this->_expPersonnage;
        }

        /** Return Money Personnage */
        public function getMoneyPersonnage(){
            return $this->_moneyPersonnage;
        }

        /** Return Effects Personnage */
        public function getEffectPersonnage(){
            // Todo : Faire un traitement avant de renvoyer
            return $this->_effectPersonnage;
        }

        /** Return Id Map Spawn Personnage */
        public function getIdMapSpawnPersonnage(){
            return $this->_idMapSpawnPersonnage;
        }

        /** Set Level Personnage */
        public function setLevelpersonnage($levelPersonage){
            $req = $this->_bdd->prepare("UPDATE Personnage SET levelPersonnage = ? WHERE idPersonnage = ?");
            $req->execute(array($levelPersonage, $this->_idEntite));
            return $req;
        }

        /** Set Experience Personnage */
        public function setExpPersonnage($expPersonnage){
            $req = $this->_bdd->prepare("UPDATE Personnage SET expPersonnage = ? WHERE idPersonnage = ?");
            $req->execute(array($expPersonnage, $this->_idEntite));
            return $req;
        }

        /** Set Money Personnage */
        public function setMoneyPersonage($moneyPersonnage){
            $req = $this->_bdd->prepare("UPDATE Personnage SET moneyPersonnage = ? WHERE idPersonnage = ?");
            $req->execute(array($moneyPersonnage, $this->_idEntite));
            return $req;
        }

        /** Set Effect Personnage */
        public function setEffectPersonnage($effectPersonnage){
            $req = $this->_bdd->prepare("UPDATE Personnage SET effectPersonnage = ? WHERE idPersonnage = ?");
            $req->execute(array($effectPersonnage, $this->_idEntite));
            return $req;
        }

        /** Set Id Map Spawn Personnage */
        public function setIdMapSpawnPersonnage($idMapSpawnPersonnage){
            $req = $this->_bdd->prepare("UPDATE Personnage SET effectPersonnage = ? WHERE idPersonnage = ?");
            $req->execute(array($idMapSpawnPersonnage, $this->_idEntite));
            return $req;
        }

        /** Personnage Take dammage by Personnage*/
        public function SubitDegatByPersonnage($dammage){
            $dammage -= ($dammage*$this->getDefense()) / 100;
            $dammage = round($dammage);
            if($dammage<0){
                $dammage = 0;
            }
            $this->_healthNow = $this->_healthNow - $dammage;
            if($this->_healthNow < 0){
                $this->_healthNow = 0;
            }
            $req = $this->_bdd->prepare("UPDATE `Entite` SET `healthNow`=:healthNow WHERE `idEntite`=:idEntite");
            $req->execute(['healthNow' => $this->_healthNow, 'idEntite' => $this->_idEntite]);
            return $this->_healthNow;
        }

        /** Personnage Take dammage by Personnage*/ // Voir pour Mettre dans Entite
        public function SubitDegatByCreature($Creature){
            //Attente de pull qui marche
            //Si le Creature attaquant a plus de O PV, il attaque
            if($Creature->getHealthNow() > 0){
                $CreatureDegatAttaqueEnvoyer=$Creature->getAttaque();
                //on réduit les déga avec armure si possible
                $enMoins = ($CreatureDegatAttaqueEnvoyer*$this->getDefense())/100;
                $CreatureDegatAttaqueEnvoyer-=$enMoins;
                $CreatureDegatAttaqueEnvoyer = round($CreatureDegatAttaqueEnvoyer);
                if($CreatureDegatAttaqueEnvoyer<0){
                    $CreatureDegatAttaqueEnvoyer = 0;
                }
                $healthAvantAttaque = $this->_healthNow;
                //on va rechercher l'historique
                $req = $this->_bdd->prepare("SELECT * FROM `AttaquePersoCreature` WHERE idCreature=:idCreature AND idPersonnage=:idPersonnage");
                $req->execute(['idCreature' => $Creature->getIdEntite(), 'idPersonnage' => $this->_idEntite]);
                $tabAttaque['nbCoup']=0;
                $tabAttaque['DegatsDonnes']=$CreatureDegatAttaqueEnvoyer;
                if($tab = $req->fetch()){
                    $tabAttaque = $tab;
                    $tabAttaque['DegatsDonnes']+=$CreatureDegatAttaqueEnvoyer;
                    $tabAttaque['nbCoup']++;
                }
                else{
                    //insertion d'une nouvelle attaque
                    $req = $this->_bdd->prepare("INSERT INTO `AttaquePersoCreature`(`idCreature`, `idPersonnage`, `nbCoup`, `coupFatal`, `DegatsDonnes`, `DegatsReçus`)
                    VALUES(:idCreature, :idPersonnage, 0, 0, :DegatsDonnes, 0)");
                    $req->execute(['idCreature' => $Creature->getIdEntite(), 'idPersonnage' => $this->_idEntite, 'DegatsDonnes' => $tabAttaque['DegatsReçus']]);
                }
                $this->_healthNow = $this->_healthNow - $CreatureDegatAttaqueEnvoyer;
                if($this->_healthNow<0){
                    $this->_healthNow =0;
                    //on ne peut pas donner plus de degat que la HealthNow d'un perso
                    $tabAttaque['DegatsDonnes'] = $healthAvantAttaque;
                    //retour en zone 0,0
                }
                $req = $this->_bdd->prepare("UPDATE `Entite` SET `healthNow`=:healthNow WHERE `idEntite`=:idEntite");
                $req->execute(['healthNow' => $this->_healthNow, 'idEntite' => $this->_idEntite]);
                //update AttaquePersoCreature pour mettre a jour combien le perso a pris de degat 
                $req = $this->_bdd->prepare("UPDATE `AttaquePersoCreature` SET `DegatsDonnes`=:DegatsDonnes WHERE idCreature=:idCreature AND idPersonnage=:idPersonnage");
                $req->execute(['DegatsDonnes' => $tabAttaque['DegatsDonnes'], 'idCreature' => $Creature->getIdEntite(), 'idPersonnage' => $this->_idEntite]);
            }
            return $this->_healthNow;
        }

        /** Add de l'Experience Personnage */ // À refaire
        public function addXP($value){
            $this->_expPersonnage += $value ;
            $req = $this->_bdd->prepare("UPDATE `Personnage` SET `expPersonnage`=:expPersonnage WHERE `idPersonnage`=:idPersonnage");
            $req->execute(['expPersonnage' => $this->_expPersonnage, 'idPersonnage' => $this->_idEntite]);
            //passage des Lvl suis une loi de racine carre
            /* le double etole ** c'est elevé à la puissance */
            $lvlEntite = ceil(($this->_expPersonnage/2000)**(0.7));
            if($lvlEntite >$this->_lvlEntite){
                $this->_lvlEntite = $lvlEntite;
                $req = $this->_bdd->prepare("UPDATE `Entite` SET `lvlEntite`=:lvlEntite WHERE `idEntite`=:idEntite");
                $req->execute(['lvlEntite' => $this->_lvlEntite, 'idEntite' => $this->_idEntite]);
            }
            return $this->_expPersonnage;
        }

        /** Fonction de Rennaisance : Réinitialisation HealthNow + Déplacement Spawn */
        public function resurection(){
            $healthMax = round($this->_healthMax - (($this->_healthMax*10)/100));
            $attaque = round($this->_degat - (($this->_degat*15)/100));
            if($healthMax<100){$healthMax=100;}
            if($attaque<10){$attaque=10;}
            $req = $this->_bdd->prepare("UPDATE `Entite` SET `degat`=:degat, `healthMax`=:healthMax, `healthNow`=:healthNow WHERE `idEntite`=:idEntite");
            $req->execute(['degat' => $attaque, 'healthMax' => $healthMax, 'healthNow' => $healthMax, 'idEntite' => $this->_idEntite]);
            $this->_healthNow=$healthMax;
            $this->_healthMax=$healthMax;
            $this->_degat=$attaque;
            $maporigine = new Map($this->_bdd);
            $maporigine->setMapByID($this->_idMapSpawnPersonnage);
            $this->changeMap($maporigine);
        }

        // Return Valeur
        public function getValeur(){
            $valeur = 0;
            foreach ($this->getItems() as $value){
                $valeur+=$value->getValeur();
            }
            foreach ($this->getEquipements() as $value){
                $valeur+=$value->getValeur();
            }
            return $valeur;
        }

        /** Return List Items */
        public function getItems(){
            $lists=array();
            foreach($this->sacItems as $ItemId){
                $newItem = new Item($this->_bdd);
                $newItem->setItemByID($ItemId);
                array_push($lists,$newItem);
            }
            return $lists;
        }

        /** Supprime Item du Sac Personnage et liste Items By ID */
        public function removeItemByID($idItem){
            unset($this->sacItems[array_search($idItem, $this->sacItems)]);
            $req = $this->_bdd->prepare("DELETE FROM `PersoSacItems` WHERE idPersonnage=:idPersonnage AND idItem=:idItem");
            $req->execute(['idPersonnage' => $this->getIdEntite(), 'idItem' => $idItem]);
            $req = $this->_bdd->prepare("DELETE FROM `Item` WHERE idItem=:idItem");
            $req->execute(['idItem' => $idItem]);
        }

        /** Crée Lien entre SacPersonnage et Items */
        public function addItem($newItem){
            array_push($this->sacItems,$newItem->getIdItem());
            $req = $this->_bdd->prepare("INSERT INTO `PersoSacItems`(`idPersonnage`, `idItem`) VALUES (:idPersonnage, :idItem)");
            $req->execute(['idPersonnage' => $this->getIdEntite(), 'idItem' => $newItem->getIdItem()]);
        }

        /** Return List HTML des Personnages d'un User et permet d'atribuer un perso à un User */
        public function getListPersonnage($User){
            if(isset($_POST["idPersonnage"])){
                $this->setPersonnageById($_POST["idPersonnage"]);
                $User->setPersonnage($this);
                if($this->_healthNow <= 0 ){
                    $this->resurection();
                }
            }
            $req = $this->_bdd->prepare("SELECT * FROM `Entite` WHERE idUser=:idUser AND idTypeEntite=1");
            $req->execute(['idUser' => $User->getIdUser()]);
            ?>
                <form action="" method="post" onchange="this.submit()">
                    <select name="idPersonnage" id="idPersonnage">
                    <option value="">Choisir un personnage</option>
                        <?php
                            while($tab = $req->fetch()){
                                ($tab['idEntite']==$this->_idEntite)?$selected='selected':$selected='';
                                echo '<option value="'.$tab["idEntite"].'" '.$selected.'> '.$tab["nameEntite"].'</option>';
                            }
                        ?>
                    </select>
                </form>
            <?php
        }

        /** Display les Actions Personnages Possibles */
        public function getActionsPerso($User){
            $TypeUser = new TypeUser($this->_bdd);
            $TypeUser->setTypeUserByIdUser($User->getIdUser());
            ?>
                <b class="">Actions :</b>
                <div class="Actions">
                    <?php
                        // Action Sanction
                        if($TypeUser->getPermPlay() == 0){
                            ?>
                                <p data-action="B00">Vous ne pouvez rien faire.</p>
                                <p data-action="B01">Vous ne pouvez rien faire.</p>
                            <?php
                        }
                        // Action Joueur
                        else{
                            ?>
                                <p data-action="J00">Fouiller</p>
                                <p data-action="J01">Se Cacher/Sortir</p>
                                <p data-action="J02">Fuir</p>
                                <p data-action="J03">Se téléporter à la Maison</p>
                            <?php
                        }
                        // Action ByPass
                        if($TypeUser->getPermBypass()){
                            ?>
                                <b class="">ByPass :</b>
                                <p data-action="T00">Régénérer Vie.</p>
                                <p data-action="T01">Régénérer Déplacement.</p>
                            <?php
                        }
                        // Action Staff
                        if($TypeUser->getPermStaff()){
                            ?>
                                <b class="">Staff :</b>
                                <p data-action="S00">Des actions Staff.</p>
                                <p data-action="S01">Des actions Staff.</p>
                            <?php
                        }
                        // Action Admin
                        if($TypeUser->getPermAdmin()){
                            ?>
                                <b class="">Admin :</b>
                                <p data-action="A00">Tuer tout les joueurs.</p>
                                <p data-action="A01">Tuer tout les monstres.</p>
                                <p data-action="A02">Détruire Objet au sol.</p>
                                <p data-action="A03">Se cacher (Admin).</p>
                                <p data-action="A04">Sortir Cachette (Admin).</p>
                                <p data-action="A05">Générer Objet.</p>
                                <p data-action="A06">Générer Monstre.</p>
                                <p data-action="A07">Se Téléporter.</p>
                            <?php
                        }
                    ?>
                </div>
                <script>
                    // Mise en place des Clicks Actions
                    var actionElements = document.querySelectorAll('.Actions p');
                    actionElements.forEach(function(element){
                        element.addEventListener('click', function(){
                            var action = element.getAttribute('data-action');
                            callAction(action);
                        });
                    });
                    // Fonction d'API Actions
                    function callAction(idAction){
                        fetch('api/useAction.php?idAction=' + idAction)
                        .then((resp) => resp.json())
                        // Réponse
                        .then(function(data){
                            console.log(data)
                            if(data[0] != 0){
                                switch(data[0]){
                                    <?php 
                                        if($TypeUser->getPermPlay() == 0){
                                            ?>
                                                case "B00":// Sanction 0
                                                    if(1){
                                                    }
                                                    break;
                                                case "B01":// Sanction 1
                                                    if(1){
                                                    }
                                                    break;
                                            <?php
                                        }
                                        if($TypeUser->getPermPlay() == 1){
                                            ?>
                                                case "J00":// Fouiller
                                                    break;
                                                case "J01":// Se cacher/Sortir
                                                    if(1){
                                                    }
                                                    break;
                                                case "J02":// Fuir
                                                    if(1){
                                                    }
                                                    break;
                                                case "J03":// Téléporte Home
                                                    break;
                                            <?php
                                        }
                                        if($TypeUser->getPermBypass()){
                                            ?>
                                                case "T00":// Régénérer Vie
                                                    if(1){
                                                    }
                                                    break;
                                                case "T01":// Régénérer Déplacement
                                                    if(1){
                                                    }
                                                    break;
                                            <?php
                                        }
                                        if($TypeUser->getPermStaff()){
                                            ?>
                                                case "S00":// Staff 0
                                                    if(1){
                                                    }
                                                    break;
                                                case "S01":// Staff 1
                                                    if(1){
                                                    }
                                                    break;
                                            <?php
                                        }
                                        if($TypeUser->getPermAdmin()){
                                            ?>
                                                case "A00":// Admin 0
                                                    if(1){
                                                    }
                                                    break;
                                                case "A01":// Admin 1
                                                    if(1){
                                                    }
                                                    break;
                                                case "A02":// Admin 1
                                                    if(1){
                                                    }
                                                    break;
                                                case "A03":// Admin 1
                                                    if(1){
                                                    }
                                                    break;
                                                case "A04":// Admin 1
                                                    if(1){
                                                    }
                                                    break;
                                                case "A05":// Admin 1
                                                    if(1){
                                                    }
                                                    break;
                                                case "A06":// Admin 1
                                                    if(1){
                                                    }
                                                    break;
                                                case "A07":// Admin 1
                                                    if(1){
                                                    }
                                                    break;
                                            <?php
                                        }
                                    ?>
                                        default:
                                            break;
                                }
                                log(data[9])
                            }
                        })
                        .catch(function(error){
                            console.log(error)
                        });
                    }
                </script>
            <?php
        }

        /** Affiche le rendu HTML du Personnage */
        public function displayHTML(){
            $Pourcentage = round(100*$this->_healthNow/$this->_healthMax); // Remettre en place le % de vie visible via le style
            $arme = $this->getArme();
            $armure = $this->getArmure();
            $TypePersonnage = $this->getTypePersonnage();
            ?>
                <div class="perso" id="Perso<?= $this->_idEntite ?>">
                    <div class="EntiteInfo">
                        <div class="EntiteName">
                            <p><?= $TypePersonnage->getNameTypePerso() ?> <?= $this->getNameEntite() ?></p>
                        </div>
                    </div>
                    <div class="divimgEntite">
                        <img class="imgEntite" src="<?= $this->_imgEntite ?>">
                    </div>
                    <div class="valuePerso">
                        <div class="backgroundAttaque">
                            <img class="imgAttaque" src="./css/epee.cur"/>
                            <p id="attaqueEntiteValeur<?= $this->_idEntite ?>"><?= $this->getAttaque() ?></p>
                            <?php
                                if(!is_null($arme)){
                                    ?>
                                        <div class="Arme" onclick="CallApiRemoveEquipementPerso(<?= $arme->getIdEquipement() ?>)">
                                            <p id="Arme<?= $arme->getIdEquipement() ?>"><?= $arme->getNameEquipement() ." LV ". $arme->getLvlEquipement() ?></p>
                                            <img id="imgArmePerso<?= $this->_idEntite ?>" class="imgHidden" src="<?= $arme->getImgEquipement() ?>"/>
                                        </div>
                                    <?php
                                }
                                else{
                                    ?>
                                        <div class="Arme">
                                            <p id="ArmePerso<?= $this->_idEntite ?>">Poigts</p>
                                            <img id="imgArmePerso<?= $this->_idEntite ?>" class="imgHidden" src=""/>
                                        </div>
                                    <?php
                                }
                            ?>
                        </div>
                        <div class="backgroundArmor">
                            <img class="imgArmor" src="./assets/image/armor.png"/>
                            <p id="defenseEntiteValeur<?= $this->_idEntite ?>"><?= $this->getDefense() ?></p>
                            <?php
                                if(!is_null($armure)){
                                    ?>
                                        <div class="ArmureNom" onclick="CallApiRemoveEquipementPerso(<?= $armure->getIdEquipement() ?>)">
                                            <p id="Armure<?= $armure->getIdEquipement() ?>"><?= $armure->getNameEquipement() ." LV ". $armure->getLvlEquipement() ?></p>
                                            <img id="imgArmurePerso<?= $this->_idEntite ?>" class="imgHidden" src="<?= $armure->getImgEquipement() ?>"/>
                                        </div>
                                    <?php
                                }
                                else{
                                    ?>
                                        <div class="ArmureNom">
                                            <p id="ArmurePerso<?= $this->_idEntite ?>">Tunique de base</p>
                                            <img id="imgArmurePerso<?= $this->_idEntite ?>" class="imgHidden" src=""/>
                                        </div>
                                    <?php
                                }
                            ?>
                        </div>
                    </div>
                    <div class="healthBar" id="healthEntite<?= $this->_idEntite ?>">
                        <div class="healthNow">
                            <p id="healthEntiteValeur<?= $this->_idEntite ?>">♥️ <?= $this->_healthNow ?> / <?= $this->_healthMax ?></p>
                        </div>
                    </div>
                </div>
            <?php
        }

        /** Return la liste ID des Entite Personnages (Pour le Classement) */
        public function getListIdPersonnage(){
            $req = $this->_bdd->prepare("SELECT * FROM `Entite` WHERE `idTypeEntite`=1 ORDER BY `lvlEntite`, `degat`, `healthMax`, `dateTimeEntite` LIMIT 100");
            $req->execute();
            return $req;
        }

        /** Get Personnage by Id Personnage (Pour le Classement) */
        public function GetInfoPersonnageById($idPersonnage){
            $req = $this->_bdd->prepare("SELECT * FROM `Personnage` WHERE idPersonnage=:idPersonnage");
            $req->execute(['idPersonnage' => $idPersonnage]);
            if($tab = $req->fetch()){
                return $tab;
            }
        }
    }
?>