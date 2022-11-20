<?php
    class TypePersonnage{
        private $_bdd;

        private $_idTypePerso;
        private $_nameTypePerso;
        private $_statsAttaque;
        private $_statsDefense;
        private $_statsDistance;
        private $_statsRessDistance;
        private $_imgTypePerso;
        private $_idFaction;

        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        /** Return TypePersonnage by Id Personnage */
        public function setTypePersonnageByIdPerso($idPersonnage){
            $req = "SELECT *
            FROM `TypePersonnage`,`Personnage`
            WHERE `Personnage`.idTypePersonnage = `TypePersonnage`.idTypePerso
            AND `Personnage`.idPersonnage = '".$idPersonnage."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $this->_idTypePerso = $tab['idTypePerso'];
                $this->_nameTypePerso = $tab['nameTypePerso'];
                $this->_statsAttaque = $tab['statsAttaque'];
                $this->_statsDefense = $tab['statsDefense'];
                $this->_statsDistance = $tab['statsDistance'];
                $this->_statsRessDistance = $tab['statsRessDistance'];
                $this->_imgTypePerso = $tab['imgTypePerso'];
                $this->_idFaction = $tab['idFaction'];
            }
        }
        
        /** Return TypePersonnage by Id TypePersonnage*/
        public function setTypePersonnageById($idTypePersonnage){
            //select les info personnage
            $req = "SELECT * FROM `TypePersonnage`
            WHERE idTypePerso = '".$idTypePersonnage."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $this->_idTypePerso = $tab['idTypePerso'];
                $this->_nameTypePerso = $tab['nameTypePerso'];
                $this->_statsAttaque = $tab['statsAttaque'];
                $this->_statsDefense = $tab['statsDefense'];
                $this->_statsDistance = $tab['statsDistance'];
                $this->_statsRessDistance = $tab['statsRessDistance'];
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

        /** Return StatsDistance */
        public function getStatsDistance(){
            return $this->_statsDistance;
        }

        /** Return StatsRessDistance */
        public function getStatsRessDistance(){
            return $this->_statsRessDistance;
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