<?php
    class Classement{
        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        /** Return le nombre d'équipement par Efficacité : À vérifier */
        public function nbequipement($idEfficacite){
            $Result = $this->_bdd->query("SELECT COUNT(*) FROM `equipement`WHERE idEfficacite=".$idEfficacite."");
            $nbequipement = $Result->fetch();
            echo $nbequipement['COUNT(*)'];
        }

        /** Return le nombre d'équipement par Type : À vérifier */
        public function nbitemtype($idTypeEquipement){
            $Result = $this->_bdd->query("SELECT COUNT(*) FROM `equipement` WHERE type=".$idTypeEquipement."");
            $nbitemtype = $Result->fetch();
            echo $nbitemtype['COUNT(*)'];
        }

        /** Return le nombre d'équipement totaux : À vérifier */
        public function getNombreEquipement($bdd){
            $req = 'SELECT COUNT(*) FROM equipement';
            $excuteReq = $this->_bdd->query($req);
            $data = $excuteReq->fetch();
            return $data['COUNT(*)'];
        }

        /** Return le nombre d'items par Type : À vérifier */
        public function nbitem($idTypeItem){
            $Result = $this->_bdd->query("SELECT COUNT(*) FROM `item` WHERE type=".$idTypeItem."");
            $nbitem = $Result->fetch();
            echo $nbitem;
        }

        /** Return le nombre d'items par Éfficacité : À vérifier */
        public function nbefficatite(){
            $Result = $this->_bdd->query("SELECT COUNT(*) FROM `item` WHERE idEfficacite=".$idEfficacite."");
            $nbefficatite = $Result->fetch();
            echo $nbefficatite;
        }

        /** Return le nombre d'items par Lvl : À vérifier */
        public function nblvl(){
            $Result = $this->_bdd->query("SELECT COUNT(*) FROM `item` WHERE lvl=".$value."");
            $nblvl = $Result->fetch();
            echo $nblvl;
        }

        /** Return le nombre d'items totaux : À vérifier */
        public function getNombreItem(){
            $req = 'SELECT COUNT(*) FROM item';
            $excuteReq = $this->_bdd->query($req);
            $data = $excuteReq->fetch();
            return $data['COUNT(*)'];
        }

        /** Return Nombre de Map ayant au moins un Monster */
        public function getMapWithOneMonster(){
            $numberOfMap = 0;
            $numberUser = 0;
            $res = $this->_bdd->query("SELECT * FROM Entite GROUP BY idMap");
            while($boucle = $res->fetch()){
                $numberOfMap++;
            }
            $res2 = $this->_bdd->query("SELECT * FROM Entite WHERE type = 1");
            while($boucle2 = $res2->fetch()){
                $numberUser++;
            }
            $numberOfMapWithMonster = $numberOfMap - $numberUser;
            return $numberOfMapWithMonster;
        }

        /** Return Nombre de Map sans Monster */
        public function getMapWithoutMonster(){
            $numberOfMap = 0;
            $temp = new Classement($this->_bdd);
            $mapWithMonster = $temp->getMapWithOneMonster();
            $res = $this->_bdd->query("SELECT * FROM Entite GROUP BY idMap");
            while($boucle = $res->fetch()){
                $numberOfMap++;
            }
            $numberOfMapWithoutMonster = $numberOfMap - $mapWithMonster;
            return $numberOfMapWithoutMonster;
        }

        /** Return Nombre de Personnages Humains */
        public function nbpersonnage(){
            $Result = $this->_bdd->query("SELECT COUNT(*) FROM `Entite` WHERE idEntite=`1`");
            $nbperso = $Result->fetch();
            echo $nbperso['COUNT(*)'];
        }

        /** Retur Nombre User */
        public function nbUser(){
            $user = $this->_bdd->query("SELECT COUNT(*) FROM User");
            $nbuser = $user->fetch();
            echo $nbuser['COUNT(*)'];
        }

        /** Retur Nombre User by idFaction */
        public function nbUserFaction(){
            $userfaction = $this->_bdd->query("SELECT COUNT(*) FROM faction, typepersonnage WHERE faction.idFaction = typepersonnage.idFaction");
            $nbuserfaction = $userfaction->fetch();
            echo $nbuserfaction['COUNT(*)'];
        }
    }
?>