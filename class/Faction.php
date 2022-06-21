<?php
    class Faction {
        private $_id;
        private $_nom;
        private $_couleur;
        private $_bdd;

        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        /** Récupère la Faction by ID */
        public function setFactionById($id){
            // Sélection des personnages de la faction
            $req = "SELECT * FROM `Faction` WHERE id='".$id."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $this->_id = $tab['id'];
                $this->_nom= $tab['nom'];
                $this->_couleur= $tab['couleur'];
            }
        }

        /** Return ID Faction */
        public function getId(){
            return $this->_id;
        }

        /** Return Nom Faction */
        public function getNom(){
            return $this->_nom;
        }

        /** Return Couleur de Faction */
        public function getCouleur(){
            return $this->_couleur;
        }

        /** Return ID Faction */
        public function getAllTypePersonnage(){
            $TypePersos = array();
            $Result = $this->_bdd->query("SELECT * FROM `TypePersonnage` WHERE idFaction = '".$this->_id."'");
            while($tab=$Result->fetch()){
                $TypePerso = new TypePersonnage($this->_bdd);
                $TypePerso->setTypePersonnageById($tab['id']);
                array_push($TypePersos,$TypePerso);
            }
            return $TypePersos;
        }

        /** Set Nom by ID */
        public function setNom($nom) {
            $req = $this->_bdd->prepare("UPDATE Faction SET nom = ? WHERE id = ?");
            $req->execute(array($nom, $this->_id));
            $this->_nom = $nom;
        }

        /** Set Couleur by ID */
        public function setColor($couleur) {
            $req = $this->_bdd->prepare("UPDATE Faction SET couleur = ? WHERE id = ?");
            $req->execute(array($couleur, $this->_id));
            $this->_couleur = $couleur;
        }

        /** Set Nom by ID */
        public function showFaction() {
            $req = $this->_bdd->prepare("SELECT * FROM Faction");
            $req->execute();
            return $req->fetch();
        }
    }
?>