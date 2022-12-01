<?php
    class Equipement extends Efficacite{
        protected $_idCategorie; //1 = Arme / 2 = Armure / 3 = Sort / 4 = Bouclcier
        protected $_coolDownMS;
        protected $_LastUse;
        protected $_lvlEquipement;

        public function __construct($bdd){
            $this->_bdd = $bdd;
            Parent::__construct($bdd);
        }

        /** Récupère un Équipement by ID */
        public function setEquipementByID($idEquipement){
            $req = "SELECT Equipement.idEquipement,
                        Equipement.idTypeEquipement,
                        Equipement.nameEquipement,
                        Equipement.valeur,
                        Equipement.idEfficacite,
                        Equipement.lvlEquipement,
                        Equipement.coolDownMS,
                        Equipement.LastUse,
                        Categorie.idCategorie
                FROM Equipement,TypeEquipement,Categorie WHERE Equipement.idEquipement='".$idEquipement."'
                AND TypeEquipement.idTypeEquipement = Equipement.idTypeEquipement
                AND Categorie.idCategorie = TypeEquipement.idCategorie
            ";
            $Result = $this->_bdd->query($req);
            if($tab = $Result->fetch()){
                $this->setEquipement(
                    $tab["idEquipement"],
                    $tab["idTypeEquipement"],
                    $tab["nameEquipement"],
                    $tab["valeur"],
                    $tab["idEfficacite"],
                    $tab["lvlEquipement"],
                    $tab["coolDownMS"],
                    $tab["LastUse"],
                    $tab["idCategorie"]
                );
            }
        }

        /** Return Id Equipement */
        public function getIdEquipement(){
            return $this->_idEquipement;
        }

        /** Return Name Equipement */
        public function getNameEquipement(){
            return $this->_nameEquipement;
        }

        /** Return Id Equipement */
        public function getLvlEquipement(){
            return $this->_lvlEquipement;
        }

        //retourne un tableau toutes les infos
        public function getIdCategorie(){
            return $this->_idCategorie;
        }

        public function desequipeEntite($Entite){
            $sql = "UPDATE `EntiteEquipement` SET `equipe`='0' WHERE `idEntite`='".$Entite->getIdEntite()."' AND `idEquipement`='".$this->_idEquipement."'";
            $this->_bdd->query($sql);
            $Entite->removeEquipeBydId($this->_idEquipement);
        }

        public function equipeEntite($Entite){
            //TODO il faut vérifier qu'il n'y a pas d'autre équipement en cours sinon il faut les retirer
            $sql = "UPDATE `EntiteEquipement`,`TypeEquipement`,`Equipement` SET `equipe`='0'
            WHERE `idEntite`='1'
            AND EntiteEquipement.idEquipement = Equipement.idEquipement
            AND Equipement.idTypeEquipement = TypeEquipement.idTypeEquipement
            AND TypeEquipement.idCategorie = '".$this->_idCategorie."'";
            $this->_bdd->query($sql);
            $Entite->addEquipeById($this->_idEquipement);
            $sql = "UPDATE `EntiteEquipement` SET `equipe`='1' WHERE `idEntite`='".$Entite->getIdEntite()."' AND `idEquipement`='".$this->_idEquipement."'";
            $this->_bdd->query($sql);
        }

        public function setEquipement($idEquipement,$idTypeEquipement,$nameEquipement,$valeur,$idEfficacite,$lvlEquipement,$coolDownMS,$LastUse,$idCategorie){
            $this->_idEquipement = $idEquipement;
            $this->_nameEquipement = $nameEquipement;
            $this->_idTypeEquipement = $idTypeEquipement;
            $this->_valeur = $valeur;
            $this->_idEfficacite = $idEfficacite;
            $this->_lvlEquipement = $lvlEquipement;
            $this->_coolDownMS = $coolDownMS;
            $this->_LastUse = $LastUse;
            $this->_idCategorie = $idCategorie;
            Parent::setEfficaciteById($this->_idEfficacite);
        }

        public function deleteEquipement($idEquipement){
            //TODO AVEC LES CONTRAINTE RELATIONNEL IL DFAUT VERIDIER QU'ELLE EST PAS UTILISER AILLEUR
            $req = "DELETE FROM Equipement WHERE idEquipement='".$idEquipement."' ";
            $Result = $this->_bdd->query($req);
        }

        //retourn un tableau les info
        public function getTypeEquipement(){
            $req = "SELECT * FROM TypeEquipement WHERE idTypeEquipement = '".$this->_idTypeEquipement."'";
            $Result = $this->_bdd->query($req);
            if($tab = $Result->fetch()){
                return $tab;
            }
            else{
                return null;
            }
        }

        /** Return le lien Image de l'équipement */ // Optimisable
        public function getImgEquipement(){
            $tab = $this->getTypeEquipement();
            if(!is_null($tab)){
                return $tab['imgEquipement'];
            }
            else{
                return "https://th.bing.com/th/id/OIP.I57H91s35hsrBcImYVt90AHaE8?w=247&h=180&c=7&r=0&o=5&pid=1.7";
            }
        }

        /** Return la couleur de rareté de l'équipement */
        public function getClassRarete(){
            $req="SELECT rarete FROM TypeEquipement WHERE idTypeEquipement = '".$this->_idTypeEquipement."'";
            $Result = $this->_bdd->query($req);
            $colorRarete = "background-color : rgba(";
            if($tab = $Result->fetch()){
                //pour le moment les raretés vont de 1 à 16
                //rareté de vert à rouge
                if($tab[0]<8){
                    //on par de 0   255 0
                    //        à 255 255 0
                    $val = round((($tab[0]/8)*((255-100)+100))+95);
                    $colorRarete .= $val . ',255,0';
                }
                else{
                    //on par de 255 255 0
                    //        à 255 0   0
                    //et les valeur vont de 8 à 16
                    $val = round(((($tab[0]-8)/8)*((255-100)+100))+95);
                    $val = 255-$val ;
                    $colorRarete .= '255,'.$val . ',0';
                }
            }
            else{
                //poussiere
                $colorRarete .= '255,255,255';
            }
            //max rarete valeur = 1600
            //1600 = 1
            $Transparence = (($this->_valeur/160)*((1-0.3)))+0.3;
            return $colorRarete.','.$Transparence.') !important';
        }

        /** Création d'un équipement aléatoire */
        public function createEquipementAleatoire(){
            $newEquipement = new Equipement($this->_bdd);
            $req="SELECT * FROM TypeEquipement ORDER BY rarete ASC";
            $Result = $this->_bdd->query($req);
            $i = $Result->rowCount();
            $imax=$i*3;
            $newType=1;
            $rarete=1;
            $coolDown=500;
            $newTypeNom='cuillère ';
            while($tab=$Result->fetch()){
                if(rand(0,$tab['chance'])==1){
                    $newType = $tab['idTypeEquipement'];
                    $newTypeNom = $tab['nameTypeEquipement'];
                    $coef=$tab['rarete'];
                    $coolDown=$tab['coolDown'];
                    break;
                }
            }
            $getEfficace = $this->getEfficaceAleatoire();
            $newNom = $newTypeNom." ".$getEfficace['adjectif'];
            $idEfficacite = $getEfficace['idEfficacite'];
            $newValeur = rand(5,10)*$rarete*$getEfficace['coef'];
            $coolDown = $coolDown*$getEfficace['coef'];
            $this->_bdd->beginTransaction();
            $req="INSERT INTO `Equipement`( `idTypeEquipement`, `nameEquipement`, `valeur`, `idEfficacite`,`lvlEquipement`,`coolDownMS`)
             VALUES ('".$newType."','".$newNom."','".$newValeur."','".$idEfficacite."',1,'".$coolDown."')";
            $Result = $this->_bdd->query($req);
            $lastID = $this->_bdd->lastInsertId();
            if($lastID){
                $newEquipement->setEquipementByID($lastID);
                $this->_bdd->commit();
                return $newEquipement;
            }
            else{
                $this->_bdd->rollback();
                echo "erreur anormal createEquipementAleatoire equipement.php ".$req;
                return null;
            }
        }

        public function getCoolDown(){
            //on doit vérifier en base si le cooldwn est terminé
            //sinon on renvoi -1
            $timeReel = microtime(true)*100;
            $timeLastUes= intval($this->_LastUse);
            $cooldown = intval($this->_coolDownMS);
        
            if($timeReel < ($timeLastUes+$cooldown)){
                return -1;
            }
            else{
                return $this->_coolDownMS;
            }
            
        }

        public function resetCoolDown(){
            $timems = microtime(true)*100;
            $req="UPDATE  Equipement SET LastUse = '".$timems."' WHERE idEquipement='".$this->_idEquipement."' ";
            $Result = $this->_bdd->query($req);
        }

        //retourne la fusion si c'est réussi des 2 items
        public function fusionEquipement($Entite,&$TabIDRemoved){
            $req="SELECT Equipement.idEquipement,Equipement.lvlEquipement FROM EntiteEquipement,Equipement
                WHERE Equipement.idEquipement = EntiteEquipement.idEquipement
                AND idEntite = '".$Entite->getIdEntite()."'
                AND Equipement.nameEquipement = '".$this->getNameEquipement()."'
                AND Equipement.lvlEquipement = '".$this->getLvlEquipement()."'
                AND Equipement.idTypeEquipement = '".$this->getTypeEquipement()['idTypeEquipement']."'
                AND Equipement.idEquipement <> '".$this->getIdEquipement()."'
            ";
            $result = $this->_bdd->query($req);
            if($tab=$result->fetch()){
                array_push($TabIDRemoved,$this->getIdEquipement());
                //maj du lvl
                $this->_lvlEquipement ++;
                $req="UPDATE `Equipement` SET `lvlEquipement`='".$this->_lvlEquipement."' WHERE `idEquipement` = '".$tab['idEquipement']."'";
                $this->_bdd->query($req);
                //et suppresion de l'ancien item
                $req="DELETE FROM `Equipement` WHERE `idEquipement` = '".$this->getIdEquipement()."'";
                $this->_bdd->query($req);
                //on met ajout son id fusionné
                $this->_idEquipement = $tab['idEquipement'];
                //fonction recursif tant qu'on peut fusionner on fusionne
                $this->fusionEquipement($Entite,$TabIDRemoved);
            }
        }
    }
?>