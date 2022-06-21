
<?php
    class TypePersonnage{
        private $_id;
        private $_coefAttaque;
        private $_coefBouclier;
        private $_coefDefense;
        private $_distance;
        private $_idFaction;
        private $_lienImage;
        private $_nom;
        private $_bdd;

        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        //on fourni l'id du personnage on récupére un type de perso
        public function setTypePersonnageByIdPerso($id){
            $req  = "SELECT *
            FROM `TypePersonnage`,`Personnage`
            WHERE `Personnage`.idTypePersonnage=  `TypePersonnage`.id
            AND `Personnage`.id='".$id."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $this->_id = $tab['id'];
                $this->_coefAttaque= $tab['coefAttaque'];
                $this->_coefBouclier= $tab['coefBouclier'];
                $this->_coefDefense= $tab['coefDefense'];
                $this->_distance= $tab['distance'];
                $this->_idFaction= $tab['idFaction'];
                $this->_lienImage= $tab['lienImage'];
                $this->_nom= $tab['nom'];
            }
        }
        
        //on fourni l'id du personnage on récupér un type de perso
        public function setTypePersonnageById($id){
            //select les info personnage
            $req  = "SELECT * FROM `TypePersonnage`
            WHERE id='".$id."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $this->_id = $tab['id'];
                $this->_coefAttaque=$tab['coefAttaque'];
                $this->_coefBouclier=$tab['coefBouclier'];
                $this->_coefDefense=$tab['coefDefense'];
                $this->_distance=$tab['distance'];
                $this->_idFaction=$tab['idFaction'];
                $this->_lienImage=$tab['lienImage'];
                $this->_nom=$tab['nom'];
            }
        }

        /** Return Id */
        public function getId(){
            return $this->_id;
        }

        /** Return Nom */
        public function getNom(){
            return $this->_nom;
        }

        /** Return CoefBouclier */
        public function getCoefBouclier(){
            return $this->_coefBouclier;
        }

        /** Return CoefDefense */
        public function getCoefDefense(){
            return $this->_coefDefense;
        }

        /** Return CoefAttaque */
        public function getCoefAttaque(){
            return $this->_coefAttaque;
        }

        /** Return Distance */
        public function getDistance(){
            return $this->_distance;
        }

        /** Return LienImage */
        public function getLienImage(){
            return $this->_lienImage;
        }

        /** Return idFaction */
        public function getFaction(){
            $faction = new Faction($this->_bdd);
            $faction->setFactionById($this->_idFaction);
            return $faction;
        }
    }
?>