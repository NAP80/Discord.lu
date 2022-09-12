<?php
    class TypeMonster extends TypeClassMonster{
        public $_bdd;

        private $_idTypeMob;
        private $_nameTypeMob;
        private $_baseAttaque;
        private $_baseDefense;
        private $_baseMagique;
        private $_baseRessMagique;
        private $_baseGainMoney;
        private $_baseGainExp;
        private $_factionTypeMob;
        private $_spawnTypeMob;

        public function __construct($bdd){
            Parent::__construct($bdd);
            $this->_bdd = $bdd;
        }

        // Return TypeMonstre by Id Mobs
        public function setTypePersonnageByIdPerso($idMonster){
            $req = "SELECT *
            FROM `Typemob`,`Entite`
            WHERE `Typemob`.idTypeMob = `Entite`.idTypeMob
            AND `Entite`.idEntite='".$idMonster."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $this->_idTypeMob = $tab['idTypeMob'];
                $this->_nameTypeMob = $tab['nameTypeMob'];
                $this->_baseAttaque = $tab['baseAttaque'];
                $this->_baseDefense = $tab['baseDefense'];
                $this->_baseMagique = $tab['baseMagique'];
                $this->_baseRessMagique = $tab['baseRessMagique'];
                $this->_baseGainMoney = $tab['baseGainMoney'];
                $this->_baseGainExp = $tab['baseGainExp'];
                $this->_factionTypeMob = $tab['factionTypeMob'];
                $this->_spawnTypeMob = $tab['spawnTypeMob'];
            }
        }
        
        // Return TypeMonstre by idTypeMob
        public function setTypePersonnageById($idTypeMob){
            $req = "SELECT * FROM `Typemob`
            WHERE idTypeMob='".$idTypeMob."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $this->_idTypeMob = $tab['idTypeMob'];
                $this->_nameTypeMob = $tab['nameTypeMob'];
                $this->_baseAttaque = $tab['baseAttaque'];
                $this->_baseDefense = $tab['baseDefense'];
                $this->_baseMagique = $tab['baseMagique'];
                $this->_baseRessMagique = $tab['baseRessMagique'];
                $this->_baseGainMoney = $tab['baseGainMoney'];
                $this->_baseGainExp = $tab['baseGainExp'];
                $this->_factionTypeMob = $tab['factionTypeMob'];
                $this->_spawnTypeMob = $tab['spawnTypeMob'];
            }
        }

        /** Return IdTypeMob */
        public function getIdTypeMob(){
            return $this->_idTypeMob;
        }

        /** Return NameTypeMob */
        public function getNameTypeMob(){
            return $this->_nameTypeMob;
        }

        /** Return BaseAttaque */
        public function getBaseAttaque(){
            return $this->_baseAttaque;
        }

        /** Return BaseDefense */
        public function getBaseDefense(){
            return $this->_baseDefense;
        }

        /** Return BaseMagique */
        public function getBaseMagique(){
            return $this->_baseMagique;
        }

        /** Return BaseRessMagique */
        public function getBaseRessMagique(){
            return $this->_baseRessMagique;
        }

        /** Return BaseGainMoney */
        public function getBaseGainMoney(){
            return $this->_baseGainMoney;
        }

        /** Return BaseGainExp */
        public function getBaseGainExp(){
            return $this->_baseGainExp;
        }

        /** Return FactionTypeMob */
        public function getFactionTypeMob(){
            return $this->_factionTypeMob;
        }

        /** Return SpawnTypeMob */
        public function getSpawnTypeMob(){
            return $this->_spawnTypeMob;
        }
    }
?>