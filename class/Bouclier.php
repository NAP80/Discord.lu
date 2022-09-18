<?php
    class Bouclier extends Equipement{
        /** Création Aléatoire d'un Bouclier */
        public function createBouclierAleatoire(){
            $req="SELECT * FROM TypeEquipement Where idCategorie = 4 order by rarete ASC"; 
            $Result = $this->_bdd->query($req);

            $newType=6;//par default on choisie un typeEquipement de categorie 2 ici le N°6
            $rarete=1;
            $newTypeNom='Pull ';

            while($tab=$Result->fetch()){
                if(rand(0,$tab['chance'])==1){
                    $newType = $tab['idTypeEquipement'];
                    $newTypeNom = $tab['nameEquipement'];
                    $coef=$tab['rarete'];
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
                echo "erreur anormale Bouclier createEquipementAleatoire equipement.php ".$req;
                return null;
            }
        }

        public function getForce(){
            return $val = $this->getLvlEquipement()*$this->getValeur();
        }
    }
?>