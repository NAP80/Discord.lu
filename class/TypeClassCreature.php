<?php
    class TypeClassCreature extends Entite{
        public $_bdd;

        private $_idTypeClassCreature;
        private $_nameTypeClassCreature;
        private $_posTypeCreature;
        private $_percentAttaque;
        private $_percentDefense;
        private $_percentDistance;
        private $_percentRessDistance;
        private $_spawnTypeCreature;

        public function __construct($bdd){
            Parent::__construct($bdd);
            $this->_bdd = $bdd;
        }

        // Return TypeClassCreature by Id Creatures
        public function setTypeClassCreatureByIdPerso($idCreature){
            $req = "SELECT *
            FROM `TypeClassCreature`,`Entite`
            WHERE `TypeClassCreature`.idTypeClassCreature = `Entite`.idTypeClassCreature
            AND `Entite`.idEntite='".$idCreature."'";
            $Result = $this->_bdd->query($req);
            if($tab = $Result->fetch()){
                $this->_idTypeClassCreature = $tab['idTypeClassCreature'];
                $this->_nameTypeClassCreature = $tab['nameTypeClassCreature'];
                $this->_posTypeCreature = $tab['posTypeCreature'];
                $this->_percentAttaque = $tab['percentAttaque'];
                $this->_percentDefense = $tab['percentDefense'];
                $this->_percentDistance = $tab['percentDistance'];
                $this->_percentRessDistance = $tab['percentRessDistance'];
                $this->_spawnTypeCreature = $tab['spawnTypeCreature'];
            }
        }
        
        // Return TypeClassCreature by Id TypeClassCreature
        public function setTypePersonnageById($idTypeClassCreature){
            $req = "SELECT * FROM `TypeClassCreature`
            WHERE idTypeClassCreature='".$idTypeClassCreature."'";
            $Result = $this->_bdd->query($req);
            if($tab = $Result->fetch()){
                $this->_idTypeClassCreature = $tab['idTypeClassCreature'];
                $this->_nameTypeClassCreature = $tab['nameTypeClassCreature'];
                $this->_posTypeCreature = $tab['posTypeCreature'];
                $this->_percentAttaque = $tab['percentAttaque'];
                $this->_percentDefense = $tab['percentDefense'];
                $this->_percentDistance = $tab['percentDistance'];
                $this->_percentRessDistance = $tab['percentRessDistance'];
                $this->_spawnTypeCreature = $tab['spawnTypeCreature'];
            }
        }

        /** Return IdTypeClassCreature */
        public function getIdTypeClassCreature(){
            return $this->_idTypeClassCreature;
        }

        /** Return NameTypeClassCreature */
        public function getNameTypeClassCreature(){
            return $this->_nameTypeClassCreature;
        }

        /** Return Position du NameTypeClassCreature :
         *  0 = Début,
         *  1 = Fin
         */
        public function getPosTypeCreature(){
            return $this->_posTypeCreature;
        }

        /** Return PercentAttaque */
        public function getPercentAttaque(){
            return $this->_percentAttaque;
        }

        /** Return PercentDefense */
        public function getPercentDefense(){
            return $this->_percentDefense;
        }

        /** Return PercentDistance */
        public function getPercentDistance(){
            return $this->_percentDistance;
        }

        /** Return PercentRessDistance */
        public function getPercentRessDistance(){
            return $this->_percentRessDistance;
        }

        /** Return SpawnTypeCreature */
        public function getSpawnTypeCreature(){
            return $this->_spawnTypeCreature;
        }
    }
?>