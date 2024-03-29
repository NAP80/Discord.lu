<?php
    class TypeMap{
        private $_bdd;

        private $_idTypeMap;
        private $_nameTypeMap;
        private $_defaultBackground;

        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        /** Return TypeMap by Id Map */
        public function setTypeMapByIdMap($IdMap){
            $req = $this->_bdd->prepare("SELECT * FROM `TypeMap`,`Map` WHERE `TypeMap`.idTypeMap=`Map`.idTypeMap AND `Map`.IdMap:IdMap");
            $req->execute(['IdMap' => $IdMap]);
            if($tab = $req->fetch()){
                $this->_idTypeMap           = $tab['idTypeMap'];
                $this->_nameTypeMap         = $tab['nameTypeMap'];
                $this->_defaultBackground   = $tab['defaultBackground'];
            }
        }
        
        /** Return TypeMap by Id TypeMap*/
        public function setTypeMapByIdTypeMap($idTypeMap){
            $req = $this->_bdd->prepare("SELECT * FROM `TypeMap` WHERE idTypeMap=:idTypeMap");
            $req->execute(['idTypeMap' => $idTypeMap]);
            if($tab = $req->fetch()){
                $this->_idTypeMap           = $tab['idTypeMap'];
                $this->_nameTypeMap         = $tab['nameTypeMap'];
                $this->_defaultBackground   = $tab['defaultBackground'];
            }
        }

        /** Return IdTypeMap */
        public function getIdTypeMap(){
            return $this->_idTypeMap;
        }

        /** Return NameTypeMap */
        public function getNameTypeMap(){
            return $this->_nameTypeMap;
        }

        /** Return DefaultBackground */
        public function getDefaultBackground(){
            return $this->_defaultBackground;
        }
    }
?>