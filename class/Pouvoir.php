<?php
    class Pouvoir  extends Equipement{
        public function createPouvoirAleatoire(){
            //attention la catérogie id Pouvoir doit etre = 3
            $req="SELECT * FROM TypeEquipement Where idCategorie = 3 order by rarete ASC";
            $Result = $this->_bdd->query($req);
            
            $newType=4;//par default c'un baton  c'est une attaque;
            $rarete=1;
            $newTypeNom='Missile Magique ';
            
            while($tab=$Result->fetch()){
                if(rand(0,$tab['chance'])==1){
                    $newType = $tab['id'];
                    $newTypeNom = $tab['nom'];
                    $coef=$tab['rarete'];
                    break;
                }
            }

            $getEfficace = $this->getEfficaceAleatoire();

            $newNom = $newTypeNom." ".$getEfficace['adjectif'];
            $efficacite = $getEfficace['id'];
            
            $newValeur = rand(5,10)*$rarete*$getEfficace['coef'];

            $this->_bdd->beginTransaction();
            $req="INSERT INTO `Equipement`( `type`, `nom`, `valeur`, `efficacite`,`lvl`) VALUES ('".$newType."','".$newNom."','".$newValeur."','".$efficacite."',1)";
            $Result = $this->_bdd->query($req);
            $lastID = $this->_bdd->lastInsertId();
            if($lastID){ 
                $this->setEquipement($lastID,$newType,$newNom,$newValeur,$efficacite,1);
                $this->_bdd->commit();
                return $this;
            }
            else{
                $this->_bdd->rollback();
                echo "erreur anormal createEquipementAleatoire equipement.php ".$req;
                return null;
            }
        }

        public function getForce()
        {
        return $val = $this->getLvl()*$this->getValeur();
        }
    }
?>