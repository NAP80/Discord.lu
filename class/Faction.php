<?php
    //cette classe est développé par : Yann Martin
    class Faction {

        private $_id;
        private $_nom;
        private $_couleur;
        private $_bdd;

        /**
         * 
         * Cette fonction permet de construire la classe
         * 
         * Entries :
         * 
         * $bdd = contient la base de donnée
        */
        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        /**
         * 
         * Cette fonction permet de récupérer la faction par l'id
         * 
         * Entries :
         * 
         * $id = contient l'id de la faction
        */
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

        /**
         * 
         * Cette fonction permet de récupérer l'id
         * 
         * Entries :
         * 
         * aucune
        */
        public function getId(){
            return $this->_id;
        }

        /**
         * 
         * Cette fonction permet de récupérer le nom
         * 
         * Entries :
         * 
         * aucune 
        */
        public function getNom(){
            return $this->_nom;
        }

        /**
         * 
         * Cette fonction permet de récupérer la couleur
         * 
         * Entries :
         * 
         * aucune
        */
        public function getCouleur(){
            return $this->_couleur;
        }

        /**
         * 
         * Cette fonction permet de récupérer les types de personnages
         * 
         * Entries :
         * 
         * aucune
        */
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

        /**
         * 
         * Cette fonction permet modifier le nom de la faction
         * 
         * Entries :
         * 
         * $nom = variable contenant le nom de la faction
        */
        public function setNom($nom) {
            $req = $this->_bdd->prepare("UPDATE Faction SET nom = ? WHERE id = ?");
            $req->execute(array($nom, $this->_id));
            $this->_nom = $nom;
        }

        /**
         * 
         * Cette fonction permet modifier la couleur de la faction
         * 
         * Entries :
         * 
         * $couleur = variable contenant la couleur
        */
        public function setColor($couleur) {
            $req = $this->_bdd->prepare("UPDATE Faction SET couleur = ? WHERE id = ?");
            $req->execute(array($couleur, $this->_id));
            $this->_couleur = $couleur;
        }

        /**
         * 
         * Cette fonction permet de supprimer une faction
         * 
         * Entries :
         * 
         * $id = variable contenant l'id de la faction
        */
        public function delFaction($id) {
            $req = $this->_bdd->prepare("DELETE FROM Faction WHERE id = ?");
            $req->execute(array($this->_id));
        }

        /**
         * 
         * Cette fonction permet de créer une faction
         * 
         * Entries :
         * 
         * $nom = variable contenant le nom de la faction
         * $couleur = variable contenant la couleur de la faction
        */
        public function setFaction($nom, $couleur) {
            $req = $this->_bdd->prepare("INSERT INTO Faction SET nom = ?, couleur = ?");
            $req->execute(array($nom, $couleur));
            $this->_nom = $nom;
            $this->_couleur = $couleur;
        }

        /**
         * 
         * Cette fonction permet d'afficher les factions
         * 
         * Return :
         * 
         * $valeur = Fetch des informations du tableau
        */
        public function showFaction() {
            $req = $this->_bdd->prepare("SELECT * FROM Faction");
            $req->execute();
            return $req->fetch();
        }

        /**
         * 
         * Cette fonction permet d'afficher une faction en fonction de l'id
         * 
         * Return :
         * 
         * $id = id de la faction
        */
        public function getFactionNameById($id) {
            $req = $this->_bdd->prepare("SELECT * FROM Faction WHERE id = ?");
            $req->execute($id);
            return $req->fetch();
        }
    }
?>