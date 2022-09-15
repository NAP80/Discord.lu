<?php
    class Personnage extends Entite{
        private $_xp;
        private $sacItems=array();

        public function __construct($bdd){
            Parent::__construct($bdd);
        }

        /** Set Xp Personnage */
        public function setXp($valeurXp){
            $req = $this->_bdd->prepare("UPDATE Personnage SET xp = ? WHERE id = ?");
            $req->execute(array($valeurXp, $this->_id));
            $this->_xp = $valeurXp;
        }

        /** Reinitialise Xp Personnage */
        public function deleteXp(){
            $req = $this->_bdd->prepare("UPDATE Personnage SET xp = 0 WHERE id = ?");
            $req->execute(array($this->_id));
        }

        /** Return Xp Personnage */
        public function getXp(){
            $req = $this->_bdd->prepare("SELECT xp FROM Personnage WHERE id = ?");
            $req->execute(array($this->_id));
            $xp = $req->fetch();
            return $xp;
        }

        public function SubitDegatByPersonnage($Personnage){
            $degat = $Personnage->getAttaque();
            //on réduit les déga avec armure si possible
            $degat-=($degat*$this->getDefense())/100;
            $degat = round($degat);
            if($degat<0){
                $degat = 0;
            }
            $this->_vie = $this->_vie - $degat;
            if($this->_vie<0){
                $this->_vie =0;
                //retour en zone 0,0
            }
            $req  = "UPDATE `Entite` SET `vie`='".$this->_vie ."' WHERE `id` = '".$this->_id ."'";
            $Result = $this->_bdd->query($req);
            return $this->_vie;
        }

        //todo peut etre factoriser dans la class mère Entite
        public function SubitDegatByMonster($Monster){
            //Attente de pull qui marche
            //Si le Monster attaquant a plus de O PV, il attaque
            if($Monster->getVie() > 0){
                $MonsterDegatAttaqueEnvoyer=$Monster->getAttaque();
                //on réduit les déga avec armure si possible
                $enMoins = ($MonsterDegatAttaqueEnvoyer*$this->getDefense())/100;
                $MonsterDegatAttaqueEnvoyer-=$enMoins;
                $MonsterDegatAttaqueEnvoyer = round($MonsterDegatAttaqueEnvoyer);
                if($MonsterDegatAttaqueEnvoyer<0){
                    $MonsterDegatAttaqueEnvoyer = 0;
                }
                $vieAvantAttaque = $this->_vie;
                //on va rechercher l'historique
                $req  = "SELECT * FROM `AttaquePersoMonster` where idMonster = '".$Monster->getId()."' and idPersonnage = '".$this->_id."'";
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
                        '".$Monster->getId()."','".$this->_id."',0,0,".$tabAttaque['DegatsReçus'].",0
                    )";
                    $Result = $this->_bdd->query($req);
                }
                $this->_vie = $this->_vie - $MonsterDegatAttaqueEnvoyer;
                if($this->_vie<0){
                    $this->_vie =0;
                    //on ne peut pas donner plus de degat que la vie d'un perso
                    $tabAttaque['DegatsDonnes'] = $vieAvantAttaque;
                    //retour en zone 0,0
                }
                $req  = "UPDATE `Entite` SET `vie`='".$this->_vie ."' WHERE `id` = '".$this->_id ."'";
                $Result = $this->_bdd->query($req);
                //update AttaquePersoMonster pour mettre a jour combien le perso a pris de degat 
                $req="UPDATE `AttaquePersoMonster` SET 
                `DegatsDonnes`=".$tabAttaque['DegatsDonnes']."
                WHERE idMonster = '".$Monster->getId()."' AND idPersonnage ='".$this->_id."' ";
                $Result = $this->_bdd->query($req);
            }
            return $this->_vie;
        }

        /** Add de l'Xp Personnage */
        public function addXP($value){
            $this->_xp += $value ;
            $req  = "UPDATE `Personnage` SET `xp`='".$this->_xp ."' WHERE `id` = '".$this->_id ."'";
            $Result = $this->_bdd->query($req);
            //passage des Lvl suis une loi de racine carre
            /* le double etole ** c'est elevé à la puissance */
            $lvl = ceil(($this->_xp/2000)**(0.7));
            if($lvl >$this->_lvl){
                $this->_lvl = $lvl;
                $req  = "UPDATE `Entite` SET `lvl`='".$this->_lvl."' WHERE `id` = '".$this->_id ."'";
                $Result = $this->_bdd->query($req);
            }
            return $this->_xp;
        }

        /** Fonction de Rennaisance : Réinitialisation Vie + Déplacement Spawn */
        public function resurection(){
            $vieMax = round($this->_vieMax - (($this->_vieMax*10)/100));
            $attaque = round($this->_degat - (($this->_degat*15)/100));
            if($vieMax<10){$vieMax=100;}
            $req = "UPDATE `Entite` SET `degat`='".$attaque."',`vieMax`='".$vieMax."',`vie`='".$vieMax."' WHERE `id` = '".$this->_id ."'";
            $Result = $this->_bdd->query($req);
            $this->_vie=$vieMax;
            $this->_vieMax=$vieMax;
            $this->_degat=$attaque;
            $maporigine = new Map($this->_bdd);
            // TODO : Récupérer point de Spawn du personnage pour pouvoir le changer à chaque ville.
            $Personnage_Spawn = 1;
            $maporigine->setMapByID($Personnage_Spawn);
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
            return  $valeur;
        }

        /** Affiche le rendu HTML du personnage */
        public function renderHTML(){
        ?>
            <div class="perso" id="PersoEnCours<?= $this->_id ?>">
                <div class="persoXP">
                    <?= $this->_xp?> (xp)
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
            foreach ($this->sacItems as $ItemId){
                $newItem = new Item($this->_bdd);
                $newItem->setItemByID($ItemId);
                array_push($lists,$newItem);
            }
            return $lists;
        }

        /** Attribue un idFaction Default à un personnage  : À dégager */
        public function ChangeFactionById($id){
            $Result = $this->_bdd->query("SELECT * FROM `TypePersonnage` WHERE idFaction = '".$id."'");
            if($tab = $Result->fetch()){
                $TypePersonnage = new TypePersonnage($this->_bdd);
                $TypePersonnage->setTypePersonnageByIdPerso($tab['id']);
                $this->ChangeTypePersonnage($TypePersonnage);
            }
        }

        /** Change l'Id Type Personnage à l'objet en cours */
        public function ChangeTypePersonnage($TypePersonnage){
            $this->_idTypePersonnage = $TypePersonnage->getId();
        }

        /** Retourne les information Faction propre au Type Personnage : À dégager */
        public function getIdFaction(){
            $req = "SELECT * FROM `TypePersonnage` WHERE id = '".$this->_idTypePersonnage."'";
            $Result = $this->_bdd->query($req);
            if($tab = $Result->fetch()){
                $req = "SELECT * FROM `Faction` WHERE id = '".$tab['idFaction']."'";
                $Result2 = $this->_bdd->query($req);
                if($tab2 = $Result2->fetch()){
                    $faction = new Faction($this->_bdd);
                    return $faction->setFactionById($tab2['id']);
                }
            }
        }

        public function setPersonnageByIdWithoutMap($id){
            Parent::setEntiteByIdWithoutMap($id);
            $req  = "SELECT * FROM `Personnage` WHERE id='".$id."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $this->_xp  = $tab['xp'];
                $this->_idTypePersonnage  = $tab['idTypePersonnage'];
            }
            else{
                return null;
            }
        }

        public function setPersonnageById($id){
            Parent::setEntiteById($id);
            //select les info personnage
            $req  = "SELECT * FROM `Personnage` WHERE id='".$id."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $this->_xp  = $tab['xp'];
                $this->_idTypePersonnage  = $tab['idTypePersonnage'];
            }
            else{
                return null;
            }
            //select les items déjà présent
            $req  = "SELECT idItem FROM `PersoSacItems` WHERE idPersonnage='".$id."'";
            $Result = $this->_bdd->query($req);
            while($tab=$Result->fetch()){
                array_push($this->sacItems,$tab[0]);
            }
        }

        /** Supprime Item du Sac Personnage et liste Items By ID */
        public function removeItemByID($id){
            unset($this->sacItems[array_search($id, $this->sacItems)]);
            $req="DELETE FROM `PersoSacItems` WHERE idPersonnage='".$this->getId()."' AND idItem='".$id."'";
            $this->_bdd->query($req);
            $req="DELETE FROM `Item` WHERE id='".$id."'";
            $this->_bdd->query($req);
        }

        /** Crée Lien entre SacPersonnage et Items */
        public function addItem($newItem){
            array_push($this->sacItems,$newItem->getId());
            $req="INSERT INTO `PersoSacItems`(`idPersonnage`, `idItem`) VALUES ('".$this->getId()."','".$newItem->getId()."')";
            $this->_bdd->query($req);
        }

        /** Return List HTML des Personnages d'un User et permet d'atribuer un perso à un User */
        public function getListPersonnage($User){
            if(isset($_POST["idPersonnage"])){
                $this->setPersonnageById($_POST["idPersonnage"]);
                $User->setPersonnage($this);
                if($this->_vie <= 0 ){
                    $this->resurection();
                }
            }
            $Result = $this->_bdd->query("SELECT * FROM `Entite` where idUser='".$User->getId()."' AND type=1");
            ?>
                <form action="" method="post" onchange="this.submit()">
                    <select name="idPersonnage" id="idPersonnage">
                    <option value="">Choisir un personnage</option>
                        <?php
                            while($tab=$Result->fetch()){
                                ($tab['id']==$this->_id)?$selected='selected':$selected='';
                                echo '<option value="'.$tab["id"].'" '.$selected.'> '.$tab["nom"].'</option>';
                            }
                        ?>
                    </select>
                </form>
            <?php
        }
    }
?>