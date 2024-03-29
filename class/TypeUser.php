
<?php
    class TypeUser{
        private $_bdd;

        private $_idTypeUser;
        private $_nameTypeUser;
        private $_admin;
        private $_staff;
        private $_bypass;
        private $_play;
        private $_view;

        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        /** Set TypeUser by Id TypeUser */
        public function setTypeUserById($idTypeUser){
            $req = $this->_bdd->prepare("SELECT * FROM `TypeUser` WHERE idTypeUser=:idTypeUser");
            $req->execute(['idTypeUser' => $idTypeUser]);
            if($tab = $req->fetch()){
                $this->_idTypeUser = $tab['idTypeUser'];
                $this->_nameTypeUser=$tab['nameTypeUser'];
                $this->_admin=$tab['admin'];
                $this->_staff=$tab['staff'];
                $this->_bypass=$tab['bypass'];
                $this->_play=$tab['play'];
                $this->_view=$tab['view'];
            }
        }

        /** Set TypeUser by Id User */
        public function setTypeUserByIdUser($idUser){
            $req = $this->_bdd->prepare("SELECT * FROM `TypeUser`,`User` WHERE `TypeUser`.idTypeUser = `User`.idTypeUser AND `User`.idUser=:idUser");
            $req->execute(['idUser' => $idUser]);
            if($tab = $req->fetch()){
                $this->_idTypeUser=$tab['idTypeUser'];
                $this->_nameTypeUser=$tab['nameTypeUser'];
                $this->_admin=$tab['admin'];
                $this->_staff=$tab['staff'];
                $this->_bypass=$tab['bypass'];
                $this->_play=$tab['play'];
                $this->_view=$tab['view'];
            }
        }

        /** Return IdTypeUser */
        public function getIdTypeUser(){
            return $this->_idTypeUser;
        }

        /** Return NameTypeUser */
        public function getNameTypeUser(){
            return $this->_nameTypeUser;
        }

        /** Return Permision Admin */
        public function getPermAdmin(){
            return $this->_admin;
        }

        /** Return Permision Staff */
        public function getPermStaff(){
            return $this->_staff;
        }

        /** Return Permision Bypass */
        public function getPermBypass(){
            return $this->_bypass;
        }

        /** Return Permision View */
        public function getPermPlay(){
            return $this->_play;
        }

        /** Return Permision View */
        public function getPermView(){
            return $this->_view;
        }
    }
?>