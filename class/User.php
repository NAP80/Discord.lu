<?php
    class User extends TypeUser{
        private $_bdd;

        private $_idUser;
        private $_email;
        private $_pseudo;
        private $_password_hash;
        private $_token;
        private $_idPersonnage;
        private $_idFaction;
        private $_dateUser;
        private $_typeUser;

        private $_infoPerso; // Information du personnage en cours

        public function __construct($bdd){
            Parent::__construct($bdd);
            $this->_bdd = $bdd;
        }

        /** Récupère User */
        public function setUser($idUser,$email,$pseudo,$password_hash,$token,$idPersonnage,$idFaction,$dateUser,$typeUser){
            $this->_idUser = $idUser;
            $this->_email = $email;
            $this->_pseudo = $pseudo;
            $this->_password_hash = $password_hash;
            $this->_token = $token;
            $this->_idPersonnage = $idPersonnage;
            $this->_idFaction = $idFaction;
            $this->_dateUser = $dateUser;
            $this->_typeUser = $typeUser;
            $this->setTypeUserById($typeUser);
        }

        /** Return ID */
        public function getIdUser(){
            return $this->_idUser;
        }

        /** Return Email */
        public function getEmail(){
            return $this->_email;
        }

        /** Return Pseudo */
        public function getPseudo(){
            return $this->_pseudo;
        }

        /** Return Password_hash */
        public function getPassword_hash(){
            return $this->_password_hash;
        }

        /** Return Token */
        public function getToken(){
            return $this->_token;
        }

        /** Return Id du personnage en cours de l'User */
        public function getIdPersonnage(){
            return $this->_idPersonnage;
        }   

        /** Return Faction */
        public function getIdFaction(){
            return $this->_idFaction;
        }

        /** Return DateUser */
        public function getDateUser(){
            return $this->_dateUser;
        }

        /** Return TypeUser User */
        public function getTypeUser(){
            return $this->_typeUser;
        }

        /** Return Nom du personnage en cours de l'User : À dégager */
        public function getNomPersonnage(){
            return $this->_infoPerso->getNameEntite();
        }

        /** Return Object Personnage */
        public function getPersonnage(){
            return $this->_infoPerso;
        }

        public function generateToken(){
            $token = openssl_random_pseudo_bytes(20);
            $token = bin2hex($token);
            return $token;
        }

        /** Set User By Token */
        public function setUserByToken($token){
            $Result = $this->_bdd->query("SELECT * FROM `User` WHERE `token`='".$token."'"); // Optimisable en récupérant aussi le TypeUser
            // Authentification si Correct et à jours.
            if($tab = $Result->fetch()){
                $this->setUser($tab["idUser"],$tab["email"],$tab["pseudo"],$tab["password_hash"],$tab["token"],$tab["idPersonnage"],$tab["idFaction"],$tab["dateUser"],$tab["typeUser"]);
                // Set son Personnage
                $personnage = new Personnage($this->_bdd);
                $personnage->setPersonnageById($tab["idPersonnage"]);
                $this->_infoPerso = $personnage;
            }
            // Déconnection si non à jours.
            else{
                $_SESSION["Connected"] = false;
                session_unset();
                session_destroy();
                $access = false;
                return $access;
            }
        }

        /** Set User By ID */
        public function setUserById($idUser){
            $Result = $this->_bdd->query("SELECT * FROM `User` WHERE `idUser`='".$idUser."'");
            if($tab = $Result->fetch()){
                $this->setUser($tab["idUser"],$tab["email"],$tab["pseudo"],$tab["password_hash"],$tab["token"],$tab["idPersonnage"],$tab["idFaction"],$tab["dateUser"],$tab["typeUser"]);
                // Set son Personnage
                $personnage = new Personnage($this->_bdd);
                $personnage->setPersonnageById($tab["idPersonnage"]);
                $this->_infoPerso = $personnage;
            }
        }

        /** Set Object Personnage */
        public function setPersonnage($Perso){
            $this->_infoPerso = $Perso;
            $req ="UPDATE `User` SET `idPersonnage`='".$Perso->getIdUser()."' WHERE `idUser` = '".$this->_idUser."'";
            $Result = $this->_bdd->query($req);
        }

        /** Get Nombres de Personnages */
        public function getNbPersonnages(){
            $req ="SELECT COUNT(*) FROM `entite` WHERE `type`=1 AND `idUser` = '".$this->_idUser."'";
            $Result = $this->_bdd->query($req);
            $Count = $Result->fetch();
            return $Count['COUNT(*)'];
        }

        /** Affiche Formulaire Création Personnage */
        public function CreatNewPersonnage(){
            ?>
                <div class="divCreatPerso">
                    <?php 
                        $idFactionUser = $this->getIdFaction();
                        $TypePersos = $this->getAllTypePersonnage($idFactionUser);
                        $TypePerso = $TypePersos[rand(0,count($TypePersos)-1)];
                        $personnage = new Personnage($this->_bdd);
                        $imageUrl = $personnage->generateImage($TypePerso->getNameTypePerso());
                        ?>
                            <form action="" method="post" class="formCreatPerso">
                                <p>Créez un personnage :</p>
                                <input type="text" name="Name" required>
                                <?php
                                    // En fait là on récupère les type de personnages en fonction de son ID de Faction
                                    $TypePersos = $this->getAllTypePersonnage($idFactionUser);
                                    ?>
                                        <div class="listTypePerso">
                                            <?php
                                                foreach($TypePersos as $TypePerso){
                                                    ?>
                                                        <div class="listTypePerso">
                                                            <input type="radio" name="TypePerso" id="Type<?= $TypePerso->getIdTypePerso() ?>" value="<?= $TypePerso->getIdTypePerso() ?>">
                                                            <label for="Type<?= $TypePerso->getIdTypePerso() ?>"><?= $TypePerso->getNameTypePerso() ?></label>
                                                            <input type="hidden" name="image" value="<?= $imageUrl ?>">
                                                        </div>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                        <input type="submit" value="Creer" name="createPerso">
                                    <?php
                                ?>
                            </form>
                        <?php
                    ?>
                </div>
            <?php
            if(isset($_POST["createPerso"]) && isset($_POST["TypePerso"]) &&isset($_POST["Name"]) && isset($_POST["image"]) && !is_null($idFactionUser)){
                $newperso = new Personnage($this->_bdd);
                $newperso = $newperso->CreateEntite($_POST['Name'], 100, 10, 1,100,$_POST['image'],$this->getIdUser(),1,1);
                $idTypePersonnage = $_POST['TypePerso'];
                $req="INSERT INTO `Personnage`(`idPersonnage`,`xp`,`idTypePersonnage`) VALUES ('".$newperso->getIdEntite()."','1','".$idTypePersonnage."')";
                $Result = $this->_bdd->query($req);
                $newperso->setEntiteById($newperso->getIdEntite());
                // Assignation du Personnage à l'User
                $this->setPersonnage($newperso);
                // Redirection - À patch le fait qu'il faut actuliser la page pour avoir son perso... Truc Temporaire
                ?>
                    <script>window.location.replace("./combat.php");</script>
                <?php
            }
        }

        /** Formulaire choix de Faction */
        public function getFormFaction(){
            ?>
                <h2>Choisisez une faction :</h2>
                <p>La Faction définira votre groupe de joueur et votre "camps".</p>
                <div>
                    <?php
                        $Result = $this->_bdd->query("SELECT * FROM `Faction`");
                        while($tabFaction = $Result->fetch()){
                            ?>
                                <div class="formfaction faction_<?= $tabFaction['idFaction'] ?>">
                                    <p><?= $tabFaction['nameFaction'] ?></p>
                                    <p><?= $tabFaction['descFaction'] ?></p>
                                    <img src="./assets/image/<?= $tabFaction['logoFaction'] ?>">
                                    <a id="confirmFaction" class="ui-button ui-widget ui-corner-all" onclick="idFaction='<?= $tabFaction['idFaction'] ?>', nameFaction='<?= $tabFaction['nameFaction'] ?>', confirmFaction(nameFaction)">
                                        Rejoindre !
                                    </a>
                                </div>
                            <?php
                        }
                    ?>
                </div>
                <?php // Script ?>
                <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
                <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
                <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
                <script>
                    function confirmFaction(nameFaction){
                        var form = document.createElement('div');
                        form.innerHTML =    '<div id="dialog-confirm" title="Rejoindre ' + nameFaction + '">'+
                                            '   <div>'+
                                            '       <span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>'+
                                            '       Vous allez rejoindre la faction ' + nameFaction + '.<br>'+
                                            '       Vous ne pourrez pas changer de Faction avant longtemps.'+
                                            '   </div>'+
                                            '   <form method="POST" action="" id="form-faction">'+
                                            '       <input type="hidden" name="faction-id" value="' + idFaction + '" class="text ui-widget-content ui-corner-all">'+
                                            '   </form>'+
                                            '</div>';
                        form.setAttribute('id','dialog-confirm');
                        form.setAttribute('title', 'Rejoindre ' + nameFaction);
                        document.body.appendChild(form);
                        $("#dialog-confirm").dialog({
                            resizable:false,
                            height:"auto",
                            width:400,
                            modal:true,
                            buttons:{
                                "Confirmer":function(){
                                    var formfac = document.getElementById('form-faction');
                                    formfac.submit();
                                },
                                "Annuler":function(){
                                    $(this).dialog("close");
                                    $('div').remove('#dialog-confirm');
                                    $('div').remove('.ui-dialog .ui-corner-all .ui-widget .ui-widget-content .ui-front .ui-dialog-buttons .ui-draggable');
                                }
                            },
                            close: function() {
                                $('div').remove('#dialog-confirm');
                                $('div').remove('.ui-dialog .ui-corner-all .ui-widget .ui-widget-content .ui-front .ui-dialog-buttons .ui-draggable');
                            }
                        }); 
                    };
                </script>
            <?php
        }

        /** Return un tableau des type de personnages en fonction de l'ID Faction */ // À Migrer sur une autre page, n'a rien à faire en User
        public function getAllTypePersonnage($idFactionUser){
            $ListPerso = array();
            $Result = $this->_bdd->query("SELECT * FROM `TypePersonnage` WHERE idFaction = '".$idFactionUser."'");
            while($tab=$Result->fetch()){
                $TypePerso = new TypePersonnage($this->_bdd);
                $TypePerso->setTypePersonnageById($tab['idTypePerso']);
                array_push($ListPerso,$TypePerso);
            }
            return $ListPerso;
        }

        /** Return List de tout Monster Capturé par ID User */
        public function getAllMyMonsterIds(){
            $listMonster=array();
            $req="SELECT `idEntite` FROM `Entite` WHERE `idUser` in (SELECT `idEntite` FROM `Entite` WHERE `idUser` = '".$this->_idUser."') AND Type=2";
            $Result = $this->_bdd->query($req);
            while($tab=$Result->fetch()){
                array_push($listMonster,$tab[0]);
            }
            return $listMonster;
        }

        public function ConnectToi(){
            $access = false;
            $afficheForm = true;
            // PHP Inscription
            if((isset($_POST["pseudo"])) && (isset($_POST["email"])) && (isset($_POST["password"])) && (isset($_POST["password_confirmation"])) && (isset($_POST["cgu"]))){
                if($_POST["cgu"]){
                    if(($_POST['password'] == $_POST['password_confirmation']) && (!empty($_POST['password']))){
                        if((!empty($_POST['pseudo'])) && (!empty($_POST['email']))){
                            $CheckPseudo = preg_replace('#[^A-Za-z0-9]#','',$_POST['pseudo']);
                            if($_POST['pseudo'] == $CheckPseudo){
                                $CheckMail = preg_replace('#[^A-Za-z0-9.@]#','',$_POST['email']);
                                if($_POST['email'] == $CheckMail){
                                    $Count = $this->_bdd->query("SELECT COUNT(*) FROM `User` WHERE `email`='".$_POST['email']."' OR `pseudo`='".$_POST['pseudo']."'");
                                    $CountNb = $Count->fetch();
                                    if($CountNb['COUNT(*)'] == 0){
                                        $PasswordHash = password_hash($_POST['password'], PASSWORD_BCRYPT);
                                        $token = $this->generateToken();
                                        $req = "INSERT INTO `User`( `email`, `pseudo`, `password_hash`, `token`) VALUES ('".$_POST['email']."','".$_POST['pseudo']."','".$PasswordHash."','".$token."')";
                                        $Result = $this->_bdd->query($req);
                                        $RepMsgRegister = "Compte crée!";
                                        // Connexion
                                        $this->setUserByToken($token);
                                        $_SESSION["token"] = $token;
                                        $_SESSION["Connected"]=true;
                                        $afficheForm = false;
                                        $access = true;
                                        $this->DeconnectToi();
                                    }
                                    else{
                                        $RepMsgRegister = "L'email ou le pseudo sont déjà utilisés.";
                                    }
                                }
                                else{
                                    $RepMsgRegister = "L'email n'est pas conforme.";
                                }
                            }
                            else{
                                $RepMsgRegister = "Le pseudo ne doit pas contenir de caractères spéciaux.";
                            }
                        }
                        else{
                            $RepMsgRegister = "Le pseudo et l'email sont nécessaire.";
                        }
                    }
                    else{
                        $RepMsgRegister = "Les mots de passes ne corespondent pas.";
                    }
                }
                else{
                    $RepMsgRegister = "Les CGU doivent être acceptés.";
                }
            }
            // PHP Connexion
            if((isset($_POST["login"])) && (isset($_POST["password"]))){
                if((!empty($_POST["login"])) && (!empty($_POST["password"]))){
                    $Result = $this->_bdd->query("SELECT * FROM `User` WHERE `email`='".$_POST['login']."' OR `pseudo`='".$_POST['login']."'");
                    $tab = $Result->fetch();
                    if((password_verify($_POST["password"], $tab['password_hash'])) && ($tab['password_hash'] != NULL)){
                        $this->setUserByToken($tab["token"]);
                        $_SESSION["token"] = $tab["token"];
                        $_SESSION["Connected"]=true;
                        $afficheForm = false;
                        $access = true;
                        $this->DeconnectToi();
                    }
                    else{
                        $RepMsgLogin = "Login ou mots de passe incorrect.";
                    }
                }
                else{
                    $RepMsgLogin = "Des éléments sont manquants.";
                }
            }
            if(isset($RepMsgRegister)){
                echo $RepMsgRegister;
            }
            if(isset($RepMsgLogin)){
                echo $RepMsgLogin;
            }
            if($afficheForm){
                ?>
                    <div class="formlogin">
                        <a id="Connect" class="ui-button ui-widget ui-corner-all" onclick="dialogRegister()">
                            S'inscrire !
                        </a>
                        <a id="Register" class="ui-button ui-widget ui-corner-all" onclick="dialogLogin()">
                            Se connecter !
                        </a>
                    </div>
                <?php
                // Script
                ?>
                    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
                    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
                    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
                    <script>
                        function dialogRegister(){
                            var dialogRegister = document.createElement('div');
                            dialogRegister.innerHTML =
                                        '<form method="POST" action="" id="form-register">'+
                                        '   <div>'+
                                        '       <label for="pseudo">Pseudo :</label>'+
                                        '       <input type="text" name="pseudo" id="pseudo" class="text ui-widget-content ui-corner-all" required>'+
                                        '   </div>'+
                                        '   <div>'+
                                        '       <label for="email">E-mail :</label>'+
                                        '       <input type="text" name="email" id="email" class="text ui-widget-content ui-corner-all" required>'+
                                        '   </div>'+
                                        '   <div>'+
                                        '       <label for="password">Mot de passe :</label>'+
                                        '       <input type="password" name="password" id="password" class="text ui-widget-content ui-corner-all" required>'+
                                        '       <label for="password_confirmation">Confirmation :</label>'+
                                        '       <input type="password" name="password_confirmation" id="password_confirmation" class="text ui-widget-content ui-corner-all" required>'+
                                        '   </div>'+
                                        '   <div>'+
                                        '       <input type="checkbox" id="cgu" name="cgu" required>'+
                                        '       <label for="cgu">J\'accepte les termes des CGU et de la politique de confidentialité.</label>'+
                                        '   </div>'+
                                        '   <input type="submit" id="submitRegister" tabindex="-1" style="display:none">'+
                                        '</form>';
                            dialogRegister.setAttribute('id','dialog-register');
                            dialogRegister.setAttribute('title', 'Inscription');
                            document.body.appendChild(dialogRegister);
                            $("#dialog-register").dialog({
                                resizable:false,
                                height:"auto",
                                width:400,
                                modal:true,
                                buttons:{
                                    "S'inscrire":function(){
                                        document.getElementById('submitRegister').click();
                                    },
                                    "Annuler":function(){
                                        $(this).dialog("close");
                                        $('div').remove('#dialog-register');
                                        $('div').remove('.ui-dialog .ui-corner-all .ui-widget .ui-widget-content .ui-front .ui-dialog-buttons .ui-draggable');
                                    }
                                },
                                close:function(){
                                    $('div').remove('#dialog-register');
                                    $('div').remove('.ui-dialog .ui-corner-all .ui-widget .ui-widget-content .ui-front .ui-dialog-buttons .ui-draggable');
                                }
                            }); 
                        };
                        function dialogLogin(){
                            var dialogLogin = document.createElement('div');
                            dialogLogin.innerHTML =
                                        '<form method="POST" action="" id="form-login">'+
                                        '   <div>'+
                                        '       <label for="login">Pseudo ou E-mail :</label>'+
                                        '       <input type="text" name="login" id="login" class="text ui-widget-content ui-corner-all" required>'+
                                        '   </div>'+
                                        '   <div>'+
                                        '       <label for="password">Mot de passe :</label>'+
                                        '       <input type="password" name="password" id="password" class="text ui-widget-content ui-corner-all" required>'+
                                        '   </div>'+
                                        '   <input type="submit" id="submitLogin" tabindex="-1" style="display:none">'+
                                        '</form>';
                            dialogLogin.setAttribute('id','dialog-login');
                            dialogLogin.setAttribute('title', 'Connexion');
                            document.body.appendChild(dialogLogin);
                            $("#dialog-login").dialog({
                                resizable:false,
                                height:"auto",
                                width:400,
                                modal:true,
                                buttons:{
                                    "Se connecter":function(){
                                        document.getElementById('submitLogin').click();
                                    },
                                    "Annuler":function(){
                                        $(this).dialog("close");
                                        $('div').remove('#dialog-login');
                                        $('div').remove('.ui-dialog .ui-corner-all .ui-widget .ui-widget-content .ui-front .ui-dialog-buttons .ui-draggable');
                                    }
                                },
                                close:function(){
                                    $('div').remove('#dialog-login');
                                    $('div').remove('.ui-dialog .ui-corner-all .ui-widget .ui-widget-content .ui-front .ui-dialog-buttons .ui-draggable');
                                }
                            });
                        };
                    </script>
                <?php
            }
            return $access;
        }

        public function DeconnectToi(){
            //traitement du formulaire
            $afficheForm = true;
            $access = true;
            if(isset($_POST["logout"])){
                $_SESSION["Connected"] = false;
                session_unset();
                session_destroy();
                $this->ConnectToi();
                $afficheForm = false;
                $access = false;
            }
            if($afficheForm){
                ?>
                    <form action="" method="post">
                        <div>
                            <input type="submit" value="Deco!" name="logout">
                        </div>
                    </form>
                <?php
            }
            return $access;
        }

        /** Affiche la Map HTML */ // À Migrer sur la page Map
        public function getVisitesHTML($taille){
            $Map = $this->getPersonnage()->getMap();
            $maxX=$Map->getX()+$taille;
            $minX=$Map->getX()-$taille;
            $maxY=$Map->getY()+$taille;
            $minY=$Map->getY()-$taille;
            if($taille>0){
                $req="SELECT `Map`.`idMap`,`Map`.`x`,`Map`.`y`
                FROM `Visites`,`Map`,`Entite`
                WHERE Map.idMap = Visites.idMap 
                AND `Visites`.`idPersonnage` = `Entite`.`idEntite`
                AND `Entite`.`idUser`='".$this->_idUser."'
                AND `Map`.`x` >= '".$minX."'
                AND `Map`.`x` <= '".$maxX."'
                AND `Map`.`y` >= '".$minY."'
                AND `Map`.`y` <= '".$maxY."'
                group by `Visites`.`idMap`";
            }
            else{
                $req="SELECT `Map`.`idMap`,`Map`.`x`,`Map`.`y`
                FROM `Visites`,`Entite`,`Map`
                WHERE `Map`.`idMap` = `Visites`.`idMap`
                AND `Visites`.idPersonnage` = `Entite.idEntite`
                AND `Entite`.`idUser`='".$this->_idUser."'
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
                $allMap[$visite['x']][$visite['y']]=$visite['idMap'];
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
                                            }
                                            else if($y==0 && $x==0){
                                                ?>
                                                    <div class="mapOrigine" <?= $styleCellule ?>></div>
                                                <?php
                                            // Si autre cas.
                                            }
                                            else{
                                                // Si Y existe dans la BDD.
                                                if(array_key_exists($x,$allMap)){
                                                    // Si Y/X existe dans la BDD.
                                                    if(array_key_exists($y,$allMap[$x])){
                                                        // Si déjà visité par User.
                                                        if(!is_null($allMap[$x][$y])){
                                                            //map found check it bro
                                                            $MapScan->setMapByID($allMap[$x][$y]);
                                                            // Si coordonné ayant un ou des Monstres Non capturés.
                                                            if(count($MapScan->getAllMonsterContre($this))){
                                                                ?>
                                                                    <div class="mapMonster" <?= $styleCellule ?>></div>
                                                                <?php
                                                            // Si coordonné ayant un ou des Monstres capturés.
                                                            }
                                                            else if(count($MapScan->getAllMonsterCapture($this))){
                                                                ?>
                                                                    <div class="mapClear" <?= $styleCellule ?>></div>
                                                                <?php
                                                            // Si coordonné n'ayant aucun Monstres.
                                                            }
                                                            else{
                                                                ?>
                                                                    <div class="mapVerte" <?= $styleCellule ?>></div>
                                                                <?php
                                                            }
                                                        // Si jamais visité par User.
                                                        }
                                                        else{
                                                            ?>
                                                                <div class="mapRouge" <?= $styleCellule ?>></div>
                                                            <?php
                                                        }
                                                    // Si Y/X n'existe pas dans la BDD.
                                                    }
                                                    else{
                                                        ?>
                                                            <div class="mapRouge" <?= $styleCellule ?>></div>
                                                        <?php
                                                    }
                                                // Si Y n'existe pas dans la BDD.
                                                }
                                                else{
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

        /** Return List de toutes les infos User */
        public function showusers(){
            $ReturnAllUser1 = $this->_bdd->query("SELECT * FROM User");
            $ReturnAllUser = $ReturnAllUser1->fetch();
            return $ReturnAllUser;
        }

        /** Set Pseudo : À modifier */
        public function updateuser($newpseudo){
            $Up = $this->_bdd->query("UPDATE `User` SET `pseudo`='".$newpseudo."' WHERE idUser=".$this->_idUser." ");
            if($Up){
                ?>
                    <p>Le pseudo a bien été changé.</p>
                <?php
            }
            else{
                ?>
                    <p>Une erreur est survenue.</p>
                <?php
            }
        }

        /** Set Password : À modifier */
        public function updatepassword(){
            if(isset($_POST["update_password_hash"])){
                //comparaison du mot de passe avec l'ancien
                if($_POST['New_password_hash'] == $_POST['password']){
                    //mise a jour dans la base du nouveau mot de passe
                    $rep = $this->_bdd->query("UPDATE `User` SET `password_hash`='".$_POST['New_password_hash']."' WHERE idUser=".$this->_idUser." ");
                    if($rep){
                        ?>
                            <p>Mot de passe changé.</p>
                        <?php
                    }
                    else{
                        ?>
                            <p>Une erreur est survenue.</p>
                        <?php
                    }
                }
                else{
                    ?>
                        <p>Les mot de passe ne correspondent pas.</p>
                    <?php
                }
            }
        }

        /** Assigne une Faction à l'User */
        public function setFaction($idFaction){
            /* Check isset IdFaction in BDD */
            $req = "SELECT COUNT(*) FROM `faction` WHERE `idFaction` = '".$idFaction."'";
            $Result = $this->_bdd->query($req);
            $ResultTab=$Result->fetch();
            if($ResultTab['COUNT(*)'] != 0){
                // Si existe en BDD
                $req = "UPDATE `user` SET `idFaction` = '".$idFaction."' WHERE `idUser` = '".$this->_idUser."'";
                $Result = $this->_bdd->query($req);
                $Result = $Result->fetch();
                $this->_idFaction = $idFaction;
                $FactionUser = new Faction($this->_bdd);
                $FactionUser->setFactionById($idFaction);
                $RepMSG = "Vous êtes maintenant dans la faction ".$FactionUser->getNameFaction()." .";
                echo $RepMSG;
            }
            else{
                // Si n'existe pas
                $RepMSG = "La faction n'existe pas.";
                echo $RepMSG;
            }
        }

        /** Return HTML des Personnages d'un User et permet d'atribuer un perso à un User */
        public function getChoixPersonnage(){
            if((isset($_POST["AssignePerso"])) && (isset($_POST["IdPerso"]))){// À faire check si idUser est bon ou si Admin
                $Personnage = New Personnage($this->_bdd);
                $Personnage->setPersonnageById($_POST["IdPerso"]);
                if(($Personnage->getIdUser() == $this->getIdUser()) || $this->getPermAdmin()){
                    $this->setPersonnage($Personnage); // Assignation du Personnage à l'User
                    if($Personnage->_healthNow <= 0 ){
                        $Personnage->resurection();
                    }
                }
            }
            $Result = $this->_bdd->query("SELECT * FROM `Entite` where idUser='".$this->getIdUser()."' AND type=1");
            ?>
                <p>Choisir un personnage :</p>
                <form action="" method="post" class="formChangePerso">
                    <?php
                        while($Personnage = $Result->fetch()){
                            ?>
                                <div class="listTypePerso">
                                    <input type="radio" name="IdPerso" id="Perso<?= $Personnage['idEntite'] ?>" value="<?= $Personnage['idEntite'] ?>">
                                    <label for="Perso<?= $Personnage['idEntite'] ?>"><?= $Personnage['nom'] ?></label>
                                </div>
                            <?php
                        }
                    ?>
                    <input type="submit" value="Prendre ce personnage" name="AssignePerso">
                </form>
            <?php
        }
    }
?>