<?php
    class Tooltip{
        private $_id;
        private $_tooltip;
        private $_bdd;

        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        //retourne le text aléatoire d'un tooltip
        public function getTooltipAleatoire(){
            $req="SELECT * From Tooltip order by rand() limit 1 ";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                return $tab['tooltip'];
            }
        }
    }
?>