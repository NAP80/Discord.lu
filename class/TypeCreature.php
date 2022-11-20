<?php
    class TypeCreature extends TypeClassCreature{
        public $_bdd;

        private $_idTypeCreature;
        private $_nameTypeCreature;
        private $_baseAttaque;
        private $_baseDefense;
        private $_baseDistance;
        private $_baseRessDistance;
        private $_baseGainMoney;
        private $_baseGainExp;
        private $_factionTypeCreature;
        private $_spawnTypeCreature;

        public function __construct($bdd){
            Parent::__construct($bdd);
            $this->_bdd = $bdd;
        }

        // Return TypeCréature by Id Creatures
        public function setTypePersonnageByIdPerso($idCreature){
            $req = "SELECT *
            FROM `TypeCreature`,`Entite`
            WHERE `TypeCreature`.idTypeCreature = `Entite`.idTypeCreature
            AND `Entite`.idEntite='".$idCreature."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $this->_idTypeCreature = $tab['idTypeCreature'];
                $this->_nameTypeCreature = $tab['nameTypeCreature'];
                $this->_baseAttaque = $tab['baseAttaque'];
                $this->_baseDefense = $tab['baseDefense'];
                $this->_baseDistance = $tab['baseDistance'];
                $this->_baseRessDistance = $tab['baseRessDistance'];
                $this->_baseGainMoney = $tab['baseGainMoney'];
                $this->_baseGainExp = $tab['baseGainExp'];
                $this->_factionTypeCreature = $tab['factionTypeCreature'];
                $this->_spawnTypeCreature = $tab['spawnTypeCreature'];
            }
        }
        
        // Return TypeCréature by idTypeCreature
        public function setTypePersonnageById($idTypeCreature){
            $req = "SELECT * FROM `TypeCreature`
            WHERE idTypeCreature='".$idTypeCreature."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $this->_idTypeCreature = $tab['idTypeCreature'];
                $this->_nameTypeCreature = $tab['nameTypeCreature'];
                $this->_baseAttaque = $tab['baseAttaque'];
                $this->_baseDefense = $tab['baseDefense'];
                $this->_baseDistance = $tab['baseDistance'];
                $this->_baseRessDistance = $tab['baseRessDistance'];
                $this->_baseGainMoney = $tab['baseGainMoney'];
                $this->_baseGainExp = $tab['baseGainExp'];
                $this->_factionTypeCreature = $tab['factionTypeCreature'];
                $this->_spawnTypeCreature = $tab['spawnTypeCreature'];
            }
        }

        /** Return IdTypeCreature */
        public function getIdTypeCreature(){
            return $this->_idTypeCreature;
        }

        /** Return NameTypeCreature */
        public function getNameTypeCreature(){
            return $this->_nameTypeCreature;
        }

        /** Return BaseAttaque */
        public function getBaseAttaque(){
            return $this->_baseAttaque;
        }

        /** Return BaseDefense */
        public function getBaseDefense(){
            return $this->_baseDefense;
        }

        /** Return BaseDistance */
        public function getBaseDistance(){
            return $this->_baseDistance;
        }

        /** Return BaseRessDistance */
        public function getBaseRessDistance(){
            return $this->_baseRessDistance;
        }

        /** Return BaseGainMoney */
        public function getBaseGainMoney(){
            return $this->_baseGainMoney;
        }

        /** Return BaseGainExp */
        public function getBaseGainExp(){
            return $this->_baseGainExp;
        }

        /** Return FactionTypeCreature */
        public function getFactionTypeCreature(){
            return $this->_factionTypeCreature;
        }

        /** Return SpawnTypeCreature */
        public function getSpawnTypeCreature(){
            return $this->_spawnTypeCreature;
        }
    }
?>