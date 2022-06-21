<?php
    class Classement{
        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        /** Return le nombre d'équipement par Efficacité : À vérifier */
        public function nbequipement(){
            $Result = $this->_bdd->query("SELECT COUNT(*) FROM `equipement`WHERE efficacite=".$value."");
            $nbequipement = $Result->fetch();
            echo $nbequipement;
        }

        /** Return le nombre d'équipement par Type : À vérifier */
        public function nbitemtype(){
            $Result = $this->_bdd->query("SELECT COUNT(*) FROM `equipement` WHERE type=".$value."");
            $nbitemtype = $Result->fetch();
            echo $nbitemtype;
        }

        /** Return le nombre d'équipement totaux : À vérifier */
        public function getNombreEquipement($bdd){
            $req = 'SELECT COUNT(*) as "NB" FROM equipement';
            $excuteReq = $this->_bdd->query($req);
            $data = $excuteReq->fetch();
            return $data['NB'];
        }

        /** Return le nombre d'items par Type : À vérifier */
        public function nbitem(){
            $Result = $this->_bdd->query("SELECT COUNT(*) FROM `item` WHERE type=".$value."");
            $nbitem = $Result->fetch();
            echo $nbitem;
        }

        /** Return le nombre d'items par Éfficacité : À vérifier */
        public function nbefficatite(){
            $Result = $this->_bdd->query("SELECT COUNT(*) FROM `item` WHERE efficacite=".$value."");
            $nbefficatite= $Result->fetch();
            echo $nbefficatite;
        }

        /** Return le nombre d'items par Lvl : À vérifier */
        public function nblvl(){
            $Result = $this->_bdd->query("SELECT COUNT(*) FROM `item` WHERE lvl=".$value."");
            $nblvl= $Result->fetch();
            echo $nblvl;
        }

        /** Return le nombre d'items totaux : À vérifier */
        public function getNombreItem(){
            $req = 'SELECT COUNT(*) as "NB" FROM item';
            $excuteReq = $this->_bdd->query($req);
            $data = $excuteReq->fetch();
            return $data['NB'];
        }

        /** Return Nombre de Map ayant au moins un Mob */
        public function getMapWithOneMob(){
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
            $numberOfMapWithMob = $numberOfMap - $numberUser;
            return $numberOfMapWithMob;
        }

        /** Return Nombre de Map sans Mob */
        public function getMapWithoutMob(){
            $numberOfMap = 0;
            $temp = new Classement($this->_bdd);
            $mapWithMob = $temp->getMapWithOneMob();
            $res = $this->_bdd->query("SELECT * FROM Entite GROUP BY idMap");
            while($boucle = $res->fetch()){
                $numberOfMap++;
            }
            $numberOfMapWithoutMob = $numberOfMap - $mapWithMob;
            return $numberOfMapWithoutMob;
        }

        /** Return Nombre de Personnages Humains */
        public function nbpersonnage(){
            $Result = $this->_bdd->query("SELECT COUNT(*) FROM `Entite` WHERE type=`1`");
            $nbperso = $Result->fetch();
            echo $nbperso;
        }

        /** Retur Nombre User */
        public function nbUser(){
            $user = $this->_bdd->query("SELECT COUNT(*) name FROM User");
            $nbuser = $user->fetch();
            echo $nbuser['name'];
        }

        /** Retur Nombre User by idFaction */
        public function nbUserFaction(){
            $userfaction = $this->_bdd->query("SELECT COUNT(*) FROM faction, typepersonnage WHERE faction.id = typepersonnage.idFaction");
            $nbuserfaction = $user->fetch();
            echo $nbuserfaction[''];
        }
    }
?>