
<?php
    class TypeUser{
        private $_bdd;

        private $_idTypeUser;
        private $_nameTypeUser;
        private $_admin;
        private $_staff;
        private $_bypass;
        private $_view;

        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        /** Set TypeUser by Id TypeUser */
        public function setTypeUserById($idTypeUser){
            $req  = "SELECT *
            FROM `TypeUser`
            WHERE idTypeUser='".$idTypeUser."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $this->_idTypeUser = $tab['idTypeUser'];
                $this->_nameTypeUser=$tab['nameTypeUser'];
                $this->_admin=$tab['admin'];
                $this->_staff=$tab['staff'];
                $this->_bypass=$tab['bypass'];
                $this->_view=$tab['view'];
            }
        }

        /** Set TypeUser by Id User */
        public function setTypeUserByIdUser($idUser){
            $req  = "SELECT *
            FROM `TypeUser`,`User`
            WHERE `TypeUser`.idTypeUser = `User`.idTypeUser
            AND `User`.idUser='".$idUser."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $this->_idTypeUser = $tab['idTypeUser'];
                $this->_nameTypeUser=$tab['nameTypeUser'];
                $this->_admin=$tab['admin'];
                $this->_staff=$tab['staff'];
                $this->_bypass=$tab['bypass'];
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
        public function getPermView(){
            return $this->_view;
        }
    }
?>