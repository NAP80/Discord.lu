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
            $req = "SELECT * FROM `Personnage` WHERE idPersonnage = '".$idPersonnage."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $this->_idTypePersonnage    = $tab['idTypePersonnage'];
                $this->_levelPersonnage     = $tab['levelPersonnage'];
                $this->_expPersonnage       = $tab['expPersonnage'];
                $this->_moneyPersonnage     = $tab['moneyPersonnage'];
                $this->_idMapSpawnPersonnage= $tab['idMapSpawnPersonnage'];
                $this->_effectPersonnage    = $tab['effectPersonnage'];
            }
            //select les items déjà présent
            $req = "SELECT idItem FROM `PersoSacItems` WHERE idPersonnage = '".$idPersonnage."'";
            $Result = $this->_bdd->query($req);
            while($tab=$Result->fetch()){
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
            $req = "UPDATE `Entite` SET `healthNow`='".$this->_healthNow ."' WHERE `idEntite` = '".$this->_idEntite ."'";
            $Result = $this->_bdd->query($req);
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
                $req = "SELECT * FROM `AttaquePersoCreature` WHERE idCreature = '".$Creature->getIdEntite()."' and idPersonnage = '".$this->_idEntite."'";
                $Result = $this->_bdd->query($req);
                $tabAttaque['nbCoup']=0;
                $tabAttaque['DegatsDonnes']=$CreatureDegatAttaqueEnvoyer;
                if($tab=$Result->fetch()){
                    $tabAttaque = $tab;
                    $tabAttaque['DegatsDonnes']+=$CreatureDegatAttaqueEnvoyer;
                    $tabAttaque['nbCoup']++;
                }
                else{
                    //insertion d'une nouvelle attaque
                    $req="INSERT INTO `AttaquePersoCreature`(`idCreature`, `idPersonnage`, `nbCoup`, `coupFatal`, `DegatsDonnes`, `DegatsReçus`) 
                    VALUES (
                        '".$Creature->getIdEntite()."','".$this->_idEntite."',0,0,".$tabAttaque['DegatsReçus'].",0
                    )";
                    $Result = $this->_bdd->query($req);
                }
                $this->_healthNow = $this->_healthNow - $CreatureDegatAttaqueEnvoyer;
                if($this->_healthNow<0){
                    $this->_healthNow =0;
                    //on ne peut pas donner plus de degat que la HealthNow d'un perso
                    $tabAttaque['DegatsDonnes'] = $healthAvantAttaque;
                    //retour en zone 0,0
                }
                $req  = "UPDATE `Entite` SET `healthNow`='".$this->_healthNow ."' WHERE `idEntite` = '".$this->_idEntite ."'";
                $Result = $this->_bdd->query($req);
                //update AttaquePersoCreature pour mettre a jour combien le perso a pris de degat 
                $req="UPDATE `AttaquePersoCreature` SET 
                `DegatsDonnes`=".$tabAttaque['DegatsDonnes']."
                WHERE idCreature = '".$Creature->getIdEntite()."' AND idPersonnage ='".$this->_idEntite."' ";
                $Result = $this->_bdd->query($req);
            }
            return $this->_healthNow;
        }

        /** Add de l'Experience Personnage */ // À refaire
        public function addXP($value){
            $this->_expPersonnage += $value ;
            $req  = "UPDATE `Personnage` SET `expPersonnage`='".$this->_expPersonnage ."' WHERE `idPersonnage` = '".$this->_idEntite."'";
            $Result = $this->_bdd->query($req);
            //passage des Lvl suis une loi de racine carre
            /* le double etole ** c'est elevé à la puissance */
            $lvlEntite = ceil(($this->_expPersonnage/2000)**(0.7));
            if($lvlEntite >$this->_lvlEntite){
                $this->_lvlEntite = $lvlEntite;
                $req  = "UPDATE `Entite` SET `lvlEntite`='".$this->_lvlEntite."' WHERE `idEntite` = '".$this->_idEntite ."'";
                $Result = $this->_bdd->query($req);
            }
            return $this->_expPersonnage;
        }

        /** Fonction de Rennaisance : Réinitialisation HealthNow + Déplacement Spawn */
        public function resurection(){
            $healthMax = round($this->_healthMax - (($this->_healthMax*10)/100));
            $attaque = round($this->_degat - (($this->_degat*15)/100));
            if($healthMax<100){$healthMax=100;}
            if($attaque<10){$attaque=10;}
            $req = "UPDATE `Entite` SET `degat`='".$attaque."',`healthMax`='".$healthMax."',`healthNow`='".$healthMax."' WHERE `idEntite` = '".$this->_idEntite ."'";
            $Result = $this->_bdd->query($req);
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
            $req="DELETE FROM `PersoSacItems` WHERE idPersonnage = '".$this->getIdEntite()."' AND idItem='".$idItem."'";
            $this->_bdd->query($req);
            $req="DELETE FROM `Item` WHERE idItem='".$idItem."'";
            $this->_bdd->query($req);
        }

        /** Crée Lien entre SacPersonnage et Items */
        public function addItem($newItem){
            array_push($this->sacItems,$newItem->getIdItem());
            $req="INSERT INTO `PersoSacItems`(`idPersonnage`, `idItem`) VALUES ('".$this->getIdEntite()."','".$newItem->getIdItem()."')";
            $this->_bdd->query($req);
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
            $Result = $this->_bdd->query("SELECT * FROM `Entite` WHERE idUser='".$User->getIdUser()."' AND idTypeEntite=1");
            ?>
                <form action="" method="post" onchange="this.submit()">
                    <select name="idPersonnage" id="idPersonnage">
                    <option value="">Choisir un personnage</option>
                        <?php
                            while($tab=$Result->fetch()){
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
            if($TypeUser->getPermPlay()){
                ?>
                    <b class="">Actions :</b>
                <?php
            }
            else{
                ?>
                    <b class="">Vous ne pouvez rien faire.</b>
                <?php
            }
            if($TypeUser->getPermAdmin()){
                ?>
                    <b class="">Admin :</b>
                <?php
            }
            if($TypeUser->getPermStaff()){
                ?>
                    <b class="">Staff :</b>
                <?php
            }
            if($TypeUser->getPermBypass()){
                ?>
                    <b class="">ByPass :</b>
                <?php
            }
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
                                        <div id="Arme<?= $arme->getIdEquipement() ?>" class="Arme standard" onclick="CallApiRemoveEquipementEntite(<?= $arme->getIdEquipement() ?>)">
                                            <p>(<?= $arme->getNameEquipement() ?>)</p>
                                        </div>
                                    <?php
                                }
                                else{
                                    ?>
                                        <div id="ArmePerso<?= $this->_idEntite ?>" class="Arme">
                                            <p>(Poigts)</p>
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
                                        <div id ="Armure<?= $armure->getIdEquipement() ?>" class="ArmureNom standard" onclick="CallApiRemoveEquipementEntite(<?= $armure->getIdEquipement() ?>)">
                                            <p>(<?= $armure->getNameEquipement() ?>)</p>
                                        </div>
                                    <?php
                                }
                                else{
                                    ?>
                                        <div id ="ArmurePerso<?= $this->_idEntite ?>" class="ArmureNom">
                                            <p>(Tunique)</p>
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
    }
?>