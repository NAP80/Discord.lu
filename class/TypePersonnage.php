<?php
    class TypePersonnage{
        private $_bdd;

        private $_idTypePerso;
        private $_nameTypePerso;
        private $_defaultAvatar;
        private $_statsAttaque;
        private $_statsDefense;
        private $_statsDistance;
        private $_statsRessDistance;
        private $_idFaction;

        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        /** Return TypePersonnage by Id Personnage */
        public function setTypePersonnageByIdPerso($idPersonnage){
            $req = $this->_bdd->prepare("SELECT * FROM `TypePersonnage`,`Personnage` WHERE `Personnage`.idTypePersonnage = `TypePersonnage`.idTypePerso AND `Personnage`.idPersonnage=:idPersonnage");
            $req->execute(['idPersonnage' => $idPersonnage]);
            if($tab = $req->fetch()){
                $this->_idTypePerso = $tab['idTypePerso'];
                $this->_nameTypePerso = $tab['nameTypePerso'];
                $this->_defaultAvatar = $tab['defaultAvatar'];
                $this->_statsAttaque = $tab['statsAttaque'];
                $this->_statsDefense = $tab['statsDefense'];
                $this->_statsDistance = $tab['statsDistance'];
                $this->_statsRessDistance = $tab['statsRessDistance'];
                $this->_idFaction = $tab['idFaction'];
            }
        }
        
        /** Return TypePersonnage by Id TypePersonnage*/
        public function setTypePersonnageById($idTypePersonnage){
            //select les info personnage
            $req = $this->_bdd->prepare("SELECT * FROM `TypePersonnage` WHERE idTypePerso=:idTypePersonnage");
            $req->execute(['idTypePersonnage' => $idTypePersonnage]);
            if($tab = $req->fetch()){
                $this->_idTypePerso = $tab['idTypePerso'];
                $this->_nameTypePerso = $tab['nameTypePerso'];
                $this->_defaultAvatar = $tab['defaultAvatar'];
                $this->_statsAttaque = $tab['statsAttaque'];
                $this->_statsDefense = $tab['statsDefense'];
                $this->_statsDistance = $tab['statsDistance'];
                $this->_statsRessDistance = $tab['statsRessDistance'];
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

        /** Return DefaultAvatar */
        public function getDefaultAvatar(){
            return $this->_defaultAvatar;
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

        /** Return IdFaction */
        public function getIdFaction(){
            return $this->_idFaction;
        }

        // Classement

        /** Return NameTypePerso by IdTypePersonnage*/
        public function getNameTypePersoById($idTypePersonnage){
            $req = $this->_bdd->prepare("SELECT * FROM `TypePersonnage` WHERE idTypePerso=:idTypePersonnage");
            $req->execute(['idTypePersonnage' => $idTypePersonnage]);
            if($tab = $req->fetch()){
                return $tab['nameTypePerso'];
            }
        }
    }
?>