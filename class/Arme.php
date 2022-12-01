<?php
    class Arme extends Equipement{
        /** Création Aléatoire d'une arme */
        public function createArmeAleatoire(){
            $req="SELECT * FROM TypeEquipement Where idCategorie = 1 order by rarete ASC";
            $Result = $this->_bdd->query($req);

            $newType=1;//par default une gifle c'est une attaque;
            $rarete=1;
            $newTypeNom='Gifle ';

            while($tab=$Result->fetch()){
                if(rand(0,$tab['chance'])==1){
                    $newType = $tab['idTypeEquipement'];
                    $newTypeNom = $tab['nameEquipement'];
                    break;
                }
            }

            $getEfficace = $this->getEfficaceAleatoire();

            $newNom = $newTypeNom." ".$getEfficace['adjectif'];
            $idEfficacite = $getEfficace['idEfficacite'];

            $newValeur = rand(5,10)*$rarete*$getEfficace['coef'];

            $this->_bdd->beginTransaction();
            $req="INSERT INTO `Equipement`( `idTypeEquipement`, `nameEquipement`, `valeur`, `idEfficacite`,`lvlEquipement`) VALUES ('".$newType."','".$newNom."','".$newValeur."','".$idEfficacite."',1)";
            $Result = $this->_bdd->query($req);
            $lastID = $this->_bdd->lastInsertId();
            if($lastID){
                $this->setEquipementByID($lastID);
                return $this;
            }
            else{
                $this->_bdd->rollback();
                echo "erreur anormal Arme createEquipementAleatoire equipement.php ".$req;
                return null;
            }
        }

        /** Return la force d'une arme */
        public function getForce(){
            return $val = $this->getLvlEquipement()*$this->getValeur();
        }
    }
?>