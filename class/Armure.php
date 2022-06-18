<?php
    //la classe Armure est une enfant de la classe Equipement
    //est représente la catégorie Armure d'un type Equipement
    // Il est existe donc plusieurs class d'armure 
    //une armure envoi des degat brute suplémentaire

    class Armure extends Equipement{

        public function createArmureAleatoire(){
            //attention la catérogie id armure doit etre = 2
            $req="SELECT * FROM TypeEquipement Where idCategorie = 2 order by rarete ASC";
            $Result = $this->_bdd->query($req);

            //Todo si ya pas de typeItem 6 en base çà va planté
            $newType=6;//par default on choisie un typeEquipement de categorie 2 ici le N°6
            $rarete=1;
            $newTypeNom='Pull ';

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
            }else{
                $this->_bdd->rollback();
                echo "erreur anormal createEquipementAleatoire equipement.php ".$req;
                return null;
            }
        }

        public function getForce(){
            return $val = $this->getLvl()*$this->getValeur();
        }
    }
?>