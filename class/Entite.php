<?php
    // Beaucoup de Similitude entre Personnage/Entité -> Refactoriser avec héritage
    class Entite{
        public $_idEntite;
        public $_nameEntite;
        public $_healthNow;
        public $_healthMax;
        public $_degat;
        public $_imgEntite;
        public $_lvlEntite;
        public $_idUser;
        public $sacEquipements=array();
        public $sacEquipe=array();
        public $_idTypeEntite; // 0 = Monster / 1 = Personnage
        public $map;
        public $_bdd;

        public $_idTypePersonnage;
        public $TypePersonnage;

        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        public function setEntite($idEntite,$nameEntite,$healthNow,$degat,$healthMax,$imgEntite,$idTypeEntite,$lvlEntite,$idUser){
            $this->_idEntite = $idEntite;
            $this->_nameEntite = $nameEntite;
            $this->_healthNow = $healthNow;
            $this->_healthMax = $healthMax;
            $this->_degat = $degat;
            $this->_imgEntite = $imgEntite;
            $this->_idTypeEntite = $idTypeEntite;
            $this->_lvlEntite = $lvlEntite;
            $this->_idUser = $idUser;

            if($this->_idTypeEntite == 1){
                if(is_null($this->TypePersonnage)){
                    $TypePersonnage = new TypePersonnage($this->_bdd);
                    $TypePersonnage->setTypePersonnageByIdPerso($this->_idEntite);
                    $this->TypePersonnage = $TypePersonnage;
                    $this->_idTypePersonnage = $TypePersonnage->getIdTypePerso();
                }
            }
        }

        /** Récupère l'entitée par ID */
        public function setEntiteById($idEntite){
            $Result = $this->_bdd->query("SELECT * FROM `Entite` WHERE `idEntite`='".$idEntite."'");
            if($tab = $Result->fetch()){
                $this->setEntite($tab["idEntite"],$tab["nameEntite"],$tab["healthNow"],$tab["degat"],$tab["healthMax"],$tab["imgEntite"],$tab["idTypeEntite"],$tab["lvlEntite"],$tab["idUser"]);
                //recherche de sa position
                $map = new map($this->_bdd);
                $map->setMapByID($tab["idMap"]);
                $this->map = $map;
                //select les equipements déjà présent
                $req = "SELECT idEquipement FROM `EntiteEquipement` WHERE idEntite='".$idEntite."'";
                $Result = $this->_bdd->query($req);
                while($tab=$Result->fetch()){
                    array_push($this->sacEquipements,$tab[0]);
                }
                //select les Equipement déjà présent
                $req = "SELECT idEquipement,equipe FROM `EntiteEquipement` WHERE idEntite='".$idEntite."' AND equipe='1'";
                $Result = $this->_bdd->query($req);
                while($tab=$Result->fetch()){
                    if($tab['equipe']==1){
                        array_push($this->sacEquipe,$tab['idEquipement']);
                    }
                }
            }
        }

        public function setEntiteByIdWithoutMap($idEntite){
            $Result = $this->_bdd->query("SELECT * FROM `Entite` WHERE `idEntite`='".$idEntite."'");
            if($tab = $Result->fetch()){
                $this->setEntite($tab["idEntite"],$tab["nameEntite"],$tab["healthNow"],$tab["degat"],$tab["healthMax"],$tab["imgEntite"],$tab["idTypeEntite"],$tab["lvlEntite"],$tab["idUser"]);
            }
        }

        /** Return Nom */
        public function getNameEntite(){
            return $this->_nameEntite;
        }

        /** Return HealthNow */
        public function getHealthNow(){
            return $this->_healthNow;
        }

        /** Return HealthMax */
        public function getHealthMax(){
            return $this->_healthMax;
        }

        /** Return l'ID de l'entitée */
        public function getIdEntite(){
            return $this->_idEntite;
        }

        /** Return l'ID de sa position Map */
        public function getMap(){
            return $this->map;
        }

        /** Return Lvl */
        public function getIdTypeEntite(){
            return $this->_idTypeEntite;
        }

        /** Return Lvl */
        public function getLvlEntite(){
            return $this->_lvlEntite;
        }

        /** Return idUser */
        public function getIdUser(){
            return $this->_idUser;
        }

        /** Create Entite */
        public function CreateEntite($nameEntite, $healthNow, $degat, $idMap,$healthMax,$imgEntite,$idUser,$idTypeEntite,$lvlEntite){
            $newperso = new Entite($this->_bdd);
            $this->_nameEntite=htmlentities($nameEntite, ENT_QUOTES);
            $this->_lvlEntite = $lvlEntite;
            $this->_imgEntite=$imgEntite;
            $req="INSERT INTO `Entite`(`nameEntite`, `healthNow`, `degat`, `idMap`,`healthMax`,`imgEntite`,`idUser`,`idTypeEntite`,`lvlEntite`)
            VALUES ('".$this->_nameEntite."','.$healthNow.','.$degat.','.$idMap.','.$healthMax.','".$this->_imgEntite."','".$idUser."','.$idTypeEntite.','.$lvlEntite.')";
            $this->_bdd->beginTransaction();
            $Result = $this->_bdd->query($req);
            $this->_idEntite = $this->_bdd->lastInsertId();
            if($this->_idEntite){
                $newperso->setEntiteById($this->_idEntite);
                $this->_bdd->commit();
                return $newperso;
            }
            else{
                $this->_bdd->rollback();
                return null;
            }
            return null;
        }

        /** Affichage de HealthNow en Bar*/
        public function getHealthBar(){
            $pourcentage = round(100*$this->_healthNow/$this->_healthMax);
            ?>
                <div class="EntitePrincipalBarreHealth">
                    <div class="attaque" id="attaqueEntiteValeur<?= $this->_idEntite ;?>"> <?= $this->_degat ;?>  </div> 
                    <div class="healthBar" id="healthEntite<?= $this->_idEntite ;?>">
                        <div class="healthNow" id="healthEntiteValeur<?= $this->_idEntite ;?>" style="width:<?= $pourcentage?>%;">
                            ♥️<?= $this->_healthNow ?>
                        </div>
                    </div>
                </div>
            <?php
        }

        /** Fonction d'attaque */
        public function getAttaque(){
            $arme = $this->getArme();
            $pouvoir = $this->getPouvoir();
            $coef = 1;
            $lvlEquipement = 1;
            $forceArme = 0;
            if(!is_null($arme)){// Si utilise une arme
                $coef = $arme->getEfficacite();
                $forceArme = $arme->getForce();
                $lvlEquipement = $arme->getLvlEquipement();
            }
            if(!is_null($pouvoir)){// Si utilise la magie
                $coef = $pouvoir->getEfficacite();
                $forcePourvoir = $pouvoir->getForce();
                $lvlEquipement = $pouvoir->getLvlEquipement();
            }
            //application des coef si il y a nu type de personnage
            if($this->_idTypeEntite == 1){// Si n'utilise rien
                $TypePersonnage = $this->getTypePersonnage();
                if(!is_null($arme)){
                    $coef = $coef*$TypePersonnage->getStatsAttaque();
                }
            }
            $val = round(($this->_degat+$forceArme)*$coef);
            return $val;
        }

        /** Aucune idée de ce que c'est */
        public function getCoolDownAttaque(){
            $arme = $this->getArme();
            $pouvoir = $this->getPouvoir();
            $cooldown = -1;
            if(!is_null($arme)){
                $cooldown =$arme->getCoolDown();
            }
            else if(!is_null($pouvoir)){
                $cooldown = $pouvoir->getCoolDown();
            }
            else{
                $cooldown = 500;
            }
            return $cooldown;
        }

        /** Remove Équipement by ID */
        public function removeEquipementByID($EquipementID){
            $idindex = array_search($EquipementID, $this->sacEquipements);
            if($idindex  >= 0){
                unset($this->sacEquipements[$idindex]);
                $req="DELETE FROM `EntiteEquipement` WHERE idEntite='".$this->getIdEntite()."' AND idEquipement='".$EquipementID."'";
                $this->_bdd->query($req);

                //todo retirer un equipement ne doit pas etre une suppression
                //todo la suppression peut etre déjà faite à la fusion
                $req="DELETE FROM `Equipement` WHERE idEquipement='".$EquipementID."'";
                $this->_bdd->query($req);
            }
        }

        /** Add Equipement au Personnage et crée un lien en BDD */
        public function addEquipement($newEquipement){
            // Vérification si Fusion possible
            $TabIdRemoved = array();
            $newEquipement->fusionEquipement($this,$TabIdRemoved);
            if(count($TabIdRemoved) > 0){
                foreach($TabIdRemoved as $idSup){
                    $this->removeEquipementByID($idSup);
                }
                array_push($this->sacEquipements,$newEquipement->getIdEquipement());
                return $TabIdRemoved;
            }
            else{
                $req="INSERT INTO `EntiteEquipement`(`idEntite`, `idEquipement`) VALUES ('".$this->getIdEntite()."','".$newEquipement->getIdEquipement()."')";
                $this->_bdd->query($req);
                array_push($this->sacEquipements,$newEquipement->getIdEquipement());
                return 0;
            }
        }

        /** Equipe un Equipement */
        public function addEquipeById($EquipementID){
            array_push($this->sacEquipe,$EquipementID);
        }

        /** Déséquipe un Equipement */
        public function removeEquipeBydId($idEquipement){
            $idEquipement = array_search($idEquipement, $this->sacEquipe);
            if($idEquipement >= 0){
                unset($this->sacEquipe[$idEquipement]);
            }
        }

        /** Return les équipements non portés */
        public function getEquipementNonPorte(){
            //compare les 2tableau et retourne ce qui est pas commun
            $tab1 = $this->sacEquipements;
            $tab2 = $this->sacEquipe;
            //attention l'ordre des param est important 
            $tab3 = array_diff($tab1,$tab2);
            //compare les 2tableau et retourne ce qui est commun
            $lists=array();
            foreach($tab3 as $EquipementId){
                $newEquipement = new Equipement($this->_bdd);
                $newEquipement->setEquipementByID($EquipementId);
                array_push($lists,$newEquipement);
            }
            return $lists;
        }

        /** Return les équipements */
        public function getEquipements(){
            $listEquipement = array();
            foreach($this->sacEquipements as $EquipementId){
                $newEquipement = new Equipement($this->_bdd);
                $newEquipement->setEquipementByID($EquipementId);
                array_push($listEquipement,$newEquipement);
            }
            return $listEquipement;
        }

        //Retour un objet de type arme
        public function getArme(){
            $Arme = null;
            foreach($this->sacEquipe as $EquipementId){
                $EntiteEquipe = new Equipement($this->_bdd);
                $EntiteEquipe->setEquipementByID($EquipementId);
                if($EntiteEquipe->getIdCategorie() == 1){// Arme = 1
                    $Arme = new Arme($this->_bdd);
                    $Arme->setEquipementByID($EntiteEquipe->getIdEquipement());
                    return $Arme;
                }
            }
            return $Arme;
        }

        //Retour un objet de type armure 
        public function getArmure(){
            $Armure = null;
            foreach($this->sacEquipe as $EquipementId){
                $EntiteEquipe = new Equipement($this->_bdd);
                $EntiteEquipe->setEquipementByID($EquipementId);
                if($EntiteEquipe->getIdCategorie() ==2 ){// Armure = 2
                    $Armure = new Armure($this->_bdd);
                    $Armure->setEquipementByID($EntiteEquipe->getIdEquipement());
                    return $Armure;
                }
            }
            return $Armure;
        }

        //Retour un objet de type pouvoir
        public function getPouvoir(){
            $Pouvoir = null;
            foreach($this->sacEquipe as $EquipementId){
                $EntiteEquipe = new Equipement($this->_bdd);
                $EntiteEquipe->setEquipementByID($EquipementId);
                if($EntiteEquipe->getIdCategorie() == 3){ // Pouvoir = 3
                    $Pouvoir = new Pouvoir($this->_bdd);
                    $Pouvoir->setEquipementByID($EntiteEquipe->getIdEquipement());
                    return $Pouvoir;
                }
            }
            return $Pouvoir;
        }

        //Retour un objet de type bouclier
        public function getBouclier(){
            $Pouvoir = null;
            foreach($this->sacEquipe as $EquipementId){
                $EntiteEquipe = new Equipement($this->_bdd);
                $EntiteEquipe->setEquipementByID($EquipementId);
                if($EntiteEquipe->getIdCategorie() == 4){ // Bouclier = 4
                    $Bouclier = new Bouclier($this->_bdd);
                    $Bouclier->setEquipementByID($EntiteEquipe->getIdEquipement());
                    return $Bouclier;
                }
            }
        }

        /** Déséquipe Arme */
        public function desequipeArme(){
            $arme = $this->getArme();
            if(!is_null($arme)){
                $arme->desequipeEntite($this);
            }
        }

        /** Déséquipe Armure */
        public function desequipeArmure(){
            $armure = $this->getArmure();
            if(!is_null($armure)){
                $armure->desequipeEntite($this);
            }
        }

        /** Déséquipe Pouvoir */
        public function desequipePouvoir(){
            $pouvoir = $this->getPouvoir();
            if(!is_null($pouvoir)){
                $pouvoir->desequipeEntite($this);
            }
        }

        /** Déséquipe Bouclier */
        public function desequipeBouclier(){
            $bouclier = $this->getBouclier();
            if(!is_null($bouclier)){
                $bouclier->desequipeEntite($this);
            }
        }

        //Fonction Sort utilise un Pouvoir
        public function getSort(){
            //ici on affiche les dégats Maximun du joueur avec Arme
            $pouvoir = $this->getPouvoir();
            $coef = 1;
            $lvlEquipement = 1;
            $forcePouvoir = 0;
            if(!is_null($pouvoir)){
                $coef = $pouvoir->getEfficacite();
                $forcePourvoir = $pouvoir->getForce();
                $lvlEquipement = $pouvoir->getLvlEquipement();
            }
            $val = round(($this->_degat+$forcePouvoir)*$coef);
            return $val;
        }

        //Fonction Défense utilise une Armure
        public function getDefense(){
            //cas 
            //ici on affiche les dégats Maximun Absorbé avec une armure
            $armure = $this->getArmure();
            $bouclier = $this->getBouclier();
            $coef = 1;
            $forceArmure = 0;
            if(!is_null($armure)){
                $coef = $armure->getEfficacite();
                $forceArmure = $armure->getForce();
            }
            if(!is_null($bouclier)){
                $coef = $bouclier->getEfficacite();
                $forceBouclier = $bouclier->getForce();
            }
            if($this->_idTypeEntite == 1){
                $TypePersonnage = $this->getTypePersonnage();
                if(!is_null($armure)){
                    $coef = $coef*$TypePersonnage->getStatsDefense();
                }
                if(!is_null($bouclier)){
                    $coef = $coef*$TypePersonnage->getStatsRessMagique();
                }
            }
            $val = $coef * $forceArmure ;
            return round($val,1);//arrondi à 1 chiffre aprés la virgul
        }

        //Fonction Parer utilise un bouclier
        public function getParer(){
            //ici on affiche les dégats Maximun Absorbé avec une armure
            $bouclier = $this->getBouclier();
            $coef = 1;
            $forceBouclier = 0;
            if(!is_null($bouclier)){
                $coef = $bouclier->getEfficacite();
                $forceBouclier = $bouclier->getForce();
            }
            //alors Todo Je sais pas ... evaluer la valeur d'une armure
            $val = $coef * $forceBouclier ;
            return round($val,1);//arrondi à 1 chiffre aprés la virgul
        }

        /* Fin Cauet */

        public function getDegat(){
            //doit retourner des degat que l'entite donne a l'instant t
            return $this->_degat;
        }
        
        //il n'est possible de booster la HealthNow au dela de HealthMax
        public function SoinPourcentage($pourcentage){
            $valeur = round(($this->_healthMax*$pourcentage)/100);
            $this->_healthNow = $valeur+ $this->_healthNow;
            if($this->_healthNow>$this->_healthMax){
                $this->_healthNow = $this->_healthMax;
            }
            $req = "UPDATE `Entite` SET `healthNow`='".$this->_healthNow."' WHERE `idEntite` = '".$this->_idEntite."'";
            $Result = $this->_bdd->query($req);
            return $valeur;
        }

        public function SubitDegatByEntite($Entite){
            //nouveauté 2022 JLA on va gerer les dégat selon le type d'équipement
            //get Attaque sera modifier
            $MonsterDegatAttaqueEnvoyer=$Entite->getAttaque();
            //Mise en place de la defence.
            $MonsterDegatAttaqueEnvoyer -= round(($MonsterDegatAttaqueEnvoyer * $this->getDefense())/100,1);
    
            $this->_healthNow = $this->_healthNow - $MonsterDegatAttaqueEnvoyer;
            if($this->_healthNow<0){
                $this->_healthNow =0;
                //retour en zone 0,0
            }
            $req = "UPDATE `Entite` SET `healthNow`='".$this->_healthNow."' WHERE `idEntite` = '".$this->_idEntite."'";
            $Result = $this->_bdd->query($req);
            return $this->_healthNow;
        }

        public function getAllMyMonsterIdByMap($map){
            $listMonster=array();
            $req="SELECT `idEntite` FROM `Entite` WHERE `idUser` = '".$this->_idEntite."' AND `idMap` = '".$map->getIdMap()."')";
            $Result = $this->_bdd->query($req);
            while($tab=$Result->fetch()){
                array_push($listMonster,$tab);
            }
            return $listMonster;
        }

        public function SubitDegatByMonster($Monster){
            $MonsterDegatAttaqueEnvoyer=$Monster->getAttaque();
            //Mise en place de la defence.
            $MonsterDegatAttaqueEnvoyer -= round(($MonsterDegatAttaqueEnvoyer * $this->getDefense())/100,1);

            $healthAvantAttaque = $this->_healthNow;
            //on va rechercher l'historique
            $req = "SELECT * FROM `AttaqueEntiteMonster` WHERE idMonster = '".$Monster->getIdEntite()."' and idEntite = '".$this->_idEntite."'";
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
                $req="INSERT INTO `AttaqueEntiteMonster`(`idMonster`, `idEntite`, `nbCoup`, `coupFatal`, `DegatsDonnes`, `DegatsReçus`) 
                VALUES (
                    '".$Monster->getIdEntite()."','".$this->_idEntite."',0,0,".$tabAttaque['DegatsReçus'].",0
                )";
                $Result = $this->_bdd->query($req);
            }

            $this->_healthNow = $this->_healthNow - $MonsterDegatAttaqueEnvoyer;
            if($this->_healthNow<0){
                $this->_healthNow=0;
                //on ne peut pas donner plus de degat que la HealthNow d'un perso
                $tabAttaque['DegatsDonnes'] = $healthAvantAttaque;
                //retour en zone 0,0
            }
            $req = "UPDATE `Entite` SET `healthNow`='".$this->_healthNow ."' WHERE `idEntite` = '".$this->_idEntite."'";
            $Result = $this->_bdd->query($req);
            //update AttaqueEntiteMonster pour mettre a jour combien le perso a pris de degat 
            $req="UPDATE `AttaqueEntiteMonster` SET 
            `DegatsDonnes`=".$tabAttaque['DegatsDonnes']." 
            WHERE idMonster = '".$Monster->getIdEntite()."' AND idEntite ='".$this->_idEntite."'";
            $Result = $this->_bdd->query($req);
            return $this->_healthNow;
        }

        public function resetCoolDown(){
            $arme = $this->getArme();
            $pouvoir = $this->getPouvoir();
            
            if(!is_null($arme)){
                $cooldown =$arme->resetCoolDown();
            }
            else if(!is_null($pouvoir)){
                $cooldown = $pouvoir->resetCoolDown();
            }
            
        }

        /** Return Type Personnage */
        public function getTypePersonnage(){
            if(!is_null($this->_idTypePersonnage)){
                if(is_null($this->TypePersonnage)){
                    $TypePersonnage = new TypePersonnage($this->_bdd);
                    $TypePersonnage->setTypePersonnageByIdPerso($this->_idEntite);
                    $this->TypePersonnage = $TypePersonnage;
                }
                return $this->TypePersonnage;
            }
            else{
                return null; // Ne devrait pas avoir lieu
            }
        }

        /** Fonction de Rennaisance : Réinitialisation HealthNow */
        public function resurection(){
            $healthMax = intdiv ($this->_healthMax,2);
            $attaque = intdiv ($this->_healthMax,2);
            if($healthMax<10){$healthMax=10;}
            $req = "UPDATE `Entite` SET `degat`='".$attaque."',`healthMax`='".$healthMax."',`healthNow`='".$healthMax."' WHERE `idEntite` = '".$this->_idEntite."'";
            $Result = $this->_bdd->query($req);
            $this->_healthNow=$healthMax;
            $this->_healthMax=$healthMax;
            $this->_degat=$attaque;
            if($this->_idTypeEntite == 1){
                $Personnage = new Personnage($this->_bdd);
                $Personnage->setPersonnageById($this->_idEntite);
                $maporigine = new Map($this->_bdd);
                $maporigine->setMapByID($Personnage->getIdMapSpawnPersonnage());
                $this->changeMap($maporigine);
            }
        }

        //retourne un entier de toutes ses valeurs
        public function getValeur(){
            $valeur = 0;
            foreach($this->getEquipements() as $value){
                $valeur+=$value->getValeur();
            }
            //$valeur = 100;
            return $valeur;
        }

        /** Affiche le rendu HTML de l'entité */ // À Dégager et faire une version pour Monstre et une pour Personnage
        public function renderHTML(){
            if($this->_healthMax<0 || $this->_healthMax=="0"){
                $this->_healthMax=10;
            }
            $pourcentage = round(100*$this->_healthNow/$this->_healthMax);
            $arme = $this->getArme();
            $pouvoir = $this->getPouvoir();
            if($this->_idTypeEntite == 1){
                $TypePersonnage = $this->getTypePersonnage();
            }
            ?>
                <div class="EntiteInfo">
                    <div class="EntiteName">
                        <?= $this->getNameEntite() ?>
                    </div>
                    <div class="EntiteValeur">
                        (<?= $this->getValeur() ?> $) LV <?= $this->_lvlEntite ?>
                    </div>
                </div>
                <div>
                    <img class="Entite" src="<?= $this->_imgEntite;?>">
                </div>
            <?php 
            if(!is_null($arme)){
                ?>
                    <div class="attaque standard" id="attaqueEntiteValeur<?= $this->_idEntite ;?>"> <?= $this->getAttaque()?>
                        <div class="coef">
                            (*<?php 
                                if(!is_null($TypePersonnage)){
                                    echo $TypePersonnage->getStatsAttaque();
                                }
                                else{
                                    echo "1";
                                }
                            ?>)
                        </div>
                    </div>
                    <div id="Arme<?= $arme->getIdEquipement() ?>" class="Arme standard" onclick="CallApiRemoveEquipementEntite(<?= $arme->getIdEquipement() ?>)"><?= $arme->getNameEquipement() ?> lvl <?= $arme->getLvlEquipement() ?></div>
                <?php
            }
            else{
                ?>
                    <div class="attaque" id="attaqueEntiteValeur<?= $this->_idEntite ;?>">
                        <?= $this->getAttaque()?>
                    </div>
                    <div id="ArmePerso<?= $this->_idEntite ?>" class="Arme">
                    </div>
                <?php
            }
            $armure = $this->getArmure();
            $bouclier = $this->getBouclier();
            if(!is_null($armure)){
                ?>
                    <div id ="Armure<?= $armure->getIdEquipement() ?>" class="ArmureNom standard" onclick="CallApiRemoveEquipementEntite(<?= $armure->getIdEquipement() ?>)"><?= $armure->getNameEquipement() ?> lvl <?= $armure->getLvlEquipement() ?>
                        <div class="coef">
                            (*<?php 
                                if(!is_null($TypePersonnage)){
                                    echo $TypePersonnage->getStatsDefense();
                                }
                                else{
                                    echo "1";
                                }
                            ?>)
                        </div>
                    </div>
                <?php
            }
            else if(!is_null($bouclier)){
                ?>
                <div id ="Armure<?= $bouclier->getIdEquipement() ?>" class="ArmureNom magic" onclick="CallApiRemoveEquipementEntite(<?= $bouclier->getIdEquipement() ?>)"><?= $bouclier->getNameEquipement() ?> lvl <?= $bouclier->getLvlEquipement() ?>
                    <div class="coef">
                        (*<?php 
                            if(!is_null($TypePersonnage)){
                                echo $TypePersonnage->getStatsRessMagique();
                            }
                            else{
                                echo "1";
                            }
                        ?>
                    )</div>
                </div>
                <?php
            }
            else{
                ?>
                    <div id ="ArmurePerso<?= $this->_idEntite ?>" class="ArmureNom"></div>
                <?php
            }
            ?>
                <div class="healthBar" id="healthEntite<?= $this->_idEntite ?>">
                    <div class="healthNow" id="healthEntiteValeur<?= $this->_idEntite ?>" style="width:<?= $pourcentage ?>%;">♥️<?= $this->_healthNow ?>
                    </div>
                    <div class="armureAll">
                        <div class="armure" id="defenseEntiteValeur<?= $this->_idEntite ?>"
                            <?php
                                if(!is_null($armure)){
                                    ?>
                                        style="width:<?= $this->getDefense() ?>%;"><?= $this->getDefense() ?>
                                    <?php
                                }
                                else{
                                    ?>
                                        >
                                    <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div>
                    <?php 
                        if($this->_idTypeEntite == 1){
                            $TypePersonnage = $this->getTypePersonnage();
                            echo $TypePersonnage->getNameTypePerso();
                        }
                    ?>
                </div>
            <?php
        }

        public function lvlupAttaque($attaque){
            $this->_degat += $attaque;
            $sql = "UPDATE `Entite` SET `degat`='".$this->_degat."' WHERE `idEntite`='".$this->_idEntite."'";
            $this->_bdd->query($sql);
        }
        public function lvlupHealthNow($healthmore){
            $this->_healthNow += $healthmore;
            $sql = "UPDATE `Entite` SET `healthNow`='".$this->_healthNow."' WHERE `idEntite`='".$this->_idEntite."'";
            $this->_bdd->query($sql);
        }
        public function lvlupHealthMax($healthmore){
            $this->_healthMax += $healthmore;
            $sql = "UPDATE `Entite` SET `healthMax`='".$this->_healthMax."' WHERE `idEntite`='".$this->_idEntite."'";
            $this->_bdd->query($sql);
        }

        /** Déplacement de l'entitée sur la Map */
        public function changeMap($NewMap){
            $this->map = $NewMap;
            $sql = "UPDATE `Entite` SET `idMap`='".$NewMap->getIdMap()."' WHERE `idEntite`='".$this->_idEntite."'";
            $this->_bdd->query($sql);
        }

        public function generateImage($Nom){ // A check
            $space = array(" ", ".", "_", "-", "%");
            $onlyconsonants = str_replace($space, "+", $Nom);
            $topic='+personage+'.$onlyconsonants.'+fanart';
            $ofs=mt_rand(0, 100);
            $geturl='https://www.bing.com/images/search?q=' . $topic . '&first=' . $ofs . '&tsc=ImageHoverTitle';
            $data=file_get_contents($geturl);
            //echo $data;
            //partialString1 is bigger link.. in it will be a scr for the beginning of the url
            $f1='<div class="img_cont hoff">';
            $pos1=strpos($data, $f1)+strlen($f1);
            $partialString1 = substr($data, $pos1);
            $f1bis='src="';
            $pos1=strpos($partialString1, $f1bis)+strlen($f1bis);
            $partialString1 = substr($partialString1, $pos1);
            //PartialString3 ends the url when it sees the "&amp;"
            $f3='"';
            $urlLength=strpos($partialString1, $f3);
            $partialString3 = substr($partialString1, 0, $urlLength);
            return $partialString3;
        }
    }
?>