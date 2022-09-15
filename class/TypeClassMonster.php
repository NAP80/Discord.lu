<?php
    class TypeClassMonster extends Entite{
        public $_bdd;

        private $_idTypeClassMonster;
        private $_nameTypeClassMonster;
        private $_posTypeMonster;
        private $_percentAttaque;
        private $_percentDefense;
        private $_percentMagique;
        private $_percentRessMagique;
        private $_spawnTypeMonster;

        public function __construct($bdd){
            Parent::__construct($bdd);
            $this->_bdd = $bdd;
        }

        // Return TypeClassMonster by Id Monsters
        public function setTypeClassMonsterByIdPerso($idMonster){
            $req = "SELECT *
            FROM `TypeClassMonster`,`Entite`
            WHERE `TypeClassMonster`.idTypeClassMonster = `Entite`.idTypeClassMonster
            AND `Entite`.idEntite='".$idMonster."'";
            $Result = $this->_bdd->query($req);
            if($tab = $Result->fetch()){
                $this->_idTypeClassMonster = $tab['idTypeClassMonster'];
                $this->_nameTypeClassMonster = $tab['nameTypeClassMonster'];
                $this->_posTypeMonster = $tab['posTypeMonster'];
                $this->_percentAttaque = $tab['percentAttaque'];
                $this->_percentDefense = $tab['percentDefense'];
                $this->_percentMagique = $tab['percentMagique'];
                $this->_percentRessMagique = $tab['percentRessMagique'];
                $this->_spawnTypeMonster = $tab['spawnTypeMonster'];
            }
        }
        
        // Return TypeClassMonster by Id TypeClassMonster
        public function setTypePersonnageById($idTypeClassMonster){
            $req = "SELECT * FROM `TypeClassMonster`
            WHERE idTypeClassMonster='".$idTypeClassMonster."'";
            $Result = $this->_bdd->query($req);
            if($tab = $Result->fetch()){
                $this->_idTypeClassMonster = $tab['idTypeClassMonster'];
                $this->_nameTypeClassMonster = $tab['nameTypeClassMonster'];
                $this->_posTypeMonster = $tab['posTypeMonster'];
                $this->_percentAttaque = $tab['percentAttaque'];
                $this->_percentDefense = $tab['percentDefense'];
                $this->_percentMagique = $tab['percentMagique'];
                $this->_percentRessMagique = $tab['percentRessMagique'];
                $this->_spawnTypeMonster = $tab['spawnTypeMonster'];
            }
        }

        /** Return IdTypeClassMonster */
        public function getIdTypeClassMonster(){
            return $this->_idTypeClassMonster;
        }

        /** Return NameTypeClassMonster */
        public function getNameTypeClassMonster(){
            return $this->_nameTypeClassMonster;
        }

        /** Return Position du NameTypeClassMonster :
         *  0 = Début,
         *  1 = Fin
         */
        public function getPosTypeMonster(){
            return $this->_posTypeMonster;
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

        /** Return SpawnTypeMonster */
        public function getSpawnTypeMonster(){
            return $this->_spawnTypeMonster;
        }
    }
?>