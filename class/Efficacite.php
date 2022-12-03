<?php
    class Efficacite{
        protected $_bdd;

        protected $_idEfficacite;
        protected $_nameEfficacite;
        protected $_valeur;

        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        public function setEfficaciteByID($idEfficacite){
            $req = $this->_bdd->prepare("SELECT * FROM Efficacite WHERE Efficacite.idEfficacite=:idEfficacite");
            $req->execute(['idEfficacite' => $idEfficacite]);
            if($tab = $req->fetch()){
                $this->setEfficacite(
                    $tab["idEfficacite"],
                    $tab["adjectif"],
                    $tab["coef"],
                    $tab["ordre"],
                    $tab["chance"]
                );
            }
        }

        public function setEfficacite($idEfficacite,$adjectif,$coef,$ordre,$chance){
            $this->_idEfficacite = $idEfficacite;
            $this->_adjectif = $adjectif;
            $this->_coef = $coef;
            $this->_ordre = $ordre;
            $this->_chance = $chance;
        }

        /** Return Name Efficacite */
        public function getNameEfficacite(){
            return $this->_nameEfficacite;
        }

        /** Return Id Efficacite */
        public function getIdEfficacite(){
            return $this->_idEfficacite;
        }

        /** Return Valeur */
        public function getValeur(){
            return $this->_valeur;
        }

        /** Return Efficacité */
        public function getEfficacite(){
            $req = $this->_bdd->prepare("SELECT * FROM Efficacite WHERE idEfficacite=:idEfficacite");
            $req->execute(['idEfficacite' => $this->_idEfficacite]);
            if($tab = $req->fetch()){
                return $tab['coef'];
            }
            return 0;
        }
        
        protected function getEfficaceAleatoire(){
            $req = $this->_bdd->prepare("SELECT * FROM Efficacite ORDER BY ordre ASC");
            $req->execute();
            $found = false;
            while($tab = $req->fetch()){
                if(rand(0, $tab['chance']) == 1){
                    $tabretour  = $tab;
                    $found = true;
                }
            }
            if($found){
                return $tabretour;
            }
            //si on trouve rien dans la base ( ce qui est pas normal
            //on envoi une efficacité bidon)
            $tab['idEfficacite'] = 1;
            $tab['coef'] = 0.1;
            $tab['ordre'] = 1;
            $tab['adjectif']="nul";
            return $tab;
        }
    }
?>