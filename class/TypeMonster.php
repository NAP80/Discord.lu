<?php
    class TypeMonster extends TypeClassMonster{
        public $_bdd;

        private $_idTypeMonster;
        private $_nameTypeMonster;
        private $_baseAttaque;
        private $_baseDefense;
        private $_baseMagique;
        private $_baseRessMagique;
        private $_baseGainMoney;
        private $_baseGainExp;
        private $_factionTypeMonster;
        private $_spawnTypeMonster;

        public function __construct($bdd){
            Parent::__construct($bdd);
            $this->_bdd = $bdd;
        }

        // Return TypeMonstre by Id Monsters
        public function setTypePersonnageByIdPerso($idMonster){
            $req = "SELECT *
            FROM `TypeMonster`,`Entite`
            WHERE `TypeMonster`.idTypeMonster = `Entite`.idTypeMonster
            AND `Entite`.idEntite='".$idMonster."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $this->_idTypeMonster = $tab['idTypeMonster'];
                $this->_nameTypeMonster = $tab['nameTypeMonster'];
                $this->_baseAttaque = $tab['baseAttaque'];
                $this->_baseDefense = $tab['baseDefense'];
                $this->_baseMagique = $tab['baseMagique'];
                $this->_baseRessMagique = $tab['baseRessMagique'];
                $this->_baseGainMoney = $tab['baseGainMoney'];
                $this->_baseGainExp = $tab['baseGainExp'];
                $this->_factionTypeMonster = $tab['factionTypeMonster'];
                $this->_spawnTypeMonster = $tab['spawnTypeMonster'];
            }
        }
        
        // Return TypeMonstre by idTypeMonster
        public function setTypePersonnageById($idTypeMonster){
            $req = "SELECT * FROM `TypeMonster`
            WHERE idTypeMonster='".$idTypeMonster."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $this->_idTypeMonster = $tab['idTypeMonster'];
                $this->_nameTypeMonster = $tab['nameTypeMonster'];
                $this->_baseAttaque = $tab['baseAttaque'];
                $this->_baseDefense = $tab['baseDefense'];
                $this->_baseMagique = $tab['baseMagique'];
                $this->_baseRessMagique = $tab['baseRessMagique'];
                $this->_baseGainMoney = $tab['baseGainMoney'];
                $this->_baseGainExp = $tab['baseGainExp'];
                $this->_factionTypeMonster = $tab['factionTypeMonster'];
                $this->_spawnTypeMonster = $tab['spawnTypeMonster'];
            }
        }

        /** Return IdTypeMonster */
        public function getIdTypeMonster(){
            return $this->_idTypeMonster;
        }

        /** Return NameTypeMonster */
        public function getNameTypeMonster(){
            return $this->_nameTypeMonster;
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

        /** Return FactionTypeMonster */
        public function getFactionTypeMonster(){
            return $this->_factionTypeMonster;
        }

        /** Return SpawnTypeMonster */
        public function getSpawnTypeMonster(){
            return $this->_spawnTypeMonster;
        }
    }
?>