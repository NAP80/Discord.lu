<?php
    class Faction {
        private $_bdd;

        private $_idFaction;
        private $_nameFaction;
        private $_descFaction;
        private $_logoFaction;

        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        /** Récupère la Faction by ID */
        public function setFactionById($idFaction){
            // Sélection des personnages de la faction
            $req = $this->_bdd->prepare("SELECT * FROM `Faction` WHERE idFaction=:idFaction");
            $req->execute(['idFaction' => $idFaction]);
            if($tab = $req->fetch()){
                $this->_idFaction   = $tab['idFaction'];
                $this->_nameFaction = $tab['nameFaction'];
                $this->_descFaction = $tab['descFaction'];
                $this->_logoFaction = $tab['logoFaction'];
            }
        }

        /** Return ID Faction */
        public function getIdFaction(){
            return $this->_idFaction;
        }

        /** Return Name Faction */
        public function getNameFaction(){
            return $this->_nameFaction;
        }

        /** Return Description Faction */
        public function getDescFaction(){
            return $this->_descFaction;
        }

        /** Return Bannière Faction */
        public function getLogoFaction(){
            return $this->_logoFaction;
        }
    }
?>