<?php
    class TypeClassMonster extends Entite{
        public $_bdd;

        private $_idTypeClassMob;
        private $_nameTypeClassMob;
        private $_posTypeMob;
        private $_percentAttaque;
        private $_percentDefense;
        private $_percentMagique;
        private $_percentRessMagique;
        private $_spawnTypeMob;

        public function __construct($bdd){
            Parent::__construct($bdd);
            $this->_bdd = $bdd;
        }

        // Return TypeClassMonster by Id Mobs
        public function setTypeClassMonsterByIdPerso($idMonster){
            $req = "SELECT *
            FROM `TypeClassMob`,`Entite`
            WHERE `TypeClassMob`.idTypeClassMob = `Entite`.idTypeClassMob
            AND `Entite`.idEntite='".$idMonster."'";
            $Result = $this->_bdd->query($req);
            if($tab = $Result->fetch()){
                $this->_idTypeClassMob = $tab['idTypeClassMob'];
                $this->_nameTypeClassMob = $tab['nameTypeClassMob'];
                $this->_posTypeMob = $tab['posTypeMob'];
                $this->_percentAttaque = $tab['percentAttaque'];
                $this->_percentDefense = $tab['percentDefense'];
                $this->_percentMagique = $tab['percentMagique'];
                $this->_percentRessMagique = $tab['percentRessMagique'];
                $this->_spawnTypeMob = $tab['spawnTypeMob'];
            }
        }
        
        // Return TypeClassMonster by Id TypeClassMonster
        public function setTypePersonnageById($idTypeClassMonster){
            $req = "SELECT * FROM `TypeClassMob`
            WHERE idTypeClassMob='".$idTypeClassMonster."'";
            $Result = $this->_bdd->query($req);
            if($tab = $Result->fetch()){
                $this->_idTypeClassMob = $tab['idTypeClassMob'];
                $this->_nameTypeClassMob = $tab['nameTypeClassMob'];
                $this->_posTypeMob = $tab['posTypeMob'];
                $this->_percentAttaque = $tab['percentAttaque'];
                $this->_percentDefense = $tab['percentDefense'];
                $this->_percentMagique = $tab['percentMagique'];
                $this->_percentRessMagique = $tab['percentRessMagique'];
                $this->_spawnTypeMob = $tab['spawnTypeMob'];
            }
        }

        /** Return IdTypeClassMob */
        public function getIdTypeClassMob(){
            return $this->_idTypeClassMob;
        }

        /** Return NameTypeClassMob */
        public function getNameTypeClassMob(){
            return $this->_nameTypeClassMob;
        }

        /** Return Position du NameTypeClassMob :
         *  0 = Début,
         *  1 = Fin
         */
        public function getPosTypeMob(){
            return $this->_posTypeMob;
        }

        /** Return PercentAttaque */
        public function getPercentAttaque(){
            return $this->_percentAttaque;
        }

        /** Return PercentDefense */
        public function getPercentDefense(){
            return $this->_percentDefense;
        }

        /** Return PercentMagique */
        public function getPercentMagique(){
            return $this->_percentMagique;
        }

        /** Return PercentRessMagique */
        public function getPercentRessMagique(){
            return $this->_percentRessMagique;
        }

        /** Return SpawnTypeMob */
        public function getSpawnTypeMob(){
            return $this->_spawnTypeMob;
        }
    }
?>