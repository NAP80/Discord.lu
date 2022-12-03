<?php
    /** NOTE : À dégager de la BDD et mettre dans le code même */
    class Tooltip{
        private $_idTooltip;
        private $_tooltip;
        private $_bdd;

        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        //retourne le text aléatoire d'un tooltip
        public function getTooltipAleatoire(){
            $req = $this->_bdd->prepare("SELECT * FROM Tooltip ORDER BY rand() LIMIT 1");
            $req->execute();
            if($tab = $req->fetch()){
                return $tab['tooltip'];
            }
        }
    }
?>