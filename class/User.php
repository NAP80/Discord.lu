<?php
class User{
    private $_id;
    private $_login;
    private $_mdp;
    private $_name;
    private $_faction;
    private $_MonPersonnage;
    private $_admin;

    private $_bdd;

    public function __construct($bdd){
        $this->_bdd = $bdd;
    }
    public function setUser($id,$login,$mdp,$name,$admin){
        $this->_id = $id;
        $this->_login = $login;
        $this->_mdp = $mdp;
        $this->_name = $name;
        $this->_admin = $admin;
    }
    public function setUserById($id){
        $Result = $this->_bdd->query("SELECT * FROM `User` WHERE `id`='".$id."' ");
        if($tab = $Result->fetch()){ 
            $this->setUser($tab["id"],$tab["login"],$tab["mdp"],$tab["name"],$tab["admin"]);
            //chercher son personnage
            $personnage = new Personnage($this->_bdd);
            $personnage->setPersonnageById($tab["idPersonnage"]);
            $this->_MonPersonnage = $personnage;
        }
    }
    public function setPersonnage($Perso){
        $this->_MonPersonnage = $Perso;
        //je mémorise en base l'association du personnage dans user
        $req ="UPDATE `User` SET `idPersonnage`='".$Perso->getID()."' WHERE `id` = '".$this->_id."'";
        $Result = $this->_bdd->query($req);
    }
    //retour true si c'est un admin
    public function isAdmin(){
        return $this->_admin;
    }
    public function getName(){
        return $this->_name;
    }
    public function getId(){
        return $this->_id;
    }
    public function getFaction(){
        return $this->_faction;
    }
    public function getNomPersonnage(){
        return $this->_MonPersonnage->getNom();
    }
    public function getPersonnage(){
        return $this->_MonPersonnage;
    }
    public function getAllMyMobIds(){
        $listMob=array();
        $req="SELECT `id` FROM `Entite` WHERE `idUser` in (SELECT `id` FROM `Entite` WHERE `idUser` = '".$this->_id."') AND Type=2";
        $Result = $this->_bdd->query($req);
        while($tab=$Result->fetch()){
            array_push($listMob,$tab[0]);
        }
        return $listMob;
    }
    public function ConnectToi(){
        $errorMessage="";
        //si c'est une inscription on valide l'inscription et on le connect
        if( isset($_POST["sub"])){
            if($_POST['MDP'] == $_POST['password']) {
                if(!empty($_POST['name'])){
                    $req ="INSERT INTO `User`( `login`, `name`, `mdp`) VALUES ('".$_POST['login']."','".$_POST['name']."','".$_POST['password']."')";
                    $Result = $this->_bdd->query($req);
                }else{
                    $errorMessage = "Il faut écrire un name à l'inscription.";
                }
            }else{
                echo "Les mots de passes ne corespondent pas.";
            }
            
        }

        //traitement du formulaire
        $access = false;
        if(isset($_POST["login"]) && isset($_POST["password"])){
            //verif mdp en BDD
            $Result = $this->_bdd->query("SELECT * FROM `User` WHERE `login`='".$_POST['login']."' AND `mdp` = '".$_POST['password']."'");
            if($tab = $Result->fetch()){

                $this->setUserById($tab["id"]);

                //si mdp = ok
                $access = true;
                $_SESSION["idUser"]= $tab["id"];
                $_SESSION["Connected"]=true;
                $afficheForm = false;
                //si on est co on affiche le formulaire de deco
                $this->DeconnectToi();
            }else{
                if ($errorMessage==""){
                    $errorMessage = "Le mots de passe ne correspond pas.";
                }
                $afficheForm = true;
            }
        }else{
            $afficheForm = true;
        }

        if($afficheForm){
        ?>
            <div class="formlogin">
                <?php
                if ($errorMessage!=""){
                    echo '<div class="Red">'.$errorMessage.'</div>';
                }
                ?>
                <form action="" method="post" >
                    <div>
                        <label for="login">Mail :</label>
                        <input type="email" name="login" id="login" required >
                    </div>
                    <div>
                        <label for="password">Password :</label>
                        <input type="password" name="password" id="password" required>
                        <label class="inscriptionHide logSub" for="MDP">Réécrivez votre Password :</label>
                        <input class="inscriptionHide logSub" type="password" name="MDP" id="MDP">
                    </div>
                    <div>
                        <label class="inscriptionHide logSub" for="name">Pseudo :</label>
                        <input class="inscriptionHide logSub" type="text" name="name" id="name" >
                    </div>
                    <div>
                        <input type="submit" value="GO !" name="log" id="logSubsubmit"> <a class="inscriptionShow logSub" id="subCreatclick" onclick="inscription()">Cliquez pour vous inscrire.</a>
                    </div>
                </form>
            </div>
            <script>
                function inscription(){
                    var TabElements = document.getElementsByClassName("logSub");
                    for (var e of TabElements) {
                        e.classList.add('inscriptionShow');
                        e.classList.remove('inscriptionHide');
                    }
                    document.getElementById("logSubsubmit").setAttribute("name", "sub");
                    var e = document.getElementById("subCreatclick");
                    e.className = 'inscriptionHide';
                }
            </script>
        <?php
        }
        return $access;
    }

    public function DeconnectToi(){
        //traitement du formulaire
        $afficheForm = true;
        $access = true;
        if( isset($_POST["logout"]) && isset($_POST["logout"])){
            //si on se deco on raffiche le formulaire de co
            $_SESSION["Connected"]=false;
            session_unset();
            session_destroy();
            $this->ConnectToi();
            $afficheForm = false;
            $access = false;
        }else{
            $afficheForm = true;
        }
        if($afficheForm){
        ?>
            <form action="" method="post" >
                <div >
                    <input type="submit" value="Deco!" name="logout">
                </div>
            </form>
        <?php
        }
        return $access;
    }
    //retourne une carte de Div HTML de tracé de div
    public function getVisitesHTML($taille){
        //etape 1 récupéré toutes les visites du user
        $Map = $this->getPersonnage()->getMap();
        $maxX=$Map->getX()+$taille;
        $minX=$Map->getX()-$taille;
        $maxY=$Map->getY()+$taille;
        $minY=$Map->getY()-$taille;;

        if ($taille>0){
            $req="SELECT `map`.`id`,`map`.`x`,`map`.`y` 
            FROM `Visites`,`map` , `Entite`
            WHERE map.id = Visites.idMap 
            AND Visites.idPersonnage = Entite.id 
            AND `Entite`.`idUser`='".$this->_id."' 
            AND map.x >= '".$minX."' 
            AND map.x <= '".$maxX."' 
            AND map.y >= '".$minY."' 
            AND map.y <= '".$maxY."' 
            group by `Visites`.`idMap`";
        }else{
            $req="SELECT `map`.`id`,`map`.`x`,`map`.`y` 
            FROM `Visites`,`Entite`,`map` 
            WHERE map.id = Visites.idMap 
            AND Visites.idPersonnage = Entite.id 
            AND `Entite`.`idUser`='".$this->_id."' 
            group by `Visites`.`idMap`";
        }

        $Result = $this->_bdd->query($req);
        $allMap = array();

        while($visite = $Result->fetch()){
            //$allMap[x][y]=idmap
            if($visite['x'] > $maxX){
                $maxX = $visite['x'];
            }
            if($visite['x'] < $minX){
                $minX = $visite['x'];
            }
            if($visite['y'] > $maxY){
                $maxY = $visite['y'];
            }
            if($visite['y'] < $minY){
                $minY = $visite['y'];
            }

            $allMap[$visite['x']][$visite['y']]=$visite['id'];
        }

        $LargeurX = $maxX - $minX ;
        $HauteurY = $maxY - $minY ;

        ($LargeurX == 0)?$LargeurX =1:$LargeurX;

        $taille=200;

        $HY = $LX = round($taille/$LargeurX);
        $taille = $LX*$LargeurX;

        //permet de réadapter la taille en fonction de l'arondi qui a grossi les div

        $Map = $this->getPersonnage()->getMap();
        $MapScan = new Map($this->_bdd);

        $style = 'style="width:'.$taille.'px"';
        $styleCellule = 'style="width:'.$LX.'px;height:'.$HY.'px"';

        //On rajoute largeur de x pour laisser de la place à la border
        $ligneTaille = $LargeurX*$LX+$LargeurX*2;
        $styleLigne = 'style="width:'.$ligneTaille.'px;height:'.$HY.'px"';
        ?>
            <div class="map" <?= $style ?>>
                <?php
                    for($y=$maxY;$y>$minY;$y--){
                        ?>
                            <div class="mapLigne" <?= $styleLigne ?>>
                                <?php
                                    for($x=$minX;$x<$maxX;$x++){
                                      // Si User est positioné à la coordonné.
                                        if($y==$Map->getY() && $x==$Map->getX()){
                                            ?>
                                                <div class="mapPositionUser" <?= $styleCellule ?>>
                                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/26/Compass_Rose_French_North.svg/800px-Compass_Rose_French_North.svg.png" widht="<?= $LX ?>px" height="<?= $LX ?>px">
                                                </div>
                                            <?php
                                      // Si la coordonné est 0/0.
                                        }else if($y==0 && $x==0){
                                            ?>
                                                <div class="mapOrigine" <?= $styleCellule ?>></div>
                                            <?php
                                      // Si autre cas.
                                        }else{
                                          // Si Y existe dans la BDD.
                                            if(array_key_exists($x,$allMap)){
                                              // Si Y/X existe dans la BDD.
                                                if(array_key_exists($y,$allMap[$x])){
                                                  // Si déja visité par User.
                                                    if(!is_null($allMap[$x][$y])){
                                                      //map found check it bro
                                                        $MapScan->setMapByID($allMap[$x][$y]);
                                                      // Si coordonné ayant un ou des Monstres Non capturés.
                                                        if(count($MapScan->getAllMobContre($this))){
                                                            ?>
                                                                <div class="mapMob" <?= $styleCellule ?>></div>
                                                            <?php
                                                      // Si coordonné ayant un ou des Monstres capturés.
                                                        }else if (count($MapScan->getAllMobCapture($this))){
                                                            ?>
                                                                <div class="mapClear" <?= $styleCellule ?>></div>
                                                            <?php
                                                      // Si coordonné n'ayant aucun Monstres.
                                                        }else{
                                                            ?>
                                                                <div class="mapVerte" <?= $styleCellule ?>></div>
                                                            <?php
                                                        }
                                                  // Si jamais visité par User.
                                                    }else{
                                                        ?>
                                                            <div class="mapRouge" <?= $styleCellule ?>></div>
                                                        <?php
                                                    }
                                              // Si Y/X n'existe pas dans la BDD.
                                                }else{
                                                    ?>
                                                        <div class="mapRouge" <?= $styleCellule ?>></div>
                                                    <?php
                                                }
                                          // Si Y n'existe pas dans la BDD.
                                            }else{
                                                ?>
                                                    <div class="mapRouge" <?= $styleCellule ?>></div>
                                                <?php
                                            }
                                        }
                                    }
                                ?>
                            </div>
                        <?php
                    }
                ?>
            </div>
        <?php
    }
    //affiche tout les utilisateurs ainsi que leurs donnée (commande de préférance admin)
    public function showusers(){
        $ReturnAllUser1 = $this->_bdd->query("SELECT * FROM User");
        $ReturnAllUser = $ReturnAllUser1->fetch();
        return $ReturnAllUser;
    }
    //fonction pour modifier un name en base
    public function updateuser(){
        $Up = $this->_bdd->query("UPDATE `User` SET `name`='".$POST['newname']."' WHERE id=".$this->_id." ");
            if($Up){
                ?>
                    <p>Ton nom a bien été changé.</p>
                <?php
            }else{
                ?>
                    <p>Une erreur est survenue.</p>
                <?php
            }
    }
    //fonction pour supprimé un utilisateur version admin
    public function deleteuseradminversion(){
        $Del = $this->_bdd->query("DELETE FROM User WHERE id= ".$_POST['id']."");
            if($Del){
                ?>
                    <p>Utilisateur supprimé.</p>
                <?php
            }else{
                ?>
                    <p>Une erreur est survenue.</p>
                <?php
            }
    }
    //fonction pour ajouté un utilisateur
    public function adduser(){
        //ajoute un commentaire dans la base de la page du jeu selectionné
        $add = $this->_bdd->query("INSERT INTO User (login, name, mdp, idPersonnage, admin) VALUES (".$_POST['login'].",".$_POST['name'].",".$_POST['mdp'].",".$_POST['idPersonnage'].", 0 ) ");
        if($add){
            ?>
                <p>Utilisateur ajouté.</p>
            <?php
        } else {
            ?>
                <p>Une erreur est survenue.</p>
            <?php
        }
    }
    //fonction pour modifier un mot de passe
    public function updatepassword(){
        if (isset($_POST["updatemdp"])) {
            //comparaison du mot de passe avec l'ancien
            if($_POST['NEWMDP'] == $_POST['password']) {
                //mise a jour dans la base du nouveau mot de passe
                $rep = $this->_bdd->query("UPDATE `User` SET `mdp`='".$_POST['NEWMDP']."' WHERE id=".$this->_id." ");
                if($rep){
                    //succées
                    ?>
                        <p>Mot de passe changé.</p>
                    <?php
                }
                else{
                    //erreur a l'update dans la base
                    ?>
                        <p>Une erreur est survenue.</p>
                    <?php
                }
            }
            else{
                //message d'erreur
                ?>
                    <p>Les mot de passe ne correspondent pas.</p>
                <?php
            }
        }
    }
    //fonction pour modifier un mot de passe version admin
    public function updatepasswordadminversion(){
        if (isset($_POST["updateusermdp"])) {
            //mise a jour dans la base du nouveau mot de passe
            $rep = $this->_bdd->query("UPDATE `User` SET `mdp`='".$_POST['NEWMDP']."' WHERE `id`='".$_POST['id']."' ");
            if($rep){
                //succées
                ?>
                    <p>Le mot de passe de l'utilisateur a été changé.</p>
                <?php
            }else{
                //erreur a l'update dans la base
                ?>
                    <p>Une erreur est survenue.</p>
                <?php
            }
        }
    }

    public function nbUser(){
        $user = $this->_bdd->query("SELECT COUNT(*) name FROM User");
        $nbuser = $user->fetch();
        echo $nbuser['name'];
    }

    public function nbUserFaction(){
        $userfaction = $this->_bdd->query("SELECT COUNT(*) FROM faction, typepersonnage WHERE faction.id = typepersonnage.idFaction");
        $nbuserfaction = $user->fetch();
        echo $nbuserfaction[''];
    }

        /*
        fonction qui permet de modidier le status admin d'un user elle attend en paramatre l'id du user.
        */
    public function GiveAdmin($id){
        $req = 'SELECT `admin` FROM `user` WHERE id = '.$id.'';
        $excuteReq = $this->_bdd->query($req);
        $dataAdmin = $excuteReq->fetch();
        $dataAdmin['admin'];

        if($dataAdmin['admin'] == 0){
            $req = 'UPDATE `user` SET `admin`= "1" WHERE id ='.$id.'';
            $excuteReq = $this->_bdd->query($req);
        }else if($dataAdmin['admin'] == 1){
            $req = 'UPDATE `user` SET `admin`= "0" WHERE id ='.$id.'';
            $excuteReq = $this->_bdd->query($req);
        }
    }

    /** Assigne une Faction à l'User */
    public function setFaction($idFaction){
        $IdUser = $this->_id;
        /* Check isset IdFaction in BDD */
        $req = "SELECT COUNT(*) FROM `faction` WHERE `id` = '".$idFaction."'";
        $Result = $this->_bdd->query($req);
        $Result = $Result->fetch();
        if($Result['COUNT(*)'] != NULL){
            // Si existe en BDD
            $req = "UPDATE `user` SET `idFaction` = '".$idFaction."' WHERE `id` = '".$IdUser."'";
            $Result = $this->_bdd->query($req);
            $Result = $Result->fetch();
            $Faction = new Faction($this->_bdd);
            $Faction->setFactionById($_SESSION['Faction']);
            $FactionUser = $Faction;
            $RepMSG = "Vous êtes maintenant dans la faction ".$FactionUser->getNom()." .";
            echo $RepMSG;
        }
        else{
            // Si n'existe pas
            $RepMSG = "La faction n'existe pas.";
            echo $RepMSG;
        }
    }
}
?>