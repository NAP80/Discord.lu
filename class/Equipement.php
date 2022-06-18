<?php
    //dev by rapidecho
    class Equipement extends Objet{

        protected $_idCategorie; //1 = Arme / 2 = Armure / 3 = Sort / 4 = Bouclcier
        protected $coolDownMS_;
        protected $LastUse_;

        public function setEquipementByID($id){
            $req="SELECT Equipement.id,
                        Equipement.type,
                        Equipement.nom,
                        Equipement.valeur,
                        Equipement.efficacite,
                        Equipement.lvl,
                        Equipement.coolDownMS,
                        Equipement.LastUse,
                        Categorie.id as idCategorie
                FROM Equipement,TypeEquipement,Categorie WHERE Equipement.id='".$id."'
                AND TypeEquipement.id = Equipement.type
                AND Categorie.id = TypeEquipement.idCategorie
            ";
            $Result = $this->_bdd->query($req);
            if($tab = $Result->fetch()){
                $this->setEquipement($tab["id"],
                            $tab["type"],
                            $tab["nom"],
                            $tab["valeur"],
                            $tab["efficacite"],
                            $tab["lvl"],
                            $tab["coolDownMS"],
                            $tab["LastUse"]
                            );
                $this->_idCategorie = $tab["idCategorie"];
            }
        }
        //retourne un tableau avec id , bool attaque , bool defense , bool magie , nom
        public function getCategorie(){
            if (!is_null($this->_idCategorie)){
                $req="SELECT * From Categorie where id = '".$this->_idCategorie."'";
                $Result = $this->_bdd->query($req);
                if($tab = $Result->fetch()){
                    return $tab;
                }
            }else{
                return null;
            }
        }

        public function desequipeEntite($Entite){
            $sql = "UPDATE `EntiteEquipement` SET `equipe`='0' WHERE `idEntite`='".$Entite->getId()."' AND `idEquipement`='".$this->_id."'";
            $this->_bdd->query($sql);
            $Entite->removeEquipeBydId($this->_id);
        }

        public function equipeEntite($Entite){
            //TODO il faut vérifier qu'il n'y a pas d'autre équipement en cours sinon il faut les retirer
            $sql = "UPDATE `EntiteEquipement`,`TypeEquipement`,`Equipement` SET `equipe`='0'
            WHERE `idEntite`='1'
            AND EntiteEquipement.idEquipement = Equipement.id
            AND Equipement.type = TypeEquipement.id
            AND TypeEquipement.idCategorie = '".$this->_idCategorie."'";
            $this->_bdd->query($sql);
            $Entite->addEquipeById($this->_id);
            $sql = "UPDATE `EntiteEquipement` SET `equipe`='1' WHERE `idEntite`='".$Entite->getId()."' AND `idEquipement`='".$this->_id."'";
            $this->_bdd->query($sql);
        }

        public function setEquipement($id,$type,$nom,$valeur,$efficacite,$lvl,$coolDownMS,$LastUse){
            $this->_id = $id;
            $this->_nom = $nom;
            $this->_type = $type;
            $this->_valeur = $valeur;
            $this->_efficacite = $efficacite;
            $this->_lvl = $lvl;
            $this->coolDownMS_ = $coolDownMS;
            $this->LastUse_ = $LastUse;
        }

        public function deleteEquipement($id){
            //TODO AVEC LES CONTRAINTE RELATIONNEL IL DFAUT VERIDIER QU'ELLE EST PAS UTILISER AILLEUR
            $req="DELETE FROM Equipement WHERE id='".$this->_id."' ";
            $Result = $this->_bdd->query($req);
        }
        //retourn un tableau avec id information lienImage nom rarete
        public function getType(){
            $req="SELECT * FROM TypeEquipement WHERE id='".$this->_type."'";
            $Result = $this->_bdd->query($req);
            if($tab = $Result->fetch()){
                return $tab;
            }else{
                return null;
            }
        }

        //retourne le lien image du type d'item
        public function getLienImage(){
            $tab = $this->getType();
            if(!is_null($tab)){
                return $tab['lienImage'];
            }else{
                return "https://th.bing.com/th/id/OIP.I57H91s35hsrBcImYVt90AHaE8?w=247&h=180&c=7&r=0&o=5&pid=1.7";
            }
        }

        //retour le style de couleur de la rareté d'un equipement
        public function getClassRarete(){
            $req="SELECT rarete FROM TypeEquipement where id = '".$this->_type."'";
            $Result = $this->_bdd->query($req);
            $colorRarete = "background-color : rgba(";
            if($tab = $Result->fetch()){
                //pour le moment les raretés vont de 1 à 16
                //rareté de vert à rouge
                if($tab[0]<8){
                    //on par de 0   255 0
                    //        à 255 255 0
                    $val = round((($tab[0]/8)*((255-100)+100))+95);
                    $colorRarete .= $val . ',255,0';
                }else{
                    //on par de 255 255 0
                    //        à 255 0   0
                    //et les valeur vont de 8 à 16
                    $val = round(((($tab[0]-8)/8)*((255-100)+100))+95);
                    $val = 255-$val ;
                    $colorRarete .= '255,'.$val . ',0';
                }
            }else{
                //poussiere
                $colorRarete .= '255,255,255';
            }
            //max rarete valeur = 1600
            //1600 = 1
            $Transparence = (($this->_valeur/160)*((1-0.3)))+0.3;
            return $colorRarete.','.$Transparence.') !important';
        }

        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        public function createEquipementAleatoire(){
            $newEquipement = new Equipement($this->_bdd);
            $req="SELECT * FROM TypeEquipement ORDER BY rarete ASC";
            $Result = $this->_bdd->query($req);
            $i = $Result->rowCount();
            $imax=$i*3;
            $newType=1;
            $rarete=1;
            $coolDown=500;
            $newTypeNom='cuillère ';
            while($tab=$Result->fetch()){
                if(rand(0,$tab['chance'])==1){
                $newType = $tab['id'];
                $newTypeNom = $tab['nom'];
                $coef=$tab['rarete'];
                $coolDown=$tab['coolDown'];
                break;
                }
            }

            $getEfficace = $this->getEfficaceAleatoire();
            $newNom = $newTypeNom." ".$getEfficace['adjectif'];
            $efficacite = $getEfficace['id'];
            $newValeur = rand(5,10)*$rarete*$getEfficace['coef'];
            $coolDown = $coolDown*$getEfficace['coef'];
            $this->_bdd->beginTransaction();
            $req="INSERT INTO `Equipement`( `type`, `nom`, `valeur`, `efficacite`,`lvl`,`coolDownMS`)
             VALUES ('".$newType."','".$newNom."','".$newValeur."','".$efficacite."',1,'".$coolDown."')";
            $Result = $this->_bdd->query($req);
            $lastID = $this->_bdd->lastInsertId();
            if($lastID){
                $newEquipement->setEquipementByID($lastID);
                $this->_bdd->commit();
                return $newEquipement;
            }else{
                $this->_bdd->rollback();
                echo "erreur anormal createEquipementAleatoire equipement.php ".$req;
                return null;
            }
        }

        public function getCoolDown(){
            //on doit vérifier en base si le cooldwn est terminé
            //sinon on renvoi -1
            $timeReel = microtime(true)*100;
            $timeLastUes= intval($this->LastUse_);
            $cooldown = intval($this->coolDownMS_);
        
            if($timeReel < ($timeLastUes+$cooldown)){
                return -1;
            }else
            {
                return $this->coolDownMS_;
            }
            
        }

        public function resetCoolDown(){
            $timems = microtime(true)*100;
            $req="UPDATE  Equipement set LastUse = '".$timems."' WHERE id='".$this->_id."' ";
            $Result = $this->_bdd->query($req);
        }

        //retourne la fusion si c'est réussi des 2 items
        public function fusionEquipement($Entite,&$TabIDRemoved){
            $req="SELECT Equipement.id,Equipement.lvl FROM EntiteEquipement,Equipement
                WHERE Equipement.id = EntiteEquipement.idEquipement
                AND idEntite = '".$Entite->getId()."'
                AND Equipement.nom = '".$this->getNom()."'
                AND Equipement.lvl = '".$this->getlvl()."'
                AND Equipement.type = '".$this->getType()['id']."'
                AND Equipement.id <> '".$this->getId()."'
            ";
            $result = $this->_bdd->query($req);
            if($tab=$result->fetch()){
                array_push($TabIDRemoved,$this->getId());
                //maj du lvl
                $this->_lvl ++;
                $req="UPDATE `Equipement` SET `lvl`='".$this->_lvl."' WHERE `id` = '".$tab['id']."'";
                $this->_bdd->query($req);
                //et suppresion de l'ancien item
                $req="DELETE FROM `Equipement` WHERE `id` = '".$this->getId()."'";
                $this->_bdd->query($req);
                //on met ajout son id fusionné
                $this->_id = $tab['id'];
                //fonction recursif tant qu'on peut fusionner on fusionne
                $this->fusionEquipement($Entite,$TabIDRemoved);
            }
        }
        //affiche le nombre d'équipement existant par efficacité'
        public function nbequipement(){
            $Result = $this->_bdd->query("SELECT COUNT(*) FROM `equipement`WHERE efficacite=".$value."");
            $nbequipement = $Result->fetch();
            echo $nbequipement;
        }
        
        //affiche le nombre d'item existant par type'
        public function nbitemtype(){
            $Result = $this->_bdd->query("SELECT COUNT(*) FROM `equipement` WHERE type=".$value."");
            $nbitemtype = $Result->fetch();
            echo $nbitemtype;
        }

        /*
        fonction qui retourne le nombre d'équipement total dans la base de donner
        elle demende en paramètre la connection a la base de donné
        */
        public function getNombreEquipement($bdd)
        {
            $req = 'SELECT COUNT(*) as "NB" FROM equipement';
            $excuteReq = $this->_bdd->query($req);
            $data = $excuteReq->fetch();
            return $data['NB'];
        }
    }
?>