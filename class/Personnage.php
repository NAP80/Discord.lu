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

        /** Set Personnage by ID Personnage sur Map */
        public function setPersonnageByIdWithoutMap($idPersonnage){
            Parent::setEntiteByIdWithoutMap($idPersonnage);
            $req = "SELECT * FROM `Personnage` WHERE idPersonnage='".$idPersonnage."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $this->_idTypePersonnage    = $tab['idTypePersonnage'];
                $this->_levelPersonnage     = $tab['levelPersonnage'];
                $this->_expPersonnage       = $tab['expPersonnage'];
                $this->_moneyPersonnage     = $tab['moneyPersonnage'];
                $this->_idMapSpawnPersonnage= $tab['idMapSpawnPersonnage'];
                $this->_effectPersonnage    = $tab['effectPersonnage'];
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
            $dammage -=  ($dammage*$this->getDefense()) / 100;
            $dammage = round($dammage);
            if($dammage<0){
                $dammage = 0;
            }
            $this->_healthNow = $this->_healthNow - $dammage;
            if($this->_healthNow < 1){
                $this->_healthNow = 1;
            }
            $req  = "UPDATE `Entite` SET `healthNow`='".$this->_healthNow ."' WHERE `idEntite` = '".$this->_idEntite ."'";
            $Result = $this->_bdd->query($req);
            return $this->_healthNow;
        }

        //todo peut etre factoriser dans la class mère Entite
        /** Personnage Take dammage by Personnage*/
        public function SubitDegatByMonster($Monster){
            //Attente de pull qui marche
            //Si le Monster attaquant a plus de O PV, il attaque
            if($Monster->getHealthNow() > 0){
                $MonsterDegatAttaqueEnvoyer=$Monster->getAttaque();
                //on réduit les déga avec armure si possible
                $enMoins = ($MonsterDegatAttaqueEnvoyer*$this->getDefense())/100;
                $MonsterDegatAttaqueEnvoyer-=$enMoins;
                $MonsterDegatAttaqueEnvoyer = round($MonsterDegatAttaqueEnvoyer);
                if($MonsterDegatAttaqueEnvoyer<0){
                    $MonsterDegatAttaqueEnvoyer = 0;
                }
                $healthAvantAttaque = $this->_healthNow;
                //on va rechercher l'historique
                $req = "SELECT * FROM `AttaquePersoMonster` WHERE idMonster = '".$Monster->getIdEntite()."' and idPersonnage = '".$this->_idEntite."'";
                $Result = $this->_bdd->query($req);
                $tabAttaque['nbCoup']=0;
                $tabAttaque['DegatsDonnes']=$MonsterDegatAttaqueEnvoyer;
                if($tab=$Result->fetch()){
                    $tabAttaque = $tab;
                    $tabAttaque['DegatsDonnes']+=$MonsterDegatAttaqueEnvoyer;
                    $tabAttaque['nbCoup']++;
                }
                else{
                    //insertion d'une nouvelle attaque
                    $req="INSERT INTO `AttaquePersoMonster`(`idMonster`, `idPersonnage`, `nbCoup`, `coupFatal`, `DegatsDonnes`, `DegatsReçus`) 
                    VALUES (
                        '".$Monster->getIdEntite()."','".$this->_idEntite."',0,0,".$tabAttaque['DegatsReçus'].",0
                    )";
                    $Result = $this->_bdd->query($req);
                }
                $this->_healthNow = $this->_healthNow - $MonsterDegatAttaqueEnvoyer;
                if($this->_healthNow<0){
                    $this->_healthNow =0;
                    //on ne peut pas donner plus de degat que la HealthNow d'un perso
                    $tabAttaque['DegatsDonnes'] = $healthAvantAttaque;
                    //retour en zone 0,0
                }
                $req  = "UPDATE `Entite` SET `healthNow`='".$this->_healthNow ."' WHERE `idEntite` = '".$this->_idEntite ."'";
                $Result = $this->_bdd->query($req);
                //update AttaquePersoMonster pour mettre a jour combien le perso a pris de degat 
                $req="UPDATE `AttaquePersoMonster` SET 
                `DegatsDonnes`=".$tabAttaque['DegatsDonnes']."
                WHERE idMonster = '".$Monster->getIdEntite()."' AND idPersonnage ='".$this->_idEntite."' ";
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
            $req = "UPDATE `Entite` SET `degat`='".$attaque."',`healthMax`='".$healthMax."',`healthNow`='".$healthMax."' WHERE `idEntite` = '".$this->_idEntite ."'";
            $Result = $this->_bdd->query($req);
            $this->_healthNow=$healthMax;
            $this->_healthMax=$healthMax;
            $this->_degat=$attaque;
            $maporigine = new Map($this->_bdd);
            $maporigine->setMapByID($this->_idMapSpawnPersonnage);
            $this->changeMap($maporigine);
        }

        //retourne un entier de toutes ses valeurs
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

        /** Affiche le rendu HTML du personnage */ // À Refaire
        public function renderHTML(){
        ?>
            <div class="perso" id="PersoEnCours<?= $this->_idEntite ?>">
                <div class="persoXP">
                    <?= $this->_expPersonnage?> (Exp)
                </div>
                <?php
                    Parent::renderHTML();
                ?>
            </div>
        <?php
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
    }
?>