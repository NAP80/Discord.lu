<?php
    class TypePersonnage{
        private $_bdd;

        private $_idTypePerso ;
        private $_nameTypePerso;
        private $_statsAttaque;
        private $_statsDefense;
        private $_statsMagique;
        private $_statsRessMagique;
        private $_imgTypePerso;
        private $_idFaction;

        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        // Return TypePersonnage by Id Perso
        public function setTypePersonnageByIdPerso($idPersonnage){
            $req = "SELECT *
            FROM `TypePersonnage`,`Personnage`
            WHERE `Personnage`.idTypePersonnage = `TypePersonnage`.idTypePerso
            AND `Personnage`.id='".$idPersonnage."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $this->_idTypePerso = $tab['idTypePerso'];
                $this->_nameTypePerso = $tab['nameTypePerso'];
                $this->_statsAttaque = $tab['statsAttaque'];
                $this->_statsDefense = $tab['statsDefense'];
                $this->_statsMagique = $tab['statsMagique'];
                $this->_statsRessMagique = $tab['statsRessMagique'];
                $this->_imgTypePerso = $tab['imgTypePerso'];
                $this->_idFaction = $tab['idFaction'];
            }
        }
        
        // Return TypePersonnage by Id
        public function setTypePersonnageById($id){
            //select les info personnage
            $req = "SELECT * FROM `TypePersonnage`
            WHERE idTypePerso='".$id."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $this->_idTypePerso = $tab['idTypePerso'];
                $this->_nameTypePerso = $tab['nameTypePerso'];
                $this->_statsAttaque = $tab['statsAttaque'];
                $this->_statsDefense = $tab['statsDefense'];
                $this->_statsMagique = $tab['statsMagique'];
                $this->_statsRessMagique = $tab['statsRessMagique'];
                $this->_imgTypePerso = $tab['imgTypePerso'];
                $this->_idFaction = $tab['idFaction'];
            }
        }

        /** Return IdTypePerso */
        public function getIdTypePerso(){
            return $this->_idTypePerso;
        }

        /** Return NameTypePerso */
        public function getNameTypePerso(){
            return $this->_nameTypePerso;
        }

        /** Return StatsAttaque */
        public function getStatsAttaque(){
            return $this->_statsAttaque;
        }

        /** Return StatsDefense */
        public function getStatsDefense(){
            return $this->_statsDefense;
        }

        /** Return StatsMagique */
        public function getStatsMagique(){
            return $this->_statsMagique;
        }

        /** Return StatsRessMagique */
        public function getStatsRessMagique(){
            return $this->_statsRessMagique;
        }

        /** Return ImgTypePerso */
        public function getImgTypePerso(){
            return $this->_imgTypePerso;
        }

        /** Return IdFaction */
        public function getIdFaction(){
            return $this->_idFaction;
        }
    }
?>