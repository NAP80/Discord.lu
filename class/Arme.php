<?php
    class Arme extends Equipement{
        /** Création Aléatoire d'une arme */
        public function createArmeAleatoire(){
            $req = $this->_bdd->prepare('SELECT * FROM TypeEquipement Where idCategorie=1 ORDER BY rarete ASC');
            $req->execute();
            $newType = 1;//par default une gifle c'est une attaque;
            $rarete = 1;
            $newTypeNom='Gifle ';

            while($tab = $req->fetch()){
                if(rand(0, $tab['chance']) == 1){
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
            $req = $this->_bdd->prepare("INSERT INTO `Equipement`( `idTypeEquipement`, `nameEquipement`, `valeur`, `idEfficacite`,`lvlEquipement`) VALUES (:newType, :newNom, :newValeur, :idEfficacite, 1)");
            $req->execute(['newType' => $newType, 'newNom' => $newNom, 'newValeur' => $newValeur, 'idEfficacite' => $idEfficacite]);
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