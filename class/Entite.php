<?php
    // Beaucoup de Similitude entre Personnage/Entité -> Refactoriser avec héritage
    class Entite{
        public $_idEntite;
        public $_nameEntite;
        public $_healthNow;
        public $_healthMax;
        public $_degat;
        public $_imgEntite;
        public $_lvlEntite; // À dégager
        public $_dateTimeEntite;
        public $_idUser;
        public $sacEquipements=array();
        public $sacEquipe=array();
        public $_idTypeEntite; // 0 = Creature / 1 = Personnage
        public $_mapEntite;
        public $_bdd;

        public $_idTypePersonnage;
        public $TypePersonnage;

        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        public function setEntite($idEntite,$nameEntite,$healthNow,$degat,$healthMax,$imgEntite,$idTypeEntite,$lvlEntite,$dateTimeEntite,$idUser){
            $this->_idEntite = $idEntite;
            $this->_nameEntite = $nameEntite;
            $this->_healthNow = $healthNow;
            $this->_healthMax = $healthMax;
            $this->_degat = $degat;
            $this->_imgEntite = $imgEntite;
            $this->_idTypeEntite = $idTypeEntite;
            $this->_lvlEntite = $lvlEntite;
            $this->_dateTimeEntite = $dateTimeEntite;
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
            $req = $this->_bdd->prepare("SELECT * FROM `Entite` WHERE `idEntite`=:idEntite");
            $req->execute(['idEntite' => $idEntite]);
            if($tab = $req->fetch()){
                $this->setEntite($tab["idEntite"], $tab["nameEntite"], $tab["healthNow"], $tab["degat"], $tab["healthMax"], $tab["imgEntite"], $tab["idTypeEntite"], $tab["lvlEntite"], $tab["dateTimeEntite"], $tab["idUser"]);
                //recherche de sa position
                $mapEntite = new map($this->_bdd);
                $mapEntite->setMapByID($tab["idMap"]);
                $this->_mapEntite = $mapEntite;
                //select les equipements déjà présent
                $req = $this->_bdd->prepare("SELECT idEquipement FROM `EntiteEquipement` WHERE idEntite=:idEntite");
                $req->execute(['idEntite' => $idEntite]);
                while($tab = $req->fetch()){
                    array_push($this->sacEquipements, $tab[0]);
                }
                //select les Equipement déjà présent
                $req = $this->_bdd->prepare("SELECT idEquipement,equipe FROM `EntiteEquipement` WHERE idEntite=:idEntite AND equipe=1");
                $req->execute(['idEntite' => $idEntite]);
                while($tab = $req->fetch()){
                    if($tab['equipe'] == 1){
                        array_push($this->sacEquipe, $tab['idEquipement']);
                    }
                }
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

        /** Return son Objet Map */
        public function getMapEntite(){
            return $this->_mapEntite;
        }

        /** Return Lvl */
        public function getIdTypeEntite(){
            return $this->_idTypeEntite;
        }

        /** Return DateTime */
        public function getIdDateTimeEntite(){
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
        public function CreateEntite($nameEntite, $healthNow, $degat, $idMap, $healthMax, $imgEntite, $idUser, $idTypeEntite, $lvlEntite){
            $newperso = new Entite($this->_bdd);
            $this->_nameEntite  = htmlentities($nameEntite, ENT_QUOTES);
            $this->_lvlEntite   = $lvlEntite;
            $this->_imgEntite   = $imgEntite;
            $req = $this->_bdd->prepare("INSERT INTO `Entite`(`nameEntite`, `healthNow`, `degat`, `idMap`,`healthMax`,`imgEntite`,`idUser`,`idTypeEntite`,`lvlEntite`)
            VALUES (:nameEntite, :healthNow, :degat, :idMap, :healthMax, :imgEntite, :idUser, :idTypeEntite, :lvlEntite)");
            $req->execute(['nameEntite' => $this->_nameEntite, 'healthNow' => $healthNow, 'degat' => $degat, 'idMap' => $idMap, 'healthMax' => $healthMax, 'imgEntite' => $this->_imgEntite, 'idUser' => $idUser, 'idTypeEntite' => $idTypeEntite, 'lvlEntite' => $lvlEntite]);
            $this->_idEntite = $this->_bdd->lastInsertId();
            if($this->_idEntite){
                $newperso->setEntiteById($this->_idEntite);
                return $newperso;
            }
            else{
                return NULL;
            }
        }

        /** Fonction d'attaque */
        public function getAttaque(){
            $arme = $this->getArme();
            $coef = 1;
            $lvlEquipement = 1;
            $forceArme = 0;
            if(!is_null($arme)){// Si utilise une arme
                $coef = $arme->getEfficacite();
                $forceArme = $arme->getForce();
                $lvlEquipement = $arme->getLvlEquipement();
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
            $cooldown = -1;
            if(!is_null($arme)){
                $cooldown =$arme->getCoolDown();
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
                $req = $this->_bdd->prepare("DELETE FROM `EntiteEquipement` WHERE idEntite=:idEntite AND idEquipement=:idEquipement");
                $req->execute(['idEntite' => $this->getIdEntite(), 'idEquipement' => $EquipementID]);
                $req = $this->_bdd->prepare("DELETE FROM `Equipement` WHERE idEquipement=:idEquipement");
                $req->execute(['idEquipement' => $EquipementID]);
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
                $req = $this->_bdd->prepare("INSERT INTO `EntiteEquipement`(`idEntite`, `idEquipement`) VALUES (:idEntite, :idEquipement)");
                $req->execute(['idEntite' => $this->getIdEntite(), 'idEquipement' => $newEquipement->getIdEquipement()]);
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

        //Fonction Défense utilise une Armure
        public function getDefense(){
            //cas 
            //ici on affiche les dégats Maximun Absorbé avec une armure
            $armure = $this->getArmure();
            $coef = 1;
            $forceArmure = 0;
            if(!is_null($armure)){
                $coef = $armure->getEfficacite();
                $forceArmure = $armure->getForce();
            }
            if($this->_idTypeEntite == 1){
                $TypePersonnage = $this->getTypePersonnage();
                if(!is_null($armure)){
                    $coef = $coef*$TypePersonnage->getStatsDefense();
                }
            }
            $val = $coef * $forceArmure ;
            return round($val,1);//arrondi à 1 chiffre aprés la virgul
        }

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
            $req = $this->_bdd->prepare("UPDATE `Entite` SET `healthNow`=:healthNow WHERE `idEntite`=:idEntite");
            $req->execute(['healthNow' => $this->_healthNow, 'idEntite' => $this->_idEntite]);
            return $valeur;
        }

        public function SubitDegatByEntite($Entite){
            //nouveauté 2022 JLA on va gerer les dégat selon le type d'équipement
            //get Attaque sera modifier
            $CreatureDegatAttaqueEnvoyer=$Entite->getAttaque();
            //Mise en place de la defence.
            $CreatureDegatAttaqueEnvoyer -= round(($CreatureDegatAttaqueEnvoyer * $this->getDefense())/100,1);
    
            $this->_healthNow = $this->_healthNow - $CreatureDegatAttaqueEnvoyer;
            if($this->_healthNow<0){
                $this->_healthNow =0;
                //retour en zone 0,0
            }
            $req = $this->_bdd->prepare("UPDATE `Entite` SET `healthNow`=:healthNow WHERE `idEntite`=:idEntite");
            $req->execute(['healthNow' => $this->_healthNow, 'idEntite' => $this->_idEntite]);
            return $this->_healthNow;
        }

        public function getAllMyCreatureIdByMap($map){
            $listCreature=array();
            $req = $this->_bdd->prepare("SELECT `idEntite` FROM `Entite` WHERE `idUser`=:idUser AND `idMap`=:idMap)");
            $req->execute(['idUser' => $this->_idEntite, 'idMap' => $map->getIdMap()]);
            while($tab = $req->fetch()){
                array_push($listCreature,$tab);
            }
            return $listCreature;
        }

        public function SubitDegatByCreature($Creature){
            $CreatureDegatAttaqueEnvoyer=$Creature->getAttaque();
            $CreatureDegatAttaqueEnvoyer -= round(($CreatureDegatAttaqueEnvoyer * $this->getDefense())/100,1);
            $healthAvantAttaque = $this->_healthNow;
            $req = $this->_bdd->prepare("SELECT * FROM `AttaqueEntiteCreature` WHERE idCreature=:idCreature AND idEntite=:idEntite");
            $req->execute(['idCreature' => $Creature->getIdEntite(), 'idEntite' => $this->_idEntite]);
            $tabAttaque['nbCoup']=0;
            $tabAttaque['DegatsDonnes']=$CreatureDegatAttaqueEnvoyer;
            if($tab = $req->fetch()){
                $tabAttaque = $tab;
                $tabAttaque['DegatsDonnes']+=$CreatureDegatAttaqueEnvoyer;
                $tabAttaque['nbCoup']++;
            }
            else{
                $req = $this->_bdd->prepare("INSERT INTO `AttaqueEntiteCreature`(`idCreature`, `idEntite`, `nbCoup`, `coupFatal`, `DegatsDonnes`, `DegatsReçus`) 
                VALUES (:idCreature, :idEntite, 0, 0, :degatsRecus, 0)");
                $req->execute(['idCreature' => $Creature->getIdEntite, 'idEntite' => $this->_idEntite, 'degatsRecus' => $tabAttaque['DegatsReçus']]);
            }
            $this->_healthNow = $this->_healthNow - $CreatureDegatAttaqueEnvoyer;
            if($this->_healthNow<0){
                $this->_healthNow=0;
                //on ne peut pas donner plus de degat que la HealthNow d'un perso
                $tabAttaque['DegatsDonnes'] = $healthAvantAttaque;
                //retour en zone 0,0
            }
            $req = $this->_bdd->prepare("UPDATE `Entite` SET `healthNow`=:healthNow WHERE `idEntite`=:idEntite");
            $req->execute(['healthNow' => $this->_healthNow, 'idEntite' => $this->_idEntite]);
            $req = $this->_bdd->prepare("UPDATE `AttaqueEntiteCreature` SET `DegatsDonnes`=:DegatsDonnes WHERE idCreature=:idCreature AND idEntite=:idEntite");
            $req->execute(['DegatsDonnes' => $tabAttaque['DegatsDonnes'], 'idCreature' => $Creature->getIdEntite(), 'idEntite' => $this->_idEntite]);
            return $this->_healthNow;
        }

        public function resetCoolDown(){
            $arme = $this->getArme();
            if(!is_null($arme)){
                $cooldown =$arme->resetCoolDown();
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
            $req = $this->_bdd->prepare("UPDATE `Entite` SET `degat`=:degat, `healthMax`=:healthMax, `healthNow`=:healthNow WHERE `idEntite`=:idEntite");
            $req->execute(['degat' => $attaque, 'healthMax' => $healthMax, 'healthNow' => $healthMax, 'idEntite' => $this->_idEntite]);
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

        //retourne un entier de toutes ses valeurs // À dégager
        public function getValeur(){
            $valeur = 0;
            foreach($this->getEquipements() as $value){
                $valeur+=$value->getValeur();
            }
            //$valeur = 100;
            return $valeur;
        }

        public function lvlupAttaque($attaque){
            $this->_degat += $attaque;
            $req = $this->_bdd->prepare("UPDATE `Entite` SET `degat`=:degat WHERE `idEntite`=:idEntite");
            $req->execute(['degat' => $this->_degat, 'idEntite' => $this->_idEntite]);
        }
        public function lvlupHealthNow($healthmore){
            $this->_healthNow += $healthmore;
            $req = $this->_bdd->prepare("UPDATE `Entite` SET `healthNow`=:healthNow WHERE `idEntite`=:idEntite");
            $req->execute(['healthNow' => $this->_healthNow, 'idEntite' => $this->_idEntite]);
        }
        public function lvlupHealthMax($healthmore){
            $this->_healthMax += $healthmore;
            $req = $this->_bdd->prepare("UPDATE `Entite` SET `healthMax`=:healthMax WHERE `idEntite`=:idEntite");
            $req->execute(['healthMax' => $this->_healthMax, 'idEntite' => $this->_idEntite]);
        }

        /** Changement de Map */
        public function changeMap($NewMap){
            $this->_mapEntite = $NewMap;
            $req = $this->_bdd->prepare("UPDATE `Entite` SET `idMap`=:idMap WHERE `idEntite`=:idEntite");
            $req->execute(['idMap' => $NewMap->getIdMap(), 'idEntite' => $this->_idEntite]);
        }
    }
?>