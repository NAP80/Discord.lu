
<?php
    class Item extends Objet{
        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        /** Récupère Item By ID */
        public function setItemByID($id){
            $req="SELECT * FROM Item WHERE id='".$id."'";
            $Result = $this->_bdd->query($req);
            if($tab = $Result->fetch()){
                $this->setItem(
                    $tab["id"],
                    $tab["type"],
                    $tab["nom"],
                    $tab["valeur"],
                    $tab["efficacite"],
                    $tab["lvl"]
                );
            }
        }

        /** Set un Item */
        public function setItem($id,$type,$nom,$valeur,$efficacite,$lvl){
            $this->_id = $id;
            $this->_type = $type;
            $this->_nom = $nom;
            $this->_valeur = $valeur;
            $this->_efficacite = $efficacite;
            $this->_lvl = $lvl;
        }

        /** Remove Item By ID : À vérifier si complet */
        public function deleteItem($id){
            $req="DELETE FROM Item WHERE id='".$id."'";
            $Result = $this->_bdd->query($req);
        }

        /** Return Tab[ID,Information,LienIMG,Nom,Rareté] */
        public function getType(){
            $req="SELECT * FROM TypeItem WHERE id='".$this->_type."'";
            $Result = $this->_bdd->query($req);
            if($tab = $Result->fetch()){
                return $tab;
            }
            else{
                return null;
            }
        }

        /** Return Couleur de Rareté d'un Item */
        public function getClassRarete(){
            $req="SELECT rarete FROM TypeItem where id = '".$this->_type."'";
            $Result = $this->_bdd->query($req);
            $colorRarete = "background-color:rgba(";
            if($tab = $Result->fetch()){
                //pour le moment les raretés vont de 1 à 16
                //rareté de vert à rouge
                if($tab[0]<8){
                    //on par de 0   255 0
                    //        à 255 255 0
                    $val = round((($tab[0]/8)*((255-100)+100))+95);
                    $colorRarete .= $val . ',255,0';
                }
                else{
                    //on par de 255 255 0
                    //        à 255 0   0
                    //et les valeur vont de 8 à 16
                    $val = round(((($tab[0]-8)/8)*((255-100)+100))+95);
                    $val = 255-$val ;
                    $colorRarete .= '255,'.$val . ',0';
                }
            }
            else{
                //poussiere
                $colorRarete .= '255,255,255';
            }
            //max rarete valeur = 1600
            //1600 = 1
            $Transparence = (($this->_valeur/160)*((1-0.3)))+0.3;
            return $colorRarete.','.$Transparence.') !important';
        }

        /** Création d'un Item Soin Aléatoire */
        public function createItemSoinConsommable(){
            $newItem = new Item($this->_bdd);
            $req="SELECT * FROM TypeItem where id = 2";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $newType = $tab['id'];
                $newTypeNom = $tab['nom'];
                $rarete=$tab['rarete'];
                $getEfficace = $this->getEfficaceAleatoire();
                $newNom = $newTypeNom." ".$getEfficace['adjectif'];
                $efficacite = $getEfficace['id'];
                $newValeur = rand(5,10)*$rarete*$getEfficace['coef'];
                $this->_bdd->beginTransaction();
                $req="INSERT INTO `Item`( `type`, `nom`, `valeur`, `efficacite`,`lvl`) VALUES ('".$newType."','".$newNom."','".$newValeur."','".$efficacite."',1)";
                $Result = $this->_bdd->query($req);
                $lastID = $this->_bdd->lastInsertId();
                if($lastID){
                    $newItem->setItem($lastID,$newType,$newNom,$newValeur,$efficacite,1);
                    $this->_bdd->commit();
                    return $newItem;
                }
                else{
                    $this->_bdd->rollback();
                    return null;
                }
            }
            else{
                return null;
            }
        }

        /** Création d'un Item Aléatoire */
        public function createItemAleatoire(){
            $newItem = new Item($this->_bdd);
            $req="SELECT * FROM TypeItem ORDER BY rarete ASC";
            $Result = $this->_bdd->query($req);
            $i = $Result->rowCount();
            $imax=$i*3;
            $newType=0;
            $rarete=1;
            $newTypeNom='poussiere';
            while($tab=$Result->fetch()){
                if(rand(0,$tab['chance'])==1){
                    $newType = $tab['id'];
                    $newTypeNom = $tab['nom'];
                    $coef=$tab['rarete'];
                    break;
                }
            }
            $getEfficace = $this->getEfficaceAleatoire();
            $newNom = $newTypeNom." ".$getEfficace['adjectif'];
            $efficacite = $getEfficace['id'];
            $newValeur = rand(5,10)*$rarete*$getEfficace['coef'];
            $this->_bdd->beginTransaction();
            $req="INSERT INTO `Item`( `type`, `nom`, `valeur`, `efficacite`,`lvl`) VALUES ('".$newType."','".$newNom."','".$newValeur."','".$efficacite."',1)";
            $Result = $this->_bdd->query($req);
            $lastID = $this->_bdd->lastInsertId();
            if($lastID){ 
                $newItem->setItem($lastID,$newType,$newNom,$newValeur,$efficacite,1);
                $this->_bdd->commit();
                return $newItem;
            }
            else{
                $this->_bdd->rollback();
                echo "erreur anormal createItemAleatoire item.php ".$req;
                return null;
            }
        }

        /** Return le Lien d'Image */
        public function getImgItem(){
            $tab = $this->getType();
            if(!is_null($tab)){
                return $tab['imgItem'];
            }
            else{
                return "https://th.bing.com/th/id/OIP.I57H91s35hsrBcImYVt90AHaE8?w=247&h=180&c=7&r=0&o=5&pid=1.7";
            }
        }
    }
?>