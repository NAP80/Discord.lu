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
            $req = $this->_bdd->prepare("SELECT Equipement.idEquipement, Equipement.idTypeEquipement, Equipement.nameEquipement, Equipement.valeur, Equipement.idEfficacite, Equipement.lvlEquipement, Equipement.coolDownMS, Equipement.LastUse, Categorie.idCategorie
                FROM Equipement, TypeEquipement, Categorie
                WHERE Equipement.idEquipement=:idEquipement
                AND TypeEquipement.idTypeEquipement=Equipement.idTypeEquipement
                AND Categorie.idCategorie=TypeEquipement.idCategorie
            ");
            $req->execute(['idEquipement' => $idEquipement]);
            if($tab = $req->fetch()){
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
            $req = $this->_bdd->prepare("UPDATE `EntiteEquipement` SET `equipe`=0 WHERE `idEntite`=:idEntite AND `idEquipement`=:idEquipement");
            $req->execute(['idEntite' => $Entite->getIdEntite, 'idEquipement' => $this->_idEquipement]);
            $Entite->removeEquipeBydId($this->_idEquipement);
        }

        public function equipeEntite($Entite){
            //TODO il faut vérifier qu'il n'y a pas d'autre équipement en cours sinon il faut les retirer
            $req = $this->_bdd->prepare("UPDATE `EntiteEquipement`,`TypeEquipement`,`Equipement` SET `equipe`=0
            WHERE `idEntite`=1
            AND EntiteEquipement.idEquipement=Equipement.idEquipement
            AND Equipement.idTypeEquipement=TypeEquipement.idTypeEquipement
            AND TypeEquipement.idCategorie=:idCategorie");
            $req->execute(['idCategorie' => $this->_idCategorie]);
            $Entite->addEquipeById($this->_idEquipement);
            $req = $this->_bdd->prepare("UPDATE `EntiteEquipement` SET `equipe`='1' WHERE `idEntite`=:idEntite AND `idEquipement`=:idEquipement");
            $req->execute(['idEntite' => $Entite->getIdEntite, 'idEquipement' => $this->_idEquipement]);
        }

        public function setEquipement($idEquipement, $idTypeEquipement, $nameEquipement, $valeur, $idEfficacite, $lvlEquipement, $coolDownMS, $LastUse, $idCategorie){
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
            $req = $this->_bdd->prepare("DELETE FROM Equipement WHERE idEquipement=:idEquipement");
            $req->execute(['idEquipement' => $idEquipement]);
        }

        //retourn un tableau les info
        public function getTypeEquipement(){
            $req = $this->_bdd->prepare("SELECT * FROM TypeEquipement WHERE idTypeEquipement=:idTypeEquipement");
            $req->execute(['idTypeEquipement' => $this->_idTypeEquipement]);
            if($tab = $req->fetch()){
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
            $req = $this->_bdd->prepare("SELECT rarete FROM TypeEquipement WHERE idTypeEquipement=:idTypeEquipement");
            $req->execute(['idTypeEquipement' => $this->_idTypeEquipement]);
            $colorRarete = "background-color:rgba(";
            if($tab = $req->fetch()){
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
            $req = $this->_bdd->prepare("SELECT * FROM TypeEquipement ORDER BY rarete ASC");
            $req->execute();
            $i = $req->rowCount();
            $imax=$i*3;
            $newType=1;
            $rarete=1;
            $coolDown=500;
            $newTypeNom='cuillère ';
            while($tab = $req->fetch()){
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
            $req = $this->_bdd->prepare("INSERT INTO `Equipement`( `idTypeEquipement`, `nameEquipement`, `valeur`, `idEfficacite`,`lvlEquipement`,`coolDownMS`)
            VALUES (:newType, :newNom, :newValeur, :idEfficacite, 1, :coolDown)");
            $req->execute(['newType' => $newType, 'newNom' => $newNom, 'newValeur' => $newValeur, 'idEfficacite' => $idEfficacite, 'coolDown' => $coolDown]);
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
            $req = $this->_bdd->prepare("UPDATE Equipement SET LastUse=:LastUse WHERE idEquipement=:idEquipement");
            $req->execute(['LastUse' => $timems, 'idEquipement' => $this->_idEquipement]);
        }

        //retourne la fusion si c'est réussi des 2 items
        public function fusionEquipement($Entite,&$TabIDRemoved){
            $req = $this->_bdd->prepare("SELECT Equipement.idEquipement, Equipement.lvlEquipement
                FROM EntiteEquipement,Equipement
                WHERE Equipement.idEquipement=EntiteEquipement.idEquipement
                AND idEntite=:idEntite
                AND Equipement.nameEquipement=:nameEquipement
                AND Equipement.lvlEquipement=:lvlEquipement
                AND Equipement.idTypeEquipement=:idTypeEquipement
                AND Equipement.idEquipement<>:idEquipement
            ");
            $req->execute(['idEntite' => $Entite->getIdEntite(), 'nameEquipement' => $this->getNameEquipement(), 'lvlEquipement' => $this->getLvlEquipement(), 'idTypeEquipement' => $this->getTypeEquipement()['idTypeEquipement'], 'idEquipement' => $this->getIdEquipement()]);
            if($tab = $req->fetch()){
                array_push($TabIDRemoved,$this->getIdEquipement());
                // Update LV
                $this->_lvlEquipement ++;
                $req = $this->_bdd->prepare("UPDATE `Equipement` SET `lvlEquipement`=:lvlEquipement WHERE `idEquipement`=:idEquipement");
                $req->execute(['lvlEquipement' => $this->_lvlEquipement, 'idEquipement' => $tab['idEquipement']]);
                // Delet Ex Equipement
                $req = $this->_bdd->prepare("DELETE FROM `Equipement` WHERE `idEquipement`=:idEquipement");
                $req->execute(['idEquipement' => $this->getIdEquipement()]);
                // Add Equipement Fusionné
                $this->_idEquipement = $tab['idEquipement'];
                // Relance Fussion
                $this->fusionEquipement($Entite,$TabIDRemoved);
            }
        }
    }
?>