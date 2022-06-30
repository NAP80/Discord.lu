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
        public function setFactionById($id){
            // Sélection des personnages de la faction
            $req = "SELECT * FROM `Faction` WHERE idFaction = '".$id."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
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