
<?php
    class Item extends Efficacite{
        public function __construct($bdd){
            $this->_bdd = $bdd;
            Parent::__construct($bdd);
        }

        /** Récupère Item By ID */
        public function setItemByID($idItem){
            $req = $this->_bdd->prepare("SELECT * FROM Item WHERE idItem=:idItem");
            $req->execute(['idItem' => $idItem]);
            if($tab = $req->fetch()){
                $this->setItem(
                    $tab["idItem"],
                    $tab["idTypeItem"],
                    $tab["nameItem"],
                    $tab["valeur"],
                    $tab["idEfficacite"],
                    $tab["lvlItem"]
                );
            }
        }

        /** Return Id Item */
        public function getIdItem(){
            return $this->_idItem;
        }

        /** Return Name Item */
        public function getNameItem(){
            return $this->_nameItem;
        }

        /** Return Name Item */
        public function getLvlItem(){
            return $this->_lvlItem;
        }

        /** Set un Item */
        public function setItem($idItem,$idTypeItem,$nameItem,$valeur,$idEfficacite,$lvlItem){
            $this->_idItem = $idItem;
            $this->_idTypeItem = $idTypeItem;
            $this->_nameItem = $nameItem;
            $this->_valeur = $valeur;
            $this->_idEfficacite = $idEfficacite;
            $this->_lvlItem = $lvlItem;
            Parent::setEfficaciteById($this->_idEfficacite);
        }

        /** Remove Item By ID : À vérifier si complet */
        public function deleteItem($idItem){
            $req = $this->_bdd->prepare("DELETE FROM Item WHERE idItem=:idItem");
            $req->execute(['idItem' => $idItem]);
        }

        /** Return Tab[ID,Information,LienIMG,Nom,Rareté] */
        public function getTypeItem(){
            $req = $this->_bdd->prepare("SELECT * FROM TypeItem WHERE idTypeItem=:idTypeItem");
            $req->execute(['idTypeItem' => $this->_idTypeItem]);
            if($tab = $req->fetch()){
                return $tab;
            }
            else{
                return null;
            }
        }

        /** Return Couleur de Rareté d'un Item */
        public function getClassRarete(){
            $req = $this->_bdd->prepare("SELECT rarete FROM TypeItem WHERE idTypeItem=:idTypeItem");
            $req->execute(['idTypeItem' => $this->_idTypeItem]);
            $colorRarete = "background-color:rgba(";
            if($tab = $req->fetch()){
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
            $req = $this->_bdd->prepare("SELECT * FROM TypeItem WHERE idTypeItem=2");
            $req->execute();
            if($tab = $req->fetch()){
                $newType = $tab['idTypeItem'];
                $newTypeNom = $tab['nameTypeItem'];
                $rarete=$tab['rarete'];
                $getEfficace = $this->getEfficaceAleatoire();
                $newNom = $newTypeNom." ".$getEfficace['adjectif'];
                $idEfficacite = $getEfficace['idEfficacite'];
                $newValeur = rand(5,10)*$rarete*$getEfficace['coef'];
                $this->_bdd->beginTransaction();
                $req = $this->_bdd->prepare("INSERT INTO `Item`( `idTypeItem`, `nameItem`, `valeur`, `idEfficacite`,`lvlItem`) VALUES (:newType, :newNom, :newValeur, :idEfficacite,1)");
                $req->execute(['newType' => $newType, 'newNom' => $newNom, 'newValeur' => $newValeur, 'idEfficacite' => $idEfficacite]);
                $lastID = $this->_bdd->lastInsertId();
                if($lastID){
                    $newItem->setItem($lastID,$newType,$newNom,$newValeur,$idEfficacite,1);
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
            $req = $this->_bdd->prepare("SELECT * FROM TypeItem ORDER BY rarete ASC");
            $req->execute();
            $i = $req->rowCount();
            $imax=$i*3;
            $newType=0;
            $rarete=1;
            $newTypeNom='poussiere';
            while($tab = $req->fetch()){
                if(rand(0,$tab['chance'])==1){
                    $newType = $tab['idTypeItem'];
                    $newTypeNom = $tab['nameTypeItem'];
                    $coef=$tab['rarete'];
                    break;
                }
            }
            $getEfficace = $this->getEfficaceAleatoire();
            $newNom = $newTypeNom." ".$getEfficace['adjectif'];
            $idEfficacite = $getEfficace['idEfficacite'];
            $newValeur = rand(5,10)*$rarete*$getEfficace['coef'];
            $this->_bdd->beginTransaction();
            $req = $this->_bdd->prepare("INSERT INTO `Item`( `idTypeItem`, `nameItem`, `valeur`, `idEfficacite`,`lvlItem`) VALUES (:newType, :newNom, :newValeur, :idEfficacite, 1)");
            $req->execute(['newType' => $newType, 'newNom' => $newNom, 'newValeur' => $newValeur, 'idEfficacite' => $idEfficacite]);
            $lastID = $this->_bdd->lastInsertId();
            if($lastID){ 
                $newItem->setItem($lastID,$newType,$newNom,$newValeur,$idEfficacite,1);
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
            $tab = $this->getTypeItem();
            if(!is_null($tab)){
                return $tab['imgItem'];
            }
            else{
                return "https://th.bing.com/th/id/OIP.I57H91s35hsrBcImYVt90AHaE8?w=247&h=180&c=7&r=0&o=5&pid=1.7";
            }
        }
    }
?>