<?php
    class Classement{
        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        /** Return le nombre d'équipement par Efficacité : À vérifier */
        public function nbequipement($idEfficacite){
            $req = $this->_bdd->prepare("SELECT COUNT(*) FROM `equipement`WHERE idEfficacite=:idEfficacite");
            $req->execute(['idEfficacite' => $idEfficacite]);
            $nbequipement = $req->fetch();
            return $nbequipement['COUNT(*)'];
        }

        /** Return le nombre d'équipement par Type : À vérifier */
        public function nbitemtype($idTypeEquipement){
            $req = $this->_bdd->prepare("SELECT COUNT(*) FROM `equipement`WHERE type=:idTypeEquipement");
            $req->execute(['idTypeEquipement' => $idTypeEquipement]);
            $nbitemtype = $req->fetch();
            return $nbitemtype['COUNT(*)'];
        }

        /** Return le nombre d'équipement totaux : À vérifier */
        public function getNombreEquipement($bdd){
            $req = $this->_bdd->prepare("SELECT COUNT(*) FROM equipement");
            $req->execute();
            $data = $req->fetch();
            return $data['COUNT(*)'];
        }

        /** Return le nombre d'items totaux : À vérifier */
        public function getNombreItem(){
            $req = $this->_bdd->prepare("SELECT COUNT(*) FROM item");
            $req->execute();
            $data = $req->fetch();
            return $data['COUNT(*)'];
        }

        /** Return Nombre de Personnages Humains */
        public function nbpersonnage(){
            $req = $this->_bdd->prepare("SELECT COUNT(*) FROM `Entite` WHERE idEntite=`1`");
            $req->execute();
            $nbperso = $req->fetch();
            return $nbperso['COUNT(*)'];
        }

        /** Return Nombre User */
        public function nbUser(){
            $req = $this->_bdd->prepare("SELECT COUNT(*) FROM User");
            $req->execute();
            $nbuser = $req->fetch();
            return $nbuser['COUNT(*)'];
        }
    }
?>