<?php
    class map{
        //valeur
        private $_id;
        private $_nom;
        private $_imageLien;
        protected $_isForge=false;
        protected $_isMarche=false;
        //coordonne de la map
        private $_x;
        private $_y;
        private $_type;
        private $idUserDecouverte;
        Private $listItems=array();
        Private $listEquipements=array();
        Private $listPersonnages=array();
        Private $listMobs=array();
        Private $_bdd;
        //la position initial est 0
        //les autres sont des hash
        private $_position=0;
        //lien vers les map adjacentes.
        private $mapNord=null;
        private $mapSud=null;
        private $mapEst=null;
        private $mapOuest=null;
        //lieu
        public function isForge(){
            return $this->_isForge;
        }
        public function isMarche(){
            return $this->_isMarche;
        }
        //calcule pitagorien pour avoir une distance au point d'origine
        //la distance determine le niveau
        public function getlvl(){
            $x = $this->_x;
            $y = $this->_x;
            if($x==0){
                $x=1;
            }
            if($y==0){
                $y=1;
            }
        $adjacent = $x * $x;
        $opose = $y * $y;
        $hypotenuse = sqrt ( $adjacent+$opose);
        $lvl = round(sqrt($hypotenuse)/3);
        if($lvl<1){
            $lvl= 1;
        }
        //plus on s'éloigne et plus il est difficile de toruver un lvl supérieur
        return $lvl;
        }
        //retourne une chaine de caractere des coordonne x , y
        public function getCoordonne(){
            return "(x:".$this->_x." y:".$this->_y.")";
        }
        public function setMapByID($id){
            $req = "SELECT * FROM map WHERE id='".$id."'";
            $Result = $this->_bdd->query($req);
            if($tab = $Result->fetch()){
                $this->setMap($tab["id"],
                            $tab["nom"],
                            $tab["position"],
                            $tab["mapNord"],
                            $tab["mapSud"],
                            $tab["mapEst"],
                            $tab["mapOuest"],
                            $tab["x"],
                            $tab["y"],
                            $tab["idUserDecouverte"],
                            $tab["lienImage"],
                            $tab["type"]
                            );
            }
        }
        public function __construct($bdd){
            $this->_bdd = $bdd;
        }
        public function getImageCssBack(){
            ?>
                <!-- Map.php -- getImageCssBack-->
                <style type = "text/css">
                    body{
                        background-size: cover;
                        background-repeat: no-repeat;
                        background-image: linear-gradient(rgba(255, 255, 255, 1), rgba(255,255,255, 0.01)), url(<?= $this->_imageLien?>);
                        background-attachment: fixed;
                    }
                </style>
            <?php
        }
        public function setMap($id,$nom,$position,$mapNord,$mapSud,$mapEst,$mapOuest,$x,$y,$idUserDecouverte,$image,$type){
            $this->_id = $id;
            $this->_nom = $nom;
            $this->_position = $position;
            $this->_x = $x;
            $this->_y = $y;
            $this->_imageLien = $image;
            $this->idUserDecouverte = $idUserDecouverte;
            //je place les id pour ne pas que l'objet racupère en récurciv toute les maps inclu dans elle meme
            (is_null($mapNord))?$this->mapNord = null:$this->mapNord = $mapNord;
            (is_null($mapSud))?$this->mapSud = null:$this->mapSud = $mapSud;
            (is_null($mapEst))?$this->mapEst = null:$this->mapEst = $mapEst;
            (is_null($mapOuest))?$this->mapOuest = null:$this->mapOuest = $mapOuest;
            //select les items déjà présent
            $this->listItems = array();
            $req = "SELECT idItem FROM `MapItems` WHERE idMap='".$id."'";
            $Result = $this->_bdd->query($req);
            while($tab=$Result->fetch()){
                array_push($this->listItems,$tab[0]);
            }
            //select les Equipements déjà présent
            $this->listEquipements = array();
            $req = "SELECT idEquipement FROM `MapEquipements` WHERE idMap='".$id."'";
            $Result = $this->_bdd->query($req);
            while($tab=$Result->fetch()){
                array_push($this->listEquipements,$tab[0]);
            }
            //select les Personnages déjà présent en vie
            $this->listPersonnages = array();
            $req = "SELECT id FROM `Entite` WHERE idMap='".$id."' and vie > 0 AND type='1'";
            $Result = $this->_bdd->query($req);
            while($tab=$Result->fetch()){
                array_push($this->listPersonnages,$tab[0]);
            }
            //select les Mob déjà présent
            $this->listMobs = array();
            $req = "SELECT id FROM `Entite` WHERE idMap='".$id."' AND type='2'";
            $Result = $this->_bdd->query($req);
            while($tab=$Result->fetch()){
                array_push($this->listMobs,$tab[0]);
            }
        }
        //accesseur get set
        public function getId(){
            return $this->_id;
        }
        public function getNom(){
            return $this->_nom;
        }
        public function getX(){
            return $this->_x;
        }
        public function getY(){
            return $this->_y;
        }
        public function getType(){
            return $this->_type;
        }
        //je vais chercher l'objet map au moment ou j'ai besoin
        //sinon les map vont automatiquement chercher leur map adjacente et çà va ramer
        public function getPosition(){
            return $this->_position;
        }
        public function getMapNord(){
            if(is_null($this->mapNord)){
                return null;
            }
            $map = new Map($this->_bdd);
            $map->setMapByID($this->mapNord);
            return $map;
        }
        public function getMapSud(){
            if(is_null($this->mapSud)){
                return null;
            }
            $map = new Map($this->_bdd);
            $map->setMapByID($this->mapSud);
            return $map;
        }
        public function getMapEst(){
            if(is_null($this->mapEst)){
                return null;
            }
            $map = new Map($this->_bdd);
            $map->setMapByID($this->mapEst);
            return $map;
        }
        public function getMapOuest(){
            if(is_null($this->mapOuest)){
                return null;
            }
            $map = new Map($this->_bdd);
            $map->setMapByID($this->mapOuest);
            return $map;
        }
        public function getItems(){
            $lists=array();
            foreach($this->listItems as $ItemId){
                $newItem = new Item($this->_bdd);
                $newItem->setItemByID($ItemId);
                array_push($lists,$newItem);
            }
            return $lists;
        }
        public function getEquipements(){
            $lists=array();
            foreach($this->listEquipements as $EquipementId){
                $newEquipement = new Equipement($this->_bdd);
                $newEquipement->setEquipementByID($EquipementId);
                array_push($lists,$newEquipement);
            }
            return $lists;
        }
        public function getAllPersonnages(){
            $lists=array();
            foreach($this->listPersonnages as $PersoID){
                $personnage = new Personnage($this->_bdd);
                $personnage->setPersonnageById($PersoID);
                array_push($lists,$personnage);
            }
            return $lists;
        }
        public function getAllMobs(){
            $lists=array();
            foreach($this->listMobs as $MobID){
                $mob = new Mob($this->_bdd);
                $mob->setMobByIdWithMap($MobID);
                array_push($lists,$mob);
            }
            return $lists;
        }
        //retourne tous les mob adverse (sans ses capture)
        public function getAllMobContre($User){
            $tab1 = $User->getAllMyMobIds();
            $tab2 = $this->listMobs;

            //attention l'ordre des param est important 
            $tab3 = array_diff($tab2,$tab1);
            //compare les 2tableau et retourne ce qui est commun
            return $tab3;
        }
        public function getAllMobCapture($User){
            //compare les 2tableau et retourne ce qui est pas commun
            $tab1 = $User->getAllMyMobIds();
            $tab2 = $this->listMobs;
            $tab3 = array_intersect($tab1,$tab2);
            //compare les 2tableau et retourne ce qui est commun
            return $tab3;
        }
        public function getPersonnageDecouvreur(){
            $perso = new User($this->_bdd);
            $perso->setUserById($this->idUserDecouverte);
            return $perso;
        }
        public function setMapNord($NewMap){
            $this->mapNord = $NewMap->getId();
            //update en base
            $req = "UPDATE `map` SET `mapNord`='".$NewMap->getId()."' WHERE `id` = '$this->_id'";
            $Result = $this->_bdd->query($req);
        }
        public function setMapSud($NewMap){
            $this->mapSud = $NewMap->getId();
            //update en base
            $req = "UPDATE `map` SET `mapSud`='".$NewMap->getId()."' WHERE `id` = '$this->_id'";
            $Result = $this->_bdd->query($req);
        }
        public function setMapEst($NewMap){
            $this->mapEst = $NewMap->getId();
            //update en base
            $req = "UPDATE `map` SET `mapEst`='".$NewMap->getId()."' WHERE `id` = '$this->_id'";
            $Result = $this->_bdd->query($req);
        }
        public function setMapOuest($NewMap){
            $this->mapOuest = $NewMap->getId();
            //update en base
            $req = "UPDATE `map` SET `mapOuest`='".$NewMap->getId()."' WHERE `id` = '$this->_id'";
            $Result = $this->_bdd->query($req);
        }
        //ajoute un lien entre item et la map en bdd 
        //et accroche l'item dans la collection itemID dans la map
        public function addItem($newItem){
            array_push($this->listItems,$newItem->getId());
            $req = "INSERT INTO `MapItems`(`idMap`, `idItem`) VALUES ('".$this->getId()."','".$newItem->getId()."')";
            $this->_bdd->query($req);
        }
        //ajoute un lien entre item et la map en bdd 
        //et accroche l'item dans la collection itemID dans la map
        public function addEquipement($newEquipement){
            array_push($this->listEquipements,$newEquipement->getId());
            $req = "INSERT INTO `MapEquipements`(`idMap`, `idEquipement`) VALUES ('".$this->getId()."','".$newEquipement->getId()."')";
            $this->_bdd->query($req);
        }
        public function removeEquipementByID($id){
            unset($this->listEquipements[array_search($id, $this->listEquipements)]);
            $req = "DELETE FROM `MapEquipements` WHERE idMap='".$this->getId()."' AND idEquipement='".$id."'";
            $this->_bdd->query($req);
        }
        public function removeItemByID($id){
            unset($this->listItems[array_search($id, $this->listItems)]);
            $req = "DELETE FROM `MapItems` WHERE idMap='".$this->getId()."' AND idItem='".$id."'";
            $this->_bdd->query($req);
        }
        //il faut lui donner la map adjacente
        //String cardinalite: lui dire si elle est par rapport à elle au sud , nord , est ou ouest ($cardinalite)
        //int id du user qui as decouvert cette map en premier
        public function Create($map,$cardinalite,$idUserDecouverte){
            if(intval($map->getId())>=0){
                $map->setMapByID($map->getId());
            }else{
                //la map n'existe pas
                return null;
            }
            $mapSud = 'NULL';
            $mapNord= 'NULL';
            $mapOuest = 'NULL';
            $mapEst = 'NULL';
            //IMPORTANT IL Faut vérifier que la zone qu'on découvre n'existe pas déjà par un autre chemin
            $newx = $map->_x;
            $newy = $map->_y;
            switch($cardinalite){
                case "sud":
                    $mapSud = "'".$map->getId()."'";
                    //on vérifie si la map n'existe pas déjà a cette cardinalité
                    if(!is_null($map->getMapNord())){
                        return $map->getMapNord();
                    }
                    $newy++;
                    break;
                case "nord":
                    $mapNord = "'".$map->getId()."'";
                    if(!is_null($map->getMapSud())){
                        return $map->getMapSud();
                    }
                    $newy--;
                    break;
                case "est":
                    $mapEst = "'".$map->getId()."'";
                    if(!is_null($map->getMapOuest())){
                        return $map->getMapOuest();
                    }
                    $newx--;
                    break;
                case "ouest":
                    $mapOuest = "'".$map->getId()."'";
                    if(!is_null($map->getMapEst())){
                        return $map->getMapEst();
                    }
                    $newx++;
                    break;
                default:
                    return null;
            }
            $mapExistante = $map->trouveMapAdjacente($map,$cardinalite);
            if(is_object($mapExistante)){
                switch($cardinalite){
                    case "nord":
                        $req = "UPDATE `map` SET `mapSud`='".$mapExistante->getId()."' WHERE `id` = '".$map->getId()."'";
                        $map->setMapSud($mapExistante);
                        $req = "UPDATE `map` SET `mapNord`='".$map->getId()."' WHERE `id` = '".$mapExistante->getId()."'";
                        $mapExistante->setMapNord($map);
                        break;
                    case "sud":
                        $req = "UPDATE `map` SET `mapNord`='".$mapExistante->getId()."' WHERE `id` = '".$map->getId()."'";
                        $map->setMapNord($mapExistante);
                        $req = "UPDATE `map` SET `mapSud`='".$map->getId()."' WHERE `id` = '".$mapExistante->getId()."'";
                        $mapExistante->setMapSud($map);
                        break;
                    case "est":
                        $req = "UPDATE `map` SET `mapOuest`='".$mapExistante->getId()."' WHERE `id` = '".$map->getId()."'";
                        $map->setMapOuest($mapExistante);
                        $req = "UPDATE `map` SET `mapEst`='".$map->getId()."' WHERE `id` = '".$mapExistante->getId()."'";
                        $mapExistante->setMapEst($map);
                        break;
                    case "ouest":
                        $req = "UPDATE `map` SET `mapEst`='".$mapExistante->getId()."' WHERE `id` = '".$map->getId()."'";
                        $map->setMapEst($mapExistante);
                        $req = "UPDATE `map` SET `mapOuest`='".$map->getId()."' WHERE `id` = '".$mapExistante->getId()."'";
                        $mapExistante->setMapOuest($map);
                        break;
                }
                $Result = $map->_bdd->query($req);
                return $mapExistante;
            }
            $position = $this->generatePosition();
            $Generate = $this->generateCarte();
            $nom = $Generate[2];
            $typeId=$Generate[0];
            $type=$Generate[1];
            //insertion en base
            //la position doit etre unique
            $imgLink = $this->getAleatoireImage($type);
            $req = "INSERT INTO `map`( `nom`, `position`, `mapNord`, `mapSud`, `mapEst`, `mapOuest`, `x`, `y`,`idUserDecouverte`,`lienImage`) 
                    VALUES 
                ('".$nom."','".$position."',".$mapNord.",".$mapSud.",".$mapEst.",".$mapOuest.",".$newx.",".$newy.",".$idUserDecouverte.",'".$imgLink."')";
            $Result = $this->_bdd->query($req);
            $req = "select id from map where position='".$position."'";
            $Result = $this->_bdd->query($req);
            if($tab = $Result->fetch()){
                $newmap = new map($this->_bdd);
                $newmap->setMapByID($tab["id"]);
                //on met à jour l'ancienne map avec les coordonnée de la nouvelle
                switch($cardinalite){
                    case "sud":
                        $map->setMapNord($newmap);
                        break;
                    case "nord":
                        $map->setMapSud($newmap);
                        break;
                    case "ouest":
                        $map->setMapEst($newmap);
                        break;
                    case "est":
                        $map->setMapOuest($newmap);
                        break;
                }
                //chargement d'un Item aléatoire
                if(rand(0,3)>1){
                    $item1 = new Item($this->_bdd);
                    $nbItem = rand(0,3);
                    for($i=0;$i<$nbItem;$i++){
                        $newmap->addItem($item1->createItemAleatoire());
                    }
                }
                //chargement d'un Equipement aléatoire
                if(rand(0,3)>1){
                    $equipement1 = new Equipement($this->_bdd);
                    $nbItem = rand(0,3);
                    for($i=0;$i<$nbItem;$i++){
                        $newmap->addEquipement($equipement1->createEquipementAleatoire());
                    }
                }
                //chargement d'un Mob aléatoire à la création
                if(rand(0,3)>1){
                    
                    $nbMob = rand(0,rand(2,4));
                    for($i=0;$i<$nbMob;$i++){
                        //il faut passer la map($this) au créateur de mob
                        $Mob1 = new Mob($this->_bdd);
                        $Mob1 = $Mob1->CreateMobAleatoire($newmap);
                        if(!is_null($Mob1)){
                            array_push($newmap->listMobs,$Mob1->getId());
                            //chargement d'un Item aléatoire par monstre present
                            if(rand(0,4)>1){
                                $item1 = new Item($this->_bdd);
                                $nbItem = rand(1,3);
                                for($i=0;$i<$nbItem;$i++){
                                    $newmap->addItem($item1->createItemAleatoire());
                                }
                            }
                            if(rand(0,4)>1){
                                $equipement1 = new Equipement($this->_bdd);
                                $nbItem = rand(0,3);
                                for($i=0;$i<$nbItem;$i++){
                                    $newmap->addEquipement($equipement1->createEquipementAleatoire());
                                }
                            }
                        }
                    }
                }
                ?>
                    <p>Tu viens de découvrir une nouvelle position : <?= $newmap->getNom() ?> <?= $newmap->getCoordonne()?>.</p>
                <?php
                return $newmap;
            }
            return null;
        }
        //cardinalite = la d'ou l'on vient
        //permet de charger un map ou d'en creer une selon d'ou l'on viens
        public function loadMap($position,$Cardinalite,$Joueur1){
            //on va vérifier qu'il n'est pas trop looin par rapport à son niveau
            $lvlPerso = $Joueur1->getPersonnage()->getLvl();
            if(isset($position) && isset($Cardinalite) ){
                //todo voir si le spam générate est controlé 
                if($position === "Generate"){
                    //la cardinalité permet de lui dire d'ou on vient
                    //on va
                    $map= $Joueur1->getPersonnage()->getMap();
                    //on vérifie si un mob est présent la ou on est car on est bloqué normalement
                    $listMob = $map->getAllMobContre($Joueur1);
                    if(is_null($listMob) || count($listMob) == 0){
                        $map = $map->Create($map,$_GET["cardinalite"],$Joueur1->getId());
                    }
                    $lvlMap = $map->getlvl();
                    if($lvlPerso<$lvlMap){
                        ?>
                            <p class="error">Ton lvl n'est pas assez haut pour venir ici.</p>
                        <?php
                        return $this;
                    }
                    if(!is_null($map)){
                        return $map;
                    }else{
                        return $this;
                    }
                }else if ($position>=0){
                    //récupération de la map est atttribution au combatant
                    $ancienX = $this->getX();
                    $ancienY = $this->getY();
                    $ancienPosition=$this->getPosition();
                    $mapVisite = new Map($this->_bdd);
                    $mapVisite->setMapByPosition($position);
                    $lvlMap = $mapVisite->getlvl();
                    if($lvlPerso<$lvlMap){
                        ?>
                            <p class="error">Ton lvl n'est pas assez haut pour venir ici.</p>
                        <?php
                        return $this;
                    }else{
                        $this->setMapByPosition($position);
                    }
                    //chargement des Items en plus
                    $req = "SELECT `laDate` from `Visites` WHERE `idMap` = '".$this->getId()."' ORDER BY `laDate` DESC";
                    $Result = $this->_bdd->query($req);
                    if($tab = $Result->fetch()){
                        $DatePresent = time();//"Y-m-d H:i:s"
                        $DerniereDate = strtotime($tab['laDate'])+2;
                        if($DerniereDate<=$DatePresent){
                            if(rand(0,2)>1){
                                $itemEnplus = new Item($this->_bdd);
                                $nbItem = rand(0,2);
                                for($i=0;$i<$nbItem;$i++){
            
                                    if(!is_null($this->getMapNord()) && rand(0,3)==0 ){
                                        $this->getMapNord()->addItem($itemEnplus->createItemAleatoire());
                                    }
                                    if(!is_null($this->getMapSud()) && rand(0,3)==1){
                                        $this->getMapSud()->addItem($itemEnplus->createItemAleatoire());
                                    }
                                    if(!is_null($this->getMapEst()) && rand(0,3)==2){
                                        $this->getMapEst()->addItem($itemEnplus->createItemAleatoire());
                                    }
                                    if(!is_null($this->getMapOuest()) && rand(0,3)==3){
                                        $this->getMapOuest()->addItem($itemEnplus->createItemAleatoire());
                                    }
                                }
                            }
                            if(rand(0,2)>1){
                                $equipementEnplus = new Equipement($this->_bdd);
                                $nbEquipement = rand(0,2);
                                for($i=0;$i<$nbEquipement;$i++){
                                    if(!is_null($this->getMapNord()) && rand(0,3)==0 ){
                                        $this->getMapNord()->addEquipement($equipementEnplus->createEquipementAleatoire());
                                    }
                                    if(!is_null($this->getMapSud()) && rand(0,3)==1){
                                        $this->getMapSud()->addEquipement($equipementEnplus->createEquipementAleatoire());
                                    }
                                    if(!is_null($this->getMapEst()) && rand(0,3)==2){
                                        $this->getMapEst()->addEquipement($equipementEnplus->createEquipementAleatoire());
                                    }
                                    if(!is_null($this->getMapOuest()) && rand(0,3)==3){
                                        $this->getMapOuest()->addEquipement($equipementEnplus->createEquipementAleatoire());
                                    }
                                }
                            }
                        }
                    }
                    //item de vie
                    if(rand(0,2)>1){
                        $itemEnplus = new Item($this->_bdd);
                        $nbItem = rand(0,2);

                        for($i=0;$i<$nbItem;$i++){
                            $this->addItem($itemEnplus->createItemSoinConsommable());
                        }
                    }
                    //on vérifie la téléportation sinon on ne change pas le joueur 
                    if( abs((abs($this->getX()) - (abs($ancienX) )) ) > 1 
                        || 
                        abs((abs($this->getY()) - (abs($ancienY) )) )> 1 
                    ){
                        ?>
                            <a href="map.php?position=<?= $ancienPosition ?>">Tu es téléporté, reviens là où tu étais.</a>
                        <?php
                    }else{
                        $Joueur1->getPersonnage()->ChangeMap($this);
                    }
                }else{
                    ?>
                        <a href="index.php">Tu es en Terre Inconnu, reviens vite là où tu étais.</a>
                    <?php
                }
            }else{
                ?>
                    <a href="index.php">Tu es en Terre Inconnu, reviens vite là où tu étais.</a>
                <?php
            }
            return $this;
        }
        public function getInfoMap(){
            ?>
                <b><?= $this->getNom() ?></b>, <?= $this->getCoordonne() ?>, lvl <?= $this->getlvl() ?>, découvert par <?= $this->getPersonnageDecouvreur()->getName() ?> et ses Heros.
            <?php
        }
        //permet d'enregistrer une visite et de vérifier si c'est pas trop rapide
        public function LogVisiteMap($Perso){
            $req = "SELECT `laDate` from `Visites` 
                WHERE `idPersonnage` = '".$Perso->getId()."'
                AND idmap = '".$this->_id."' 
                ORDER BY `laDate` DESC";
            $Result = $this->_bdd->query($req);
            if($tab = $Result->fetch()){
                $req = "UPDATE  `Visites` SET `laDate` =  '".date("Y-m-d H:i:s")."'
                WHERE   `idPersonnage` = '".$Perso->getId()."' 
                AND idmap = '".$this->_id."' ;";
                $Result = $this->_bdd->query($req);
            }else{
                $req = "INSERT INTO `Visites` (`idPersonnage`, `idMap`, `laDate`) 
                VALUES ('".$Perso->getId()."', '".$this->_id."', '".date("Y-m-d H:i:s")."')";
                $Result = $this->_bdd->query($req);
            }
            return true;
        }
        //retourn un string 
        //hash aléatoire pour une nouvelle position
        public function generatePosition(){
            return hash('ripemd160', $this->_nom.rand(0,100000000).rand(0,100000000));
        }
        public function setMapByPosition($position){
            $Result = $this->_bdd->query("SELECT id FROM `map` WHERE `position`='".$position."' ");
            if($tab = $Result->fetch()){ 
                $this->setMapByID($tab["id"]);
            }
        }
        //retourne les liens HTML des 4 map adjacente
        //sous forme de tableau ( 4 indice = 4 position)
        public function getMapAdjacenteLienHTML($cardinalite,$User){
                $tab['nord']='';
                $tab['sud']='';
                $tab['est']='';
                $tab['ouest']='';
                $Mnord = $this->getMapNord();
                $Msud = $this->getMapSud();
                $Mest = $this->getMapEst();
                $Mouest = $this->getMapOuest();
                $affichNord = false;
                $affichSud = false;
                $affichEst= false;
                $affichOuest = false;
                //si jamais il y a un mob on va quand meme passer à true la ou l'on vient
                switch ($cardinalite){
                    case 'nord':
                        $affichNord = true;
                        break;
                    case 'sud':
                        $affichSud = true;
                        break;
                    case 'ouest':
                        $affichOuest= true;
                        break;
                    case 'est':
                        $affichEst= true;
                        break;
                }
                if (count($this->getAllMobContre($User))==0){
                    $affichNord = true;
                    $affichSud = true;
                    $affichEst= true;
                    $affichOuest = true;
                }
                if($affichSud){
                    $tab['sud'] .= '<div class="sud">';
                    if(!is_null($Msud)){
                        $tab['sud'] .= '<a href="map.php?position='.$Msud->getPosition().'&cardinalite=nord">'.$Msud->getNom().'</a>';
                    }else{
                        $tab['sud'] .= '<a href="map.php?position=Generate&cardinalite=nord">Découvre cette région inconnue</a>';
                    }
                    $tab['sud'] .=  '</div>';
                } 
                //si il y a un mob la region est bloqué
                if($affichNord){
                    $tab['nord'] .= '<div class="nord">';
                    if(!is_null($Mnord)){
                        $tab['nord'] .= '<a href="map.php?position='.$Mnord->getPosition().'&cardinalite=sud">'.$Mnord->getNom().'</a>';
                    }else{
                        $tab['nord'] .= '<a href="map.php?position=Generate&cardinalite=sud">Découvre cette région inconnue</a>';
                    }
                    $tab['nord'] .=  '</div>';
                }
                if($affichEst){
                    $tab['est'] .= '<div class="est">';
                    if(!is_null($Mest)){
                        $tab['est'] .= '<a class="VerticalText" href="map.php?position='.$Mest->getPosition().'&cardinalite=ouest">'.$Mest->getNom().'</a>';
                    }else{
                        $tab['est'] .= '<a class="VerticalText" href="map.php?position=Generate&cardinalite=ouest">Découvre cette région inconnue</a>';
                    }
                    $tab['est'] .=  '</div>';
                }
                if($affichOuest){
                    $tab['ouest'] .= '<div class="ouest">';
                    if(!is_null($Mouest)){
                        $tab['ouest'] .= '<a class="VerticalText" href="map.php?position='.$Mouest->getPosition().'&cardinalite=est">'.$Mouest->getNom().'</a>';
                    }else{
                        $tab['ouest'] .= '<a class="VerticalText" href="map.php?position=Generate&cardinalite=est">Découvre cette région inconnue</a>';
                    }
                    $tab['ouest'] .=  '</div>';
                }
                return $tab;
            ?>
                </div>
            <?php
        }
        //retourn un tableau d'objet de 4 map 
        public function getMapAdjacente(){  
            $tabMapAdjacent = array();
            array_push($tabMapAdjacent, $this->mapNord);
            array_push($tabMapAdjacent, $this->mapSud);
            array_push($tabMapAdjacent, $this->mapEst);
            array_push($tabMapAdjacent, $this->mapOuest);
        }
        //Permet de générer un nom de map
        public function generateCarte(){
            $nom = "";
            $req = "Select * from TypeMap";
            $Result = $this->_bdd->query($req);
            $typemap=array();
            while($tab=$Result->fetch()){
                array_push($typemap,$tab);
            }
            $choixAleatoire= array_rand($typemap, 1);
            $type =  $typemap[$choixAleatoire];
            $nom = $type['nomFr'];
            $Adjectif = "";
            switch(rand(0,20)){
                case 0:
                    $Adjectif = "Poisseux";
                break;
                case 1:
                    $Adjectif = "Luxuriant";
                break;
                case 2:
                    $Adjectif = "Immense";
                break;
                case 3:
                    $Adjectif = "Enchantée";
                break;
                case 4:
                    $Adjectif = "Mortel";
                break;
                case 5:
                    $Adjectif = "Abandonné";
                break;
                case 6:
                    $Adjectif = "Enflammé";
                break;
                case 7:
                    $Adjectif = "Minuscule";
                break;
                case 8:
                    $Adjectif = "Lumineux";
                break;
                case 9:
                    $Adjectif = "Sombre";
                break;
                case 10:
                    $Adjectif = "Bouleversant";
                break;
                case 11:
                    $Adjectif = "Captivant";
                break;
                case 12:
                    $Adjectif = "Divin";
                break;
                case 13:
                    $Adjectif = "Épouvantable";
                break;
                case 14:
                    $Adjectif = "Exaltant";
                break;
                case 15:
                    $Adjectif = "Remarquable";
                break;
                case 16:
                    $Adjectif = "Somptueux";
                break;
                case 17:
                    $Adjectif = "Spiritueux";
                break;
                case 18:
                    $Adjectif = "Vivable";
                break;
                case 19:
                    $Adjectif = "Banal";
                break;
                default:
                    $Adjectif = "Haineux";
            }
            $Nom = "";
            switch (rand(0,101)){
                case 0:
                    $Nom = "Jewotia";
                break;
                case 1:
                    $Nom = "Flunition";
                break;
                case 2:
                    $Nom = "Vesetora";
                break;
                case 3:
                    $Nom = "Kriokkiatria";
                break;
                case 4:
                    $Nom = "Eafatha";
                break;
                case 5:
                    $Nom = "Tinnianet";
                break;
                case 6:
                    $Nom = "Riovacion";
                break;
                case 7:
                    $Nom = "Plealiorim";
                break;
                case 8:
                    $Nom = "Eagliolas";
                break;
                case 9:
                    $Nom = "Chiarradore";
                break;
                case 10:
                    $Nom = "Riowearion";
                break;
                case 11:
                    $Nom = "Brappianica";
                break;
                case 12:
                    $Nom = "Eogetall";
                break;
                case 13:
                    $Nom = "Ireathaer";
                break;
                case 14:
                    $Nom = "Iobeogana";
                break;
                case 15:
                    $Nom = "Blotianica";
                break;
                case 16:
                    $Nom = "Klecothan";
                break;
                case 17:
                    $Nom = "Ucomund";
                break;
                case 18:
                    $Nom = "Yaefirial";
                break;
                case 19:
                    $Nom = "Heajorus";
                break;
                case 20:
                    $Nom = "Utioros";
                break;
                case 21:
                    $Nom = "Issurhia";
                break;
                case 22:
                    $Nom = "Hirodin";
                break;
                case 23:
                    $Nom = "Neogritha";
                break;
                case 24:
                    $Nom = "Aeppulion";
                break;
                case 25:
                    $Nom = "Cruqorene";
                break;
                case 26:
                    $Nom = "Cleobiagarth";
                break;
                case 27:
                    $Nom = "Stafeadore";
                break;
                case 28:
                    $Nom = "Uthuaruta";
                break;
                case 29:
                    $Nom = "Illiliv";
                break;
                case 30:
                    $Nom = "Madus";
                break;
                case 31:
                    $Nom = "Bagua";
                break;
                case 32:
                    $Nom = "Croonia";
                break;
                case 33:
                    $Nom = "Lerocarro";
                break;
                case 34:
                    $Nom = "Aepra";
                break;
                case 35:
                    $Nom = "Xuaruta";
                break;
                case 36:
                    $Nom = "Tollypso";
                break;
                case 37:
                    $Nom = "Icion";
                break;
                case 38:
                    $Nom = "Tigeatune";
                break;
                case 39:
                    $Nom = "Uloclite";
                break;
                case 40:
                    $Nom = "Zoxawei";
                break;
                case 41:
                    $Nom = "Gramuliv";
                break;
                case 42:
                    $Nom = "Eivis";
                break;
                case 43:
                    $Nom = "Gophus";
                break;
                case 44:
                    $Nom = "Ibarvis";
                break;
                case 45:
                    $Nom = "Valleuruta";
                break;
                case 46:
                    $Nom = "Chubarth";
                break;
                case 47:
                    $Nom = "Ulmuetera";
                break;
                case 48:
                    $Nom = "Soimiers";
                break;
                case 49:
                    $Nom = "Avinesse";
                break;
                case 50:
                    $Nom = "Klefminns";
                break;
                case 51:
                    $Nom = "Carault";
                break;
                case 52:
                    $Nom = "Fexsorth";
                break;
                case 53:
                    $Nom = "Bellimar";
                break;
                case 54:
                    $Nom = "Roalême";
                break;
                case 55:
                    $Nom = "Charyonne";
                break;
                case 56:
                    $Nom = "Bormomble";
                break;
                case 57:
                    $Nom = "Maulet";
                break;
                case 58:
                    $Nom = "Poinoît";
                break;
                case 59:
                    $Nom = "Nuxine";
                break;
                case 60:
                    $Nom = "Nougarts";
                break;
                case 61:
                    $Nom = "Disart";
                break;
                case 62:
                    $Nom = "Marilly";
                break;
                case 63:
                    $Nom = "Ogluriton";
                break;
                case 64:
                    $Nom = "Avirac";
                break;
                case 65:
                    $Nom = "Bornorth";
                break;
                case 66:
                    $Nom = "Digueux";
                break;
                case 67:
                    $Nom = "Maifort";
                break;
                case 68:
                    $Nom = "Coltou";
                break;
                case 69:
                    $Nom = "Tougneux";
                break;
                case 70:
                    $Nom = "Tousart";
                break;
                case 71:
                    $Nom = "Toumasse";
                break;
                case 72:
                    $Nom = "Alenzon";
                break;
                case 73:
                    $Nom = "Narlès";
                break;
                case 74:
                    $Nom = "Toussonne";
                break;
                case 75:
                    $Nom = "Nanteaux";
                break;
                case 76:
                    $Nom = "Vayonne";
                break;
                case 77:
                    $Nom = "Vizia";
                break;
                case 78:
                    $Nom = "Ceolyra";
                break;
                case 79:
                    $Nom = "Kricaea";
                break;
                case 80:
                    $Nom = "Aichagary";
                break;
                case 81:
                    $Nom = "Gemeopia";
                break;
                case 82:
                    $Nom = "Odruicatia";
                break;
                case 83:
                    $Nom = "Sluicritia";
                break;
                case 84:
                    $Nom = "Slikhothen";
                break;
                case 85:
                    $Nom = "Credoria";
                break;
                case 86:
                    $Nom = "Veadour";
                break;
                case 87:
                    $Nom = "Ikakha";
                break;
                case 88:
                    $Nom = "Exuinada";
                break;
                case 89:
                    $Nom = "Stoca";
                break;
                case 90:
                    $Nom = "Aexotha";
                break;
                case 91:
                    $Nom = "Wolecan";
                break;
                case 92:
                    $Nom = "Buixiviel";
                break;
                case 93:
                    $Nom = "Oukhavaenid";
                break;
                case 94:
                    $Nom = "Iphilorian";
                break;
                case 95:
                    $Nom = "Hepoukya";
                break;
                case 96:
                    $Nom = "Deotor";
                break;
                case 97:
                    $Nom = "Lounao";
                break;
                case 98:
                    $Nom = "Staelia";
                break;
                case 99:
                    $Nom = "Aenidia";
                break;
                case 100:
                    $Nom = "Ovora";
                break;
                default:
                    $Nom = "Treoweth";
            }
            //la premiere case et le type en anglais pour une recherche d'image
            $tab[0]=$type['id'];
            $tab[1]=$type['nom'];
            $tab[2]=$nom ." ". $Adjectif." ".$Nom;
            return $tab;
        }
        //Permet de générer un nom de map
        public function generateMap(){
            $nom = "";
            $req = "Select * from TypeMap";
            $Result = $this->_bdd->query($req);
            $typemap=array();
            while($tab=$Result->fetch()){
                array_push($typemap,$tab);
            }
            $choixAleatoire= array_rand($typemap, 1);
            if($choixAleatoire == 21){
                $this->_isForge = true;
            }
            if($choixAleatoire == 22){
                $this->_isMarche = true;
            }
            $type =  $typemap[$choixAleatoire];
            $nom = $type['nomFr'];
            $Adjectif = "";
            switch (rand(0,10)){
                case 0:
                    $Adjectif = "Poisseux";
                break;
                case 1:
                    $Adjectif = "Luxuriant";
                break;
                case 2:
                    $Adjectif = "Verdattre";
                break;
                case 3:
                    $Adjectif = "Enchantée";
                break;
                case 4:
                    $Adjectif = "Mortel";
                break;
                case 5:
                    $Adjectif = "Minuscule";
                break;
                case 6:
                    $Adjectif = "Abandonné";
                break;
                case 7:
                    $Adjectif = "Hanté";
                break;
                case 8:
                    $Adjectif = "Lumineux";
                break;
                case 9:
                    $Adjectif = "Sombre";
                break;
                default:
                    $Adjectif = "Noir";
            }
            $Consone = "";
            for($i=0;$i<=rand(1,3);$i++){
                switch (rand(0,19)){
                    case 0:
                        $Consone = "bien";
                    break;
                    case 1:
                        $Consone = "dra";
                    break;
                    case 2:
                        $Consone = "bel";
                    break;
                    case 3:
                        $Consone = "con";
                    break;
                    case 4:
                        $Consone = "car";
                    break;
                    case 5:
                        $Consone = "den";
                    break;
                    case 6:
                        $Consone = "feu";
                    break;
                    case 7:
                        $Consone = "for";
                    break;
                    case 8:
                        $Consone = "ga";
                    break;
                    case 9:
                        $Consone = "lon";
                    break;
                    case 10:
                        $Consone = "la";
                    break;
                    case 11:
                        $Consone = "len";
                    break;
                    case 12:
                        $Consone = "mon";
                    break;
                    case 13:
                        $Consone = "ma";
                    break;
                    case 14:
                        $Consone = "ni";
                    break;
                    case 15:
                        $Consone = "nard";
                    break;
                    case 16:
                        $Consone = "pon";
                    break;
                    case 17:
                        $Consone = "pen";
                    break;
                    case 18:
                        $Consone = "ri";
                    break;
                    case 19:
                        $Consone = "or";
                    break;
                    default:
                    $Consone = " ";
                }
            }
            //la premiere case et le type en anglais pour une recherche d'image
            $tab[0]=$type['id'];
            $tab[1]=$type['nom'];
            $tab[2]=$nom ." ". $Adjectif." ".$Consone;
            return $tab;
        }
        //fonction de recherche récursive de map adjacent
        //retourne une map si elle se trouve 
        public function trouveMapAdjacente($map,$cardinalite){
            $x=$map->getX();
            $y=$map->getY();
            switch ($cardinalite){
                case "nord":
                    $y--;
                    break;
                case "sud":
                    $y++;
                    break;
                case "est":
                    $x--;
                    break;
                case "ouest":
                    $x++;
                    break;
            }
            $req = "select id from map where x='".$x."' AND y='".$y."'";
            $Result = $this->_bdd->query($req);
            if($tab = $Result->fetch()){
                $newmap = new Map($this->_bdd);
                $newmap->setMapByID($tab['id']);
                return $newmap;
            }
            return null;
        }
        public function getAleatoireImage($typeName){
            $typeName2 = str_replace(' ',',',$typeName);
            $url = "https://source.unsplash.com/random/?".$typeName2;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
            $result = stristr($result, 'https://',false);
            $result = stristr($result, '?',true);
            if($result=='https://status.unsplash.com/' || $result=='https://images.unsplash.com/source-404'){
                $result='https://i0.wp.com/supertrampontheroad.com/wp-content/uploads/2017/02/DSC_3793-1-2.jpg?resize=1024%2C678&ssl=1';
            }else{
                $result .= "?w=600";
            }
            return $result;
        }
        //retourne le nombre de map possédant un mob au minimum
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
        public function getMapWithoutMob(){
            $numberOfMap = 0;
            $temp = new map($this->_bdd);
            $mapWithMob = $temp->getMapWithOneMob();
            $res = $this->_bdd->query("SELECT * FROM Entite GROUP BY idMap");
            while($boucle = $res->fetch()){
                $numberOfMap++;
            }
            $numberOfMapWithoutMob = $numberOfMap - $mapWithMob;
            return $numberOfMapWithoutMob;
        }
    }
?>