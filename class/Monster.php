<?php
    // Beaucoup de Similitude entre Personnage/Entité -> Refactoriser avec héritage
    class Monster extends TypeMonster{
        private $_coefXP; // À dégager
        private $_idTypeMonster;

        public function __construct($bdd){
            Parent::__construct($bdd);
        }

        public function setMonsterById($idEntite){
            Parent::setEntiteById($idEntite);
            $this->initInfo($idEntite);
        }

        /** Return CoefXp */ // À dégager
        public function getCoefXp(){
            return $this->_coefXP;
        }

        /** Return Type Monster */
        public function getTypeMonster(){
            return $this->_idTypeMonster;
        }

        private function initInfo($idEntite){
            //select les info personnage
            $req = "SELECT * FROM `Monster` WHERE idEntite='".$idEntite."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $this->_idTypeMonster  = $tab['idTypeMonster'];
                $this->_coefXP  = $tab['coefXp'];
            }
            else{
                $req  = "INSERT  INTO `Monster` (idEntite,idTypeMonster,coefXp) VALUE ('".$idEntite."','0','1')";
                $Result = $this->_bdd->query($req);
            }
        }

        //methode appelé quand un personnage attaque un Monster
        //le perso est passé en param return 0 si pas possible d'attaquer
        public function SubitDegat($Entite){
            $Attaque = $Entite->getAttaque();
            $CoolDown = $Entite->getCoolDownAttaque();
            $CoupCritique = 'coolDown';
            //is coolDOwn < 0 c'est que l'attaque est tjs en cours
            if($CoolDown > 0){
                //l'attaque est en cours on met a jout le cooldown pour ne pas spam l'attaque
                $Entite->resetCoolDown();
                //Ajout Aléatoire pour coup critique PVE (15% de chance d'acctivation // 50% de dégats en plus):
                $CC = random_int(1, 100);
                if($CC >=1 && $CC <= 15){
                    $degat = $Attaque * 1.5;
                    $degat = round($degat);
                    $this->_healthNow = $this->_healthNow - $degat;
                    if($degat > 1){
                        $CoupCritique = "Coup Critique ! Vous avez infligé ".$degat." points de dégâts.";
                    }
                    else{
                        $CoupCritique = "Coup Critique ! Vous avez infligé ".$degat." point de dégât.";
                    }
                }
                else{
                    $degat = $Attaque;
                    $degat = round($degat);
                    $this->_healthNow = $this->_healthNow - $degat;
                    if($degat > 1){
                        $CoupCritique = "Vous avez infligé ".$degat." points de dégâts.";
                    }
                    else{
                        $CoupCritique = "Vous avez infligé ".$degat." point de dégât.";
                    }
                }
                $coupFatal = 0;
                if($this->_healthNow <= 0){
                    $this->_healthNow = 0;
                    $coupFatal = 1;
                    //on va attribuer le monstre au personnage, sa healthNow revient a fond pour le propriétaire
                    $req    = "UPDATE `Entite` SET `healthNow`='".$this->_healthMax."',`idUser`='".$Entite->getIdUser()."' WHERE `idEntite` = '".$this->_idEntite."'";
                    $Result = $this->_bdd->query($req);
                }
                else{
                    $req    = "UPDATE `Entite` SET `healthNow`='".$this->_healthNow ."' WHERE `idEntite` = '".$this->_idEntite ."'";
                    $Result = $this->_bdd->query($req);
                }
                //on va rechercher l'historique
                $req    = "SELECT * FROM `AttaquePersoMonster` WHERE idMonster = '".$this->_idEntite."' and idPersonnage = '".$Entite->getIdEntite()."'";
                $Result = $this->_bdd->query($req);
                $tabAttaque['nbCoup']=0;
                $tabAttaque['DegatsDonnes']=0;
                $tabAttaque['DegatsReçus']=$Entite->getAttaque();
                if($tab=$Result->fetch()){
                    $tabAttaque = $tab;
                    $tabAttaque['DegatsReçus']+=$Entite->getAttaque();
                    $tabAttaque['nbCoup']++;
                }
                else{
                    //insertion d'une nouvelle attaque
                    $req    = "INSERT INTO `AttaquePersoMonster`(`idMonster`, `idPersonnage`, `nbCoup`, `coupFatal`, `DegatsDonnes`, `DegatsReçus`)
                    VALUES (
                        '".$this->_idEntite."','".$Entite->getIdEntite()."',1,0,0,".$tabAttaque['DegatsReçus']."
                    )";
                    $Result = $this->_bdd->query($req);
                }
                //update AttaquePersoMonster
                $req    = "UPDATE `AttaquePersoMonster` SET
                `nbCoup` = ".$tabAttaque['nbCoup'].",
                `coupFatal` = ".$coupFatal.",
                `DegatsReçus` = ".$tabAttaque['DegatsReçus']."
                WHERE idMonster = '".$this->getIdEntite()."' AND idPersonnage = '".$Entite->getIdEntite()."' ";
                $Result = $this->_bdd->query($req);
                usleep($CoolDown*1000);//microSeconde
            }
            return array ($this->_healthNow, $CoupCritique);
        }

        /** Return Historique d'ataque */
        public function getHistoriqueAttaque(){
            $req  = "SELECT * FROM `AttaquePersoMonster` WHERE idMonster = '".$this->_idEntite."'";
            $Result = $this->_bdd->query($req);
            while($tab=$Result->fetch()){
                array_push($this->HistoriqueAttaque,$tab);
            }
            return $this->HistoriqueAttaque;
        }

        /** Création d'un Monsters Aléatoire */
        public function CreateMonsterAleatoire($map){
                $newMonster = new Monster($this->_bdd);
                $typeMonster = $this->getTypeAleatoire();
                $lvlMap = $map->getLvlMap();
                $coefAbuseHealth = rand(20,50);
                $coefAbuseArme = rand(2,20);
                $healthNow = $coefAbuseHealth*$typeMonster[2]*$lvlMap;
                $degat = $coefAbuseArme*$typeMonster[2]*$lvlMap;
                // Menhir
                if($typeMonster[1]==0){
                    $healthNow = $coefAbuseHealth*20*$lvlMap;
                    $degat = 1*$lvlMap;
                }
                $newMonster = $newMonster->CreateEntite($this->generateName($typeMonster[0]), $healthNow, $degat, $map->getIdMap(),$healthNow,$typeMonster[3],null,0,$lvlMap);
                if(!is_null($newMonster)){
                    $req="INSERT INTO `Monster`(`idTypeMonster`, `idEntite` ,`coefXp` )
                    VALUES ('".$typeMonster[1]."','".$newMonster->getIdTypeEntite()."','".$typeMonster[2]."')";
                    $Result = $this->_bdd->query($req);
                    if($newMonster->getIdEntite()){
                        $newMonster->setEntiteById( $newMonster->getIdEntite());
                        return $newMonster;
                    }
                    else{
                        return null;
                    }
                }
                else{
                    return null;
                }
                $itemEnplus = new Item($this->_bdd);
                $nbItem = rand(2,$coefAbuseArme+round(($coefAbuseHealth/10)));
                for($i=0;$i<$nbItem;$i++){
                    $map->addItem($itemEnplus->createItemAleatoire());
                }
        }

        //retour un tableau
        //$tab[0]=$newTypeNom;
        //$tab[1]=$newType;
        //$tab[2]=$coef;
        //$tab[3]=image
        private function getTypeAleatoire(){
            $req="SELECT * FROM TypeMonster ORDER BY spawnTypeMonster ASC";
            $Result = $this->_bdd->query($req);
            $i = $Result->rowCount();
            $coef = 1;
            $imax = $i*3;
            $newType = 0;// Menhir par default
            $spawnTypeMonster = 1;
            $newTypeNom='Menhir';
            while($tab=$Result->fetch()){
                if(rand(0,$tab['spawnTypeMonster'])==1){
                    $newType = $tab['idTypeMonster'];
                    $newTypeNom = $tab['nameTypeMonster'];
                    $coef=$tab['spawnTypeMonster'];
                    break;
                }
            }
            $image = $this->generateImageMonster($newTypeNom);
            $tab[0]=$newTypeNom;
            $tab[1]=$newType;
            $tab[2]=$coef;
            $tab[3]=$image;
            return $tab;
        }

        /** Génére et Return un Nom en fonction du Type */
        public function GenerateName($type){
            $NameType = $type;
            $Adjectif = "";
            switch (rand(1,20)){
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
            switch (rand(0,201)){
                case 0:
                    $Nom = "Bracken";
                break;
                case 1:
                    $Nom = "Acorn";
                break;
                case 2:
                    $Nom = "Sotreg";
                break;
                case 3:
                    $Nom = "Urshug";
                break;
                case 4:
                    $Nom = "Moleskrith";
                break;
                case 5:
                    $Nom = "Niondikaix";
                break;
                case 6:
                    $Nom = "Sradurgrin";
                break;
                case 7:
                    $Nom = "Moleskrith";
                break;
                case 8:
                    $Nom = "Orshion";
                break;
                case 9:
                    $Nom = "Tagasko";
                break;
                case 10:
                    $Nom = "Totrei";
                break;
                case 11:
                    $Nom = "Trasalmoh";
                break;
                case 12:
                    $Nom = "Oronghaiz";
                break;
                case 13:
                    $Nom = "Trikto";
                break;
                case 14:
                    $Nom = "Panorus";
                break;
                case 15:
                    $Nom = "Konstian";
                break;
                case 16:
                    $Nom = "Peleon";
                break;
                case 17:
                    $Nom = "Melanthus";
                break;
                case 18:
                    $Nom = "Eusades";
                break;
                case 19:
                    $Nom = "Ajalus";
                break;
                case 20:
                    $Nom = "Shellos";
                break;
                case 21:
                    $Nom = "Gregzins";
                break;
                case 22:
                    $Nom = "Tits";
                break;
                case 23:
                    $Nom = "Yelko";
                break;
                case 24:
                    $Nom = "Uczaks";
                break;
                case 25:
                    $Nom = "Furghaohlach";
                break;
                case 26:
                    $Nom = "Tirdad";
                break;
                case 27:
                    $Nom = "Rar";
                break;
                case 28:
                    $Nom = "Cenghaild";
                break;
                case 29:
                    $Nom = "Patriarch";
                break;
                case 30:
                    $Nom = "Moraphine";
                break;
                case 31:
                    $Nom = "Verelle";
                break;
                case 32:
                    $Nom = "Yenyre";
                break;
                case 33:
                    $Nom = "Dysys";
                break;
                case 34:
                    $Nom = "Hyninis";
                break;
                case 35:
                    $Nom = "Cecoya";
                break;
                case 36:
                    $Nom = "Fecerna";
                break;
                case 37:
                    $Nom = "Hohecne";
                break;
                case 38:
                    $Nom = "Ephnide";
                break;
                case 39:
                    $Nom = "Ghurheco";
                break;
                case 40:
                    $Nom = "Gerirho";
                break;
                case 41:
                    $Nom = "Thucnaidh";
                break;
                case 42:
                    $Nom = "Brelforth";
                break;
                case 43:
                    $Nom = "Dravru";
                break;
                case 44:
                    $Nom = "Ceshope";
                break;
                case 45:
                    $Nom = "Rherunru";
                break;
                case 46:
                    $Nom = "Phunvipi";
                break;
                case 47:
                    $Nom = "Cylmik";
                break;
                case 48:
                    $Nom = "Melfie";
                break;
                case 49:
                    $Nom = "Ony";
                break;
                case 50:
                    $Nom = "Oscono";
                break;
                case 51:
                    $Nom = "Driolfur";
                break;
                case 52:
                    $Nom = "Zimnath";
                break;
                case 53:
                    $Nom = "Chocudro";
                break;
                case 54:
                    $Nom = "Bobiphe";
                break;
                case 55:
                    $Nom = "Eophorbia";
                break;
                case 56:
                    $Nom = "Lavendoris";
                break;
                case 57:
                    $Nom = "Poppiris";
                break;
                case 58:
                    $Nom = "Aconite";
                break;
                case 59:
                    $Nom = "Cinnamonia";
                break;
                case 60:
                    $Nom = "Viola";
                break;
                case 61:
                    $Nom = "Saffronis";
                break;
                case 62:
                    $Nom = "Dindellis";
                break;
                case 63:
                    $Nom = "Poinsetta";
                break;
                case 64:
                    $Nom = "Amaryllis";
                break;
                case 65:
                    $Nom = "Ehretia";
                break;
                case 66:
                    $Nom = "Pteili";
                break;
                case 67:
                    $Nom = "Poppiris";
                break;
                case 68:
                    $Nom = "Hellobora";
                break;
                case 69:
                    $Nom = "Sabatia";
                break;
                case 70:
                    $Nom = "Azolla";
                break;
                case 71:
                    $Nom = "Ianisse";
                break;
                case 72:
                    $Nom = "Oinone";
                break;
                case 73:
                    $Nom = "Hamo";
                break;
                case 74:
                    $Nom = "Rand";
                break;
                case 75:
                    $Nom = "Raiimond";
                break;
                case 76:
                    $Nom = "Eloise";
                break;
                case 77:
                    $Nom = "Maneld";
                break;
                case 78:
                    $Nom = "Cristina";
                break;
                case 79:
                    $Nom = "Elurelia";
                break;
                case 80:
                    $Nom = "Dialina";
                break;
                case 81:
                    $Nom = "Narilla";
                break;
                case 82:
                    $Nom = "Eathemala";
                break;
                case 83:
                    $Nom = "Oralina";
                break;
                case 84:
                    $Nom = "Kallipheme";
                break;
                case 85:
                    $Nom = "Elurelia";
                break;
                case 86:
                    $Nom = "Nahfa";
                break;
                case 87:
                    $Nom = "Lagurinda";
                break;
                case 88:
                    $Nom = "Aethella";
                break;
                case 89:
                    $Nom = "Perinos";
                break;
                case 90:
                    $Nom = "Thataruh";
                break;
                case 91:
                    $Nom = "Abrao";
                break;
                case 92:
                    $Nom = "Tallan";
                break;
                case 93:
                    $Nom = "Efarol";
                break;
                case 94:
                    $Nom = "Yalluh";
                break;
                case 95:
                    $Nom = "Idlestriker";
                break;
                case 96:
                    $Nom = "Mimnu";
                break;
                case 97:
                    $Nom = "Odri";
                break;
                case 98:
                    $Nom = "Osruu";
                break;
                case 99:
                    $Nom = "Eelliya";
                break;
                case 100:
                    $Nom = "Connar";
                break;
                case 101:
                    $Nom = "Iwlos";
                break;
                case 102:
                    $Nom = "Crixog";
                break;
                case 103:
                    $Nom = "Slolos";
                break;
                case 104:
                    $Nom = "Ausbos";
                break;
                case 105:
                    $Nom = "Vreslith";
                break;
                case 106:
                    $Nom = "Hewmalog";
                break;
                case 107:
                    $Nom = "Xuog";
                break;
                case 108:
                    $Nom = "Heom";
                break;
                case 109:
                    $Nom = "Kutheus";
                break;
                case 110:
                    $Nom = "Naroch";
                break;
                case 111:
                    $Nom = "Tafag";
                break;
                case 112:
                    $Nom = "Aodlor";
                break;
                case 113:
                    $Nom = "Flukkaros";
                break;
                case 114:
                    $Nom = "Kethos";
                break;
                case 115:
                    $Nom = "Crowgar";
                break;
                case 116:
                    $Nom = "Cunas";
                break;
                case 117:
                    $Nom = "Dlasfur";
                break;
                case 118:
                    $Nom = "Onus";
                break;
                case 119:
                    $Nom = "Nugdhor";
                break;
                case 120:
                    $Nom = "Wiwrog";
                break;
                case 121:
                    $Nom = "Cabtheus";
                break;
                case 122:
                    $Nom = "Judroch";
                break;
                case 123:
                    $Nom = "Wruxgrog";
                break;
                case 124:
                    $Nom = "Lugfur";
                break;
                case 125:
                    $Nom = "Klizbor";
                break;
                case 126:
                    $Nom = "Nimlas";
                break;
                case 127:
                    $Nom = "Caglith";
                break;
                case 128:
                    $Nom = "Fecrus";
                break;
                case 129:
                    $Nom = "Fetlog";
                break;
                case 130:
                    $Nom = "Joroch";
                break;
                case 131:
                    $Nom = "Lilsius";
                break;
                case 132:
                    $Nom = "Minfius";
                break;
                case 133:
                    $Nom = "Frarmalog";
                break;
                case 134:
                    $Nom = "Crubgrog";
                break;
                case 135:
                    $Nom = "Dodlor";
                break;
                case 136:
                    $Nom = "Nogir";
                break;
                case 137:
                    $Nom = "Nufgan";
                break;
                case 138:
                    $Nom = "Niom";
                break;
                case 139:
                    $Nom = "Kolzus";
                break;
                case 140:
                    $Nom = "Aretius";
                break;
                case 141:
                    $Nom = "Cretder";
                break;
                case 142:
                    $Nom = "Jadnus";
                break;
                case 143:
                    $Nom = "Cogfius";
                break;
                case 144:
                    $Nom = "Kewnas";
                break;
                case 145:
                    $Nom = "Falthos";
                break;
                case 146:
                    $Nom = "Werus";
                break;
                case 147:
                    $Nom = "Zugan";
                break;
                case 148:
                    $Nom = "Habdhor";
                break;
                case 149:
                    $Nom = "Jabtheus";
                break;
                case 150:
                    $Nom = "Ocmohr";
                break;
                case 151:
                    $Nom = "Grinus";
                break;
                case 152:
                    $Nom = "Cocvag";
                break;
                case 153:
                    $Nom = "Alover";
                break;
                case 154:
                    $Nom = "Fremlas";
                break;
                case 155:
                    $Nom = "Slumsar";
                break;
                case 156:
                    $Nom = "Moxzar";
                break;
                case 157:
                    $Nom = "Lonwar";
                break;
                case 158:
                    $Nom = "Bokroch";
                break;
                case 159:
                    $Nom = "Flaxdor";
                break;
                case 160:
                    $Nom = "Famlas";
                break;
                case 161:
                    $Nom = "Srunus";
                break;
                case 162:
                    $Nom = "Mabar";
                break;
                case 163:
                    $Nom = "Doksag";
                break;
                case 164:
                    $Nom = "Wilrion";
                break;
                case 165:
                    $Nom = "Wesog";
                break;
                case 166:
                    $Nom = "Fesius";
                break;
                case 167:
                    $Nom = "Rokos";
                break;
                case 168:
                    $Nom = "Zloos";
                break;
                case 169:
                    $Nom = "Elith";
                break;
                case 170:
                    $Nom = "Cemir";
                break;
                case 171:
                    $Nom = "Dremdus";
                break;
                case 172:
                    $Nom = "Uas";
                break;
                case 173:
                    $Nom = "Vokaros";
                break;
                case 174:
                    $Nom = "Denus";
                break;
                case 175:
                    $Nom = "Glewor";
                break;
                case 176:
                    $Nom = "Codius";
                break;
                case 177:
                    $Nom = "Nebfur";
                break;
                case 178:
                    $Nom = "Wream";
                break;
                case 179:
                    $Nom = "Gengar";
                break;
                case 180:
                    $Nom = "Aksog";
                break;
                case 181:
                    $Nom = "Stykt";
                break;
                case 182:
                    $Nom = "Zes";
                break;
                case 183:
                    $Nom = "Bix";
                break;
                case 184:
                    $Nom = "Wrox";
                break;
                case 185:
                    $Nom = "Frots";
                break;
                case 186:
                    $Nom = "Bliazgeeg";
                break;
                case 187:
                    $Nom = "Zriahzird";
                break;
                case 188:
                    $Nom = "Slunis";
                break;
                case 189:
                    $Nom = "Sloitvulk";
                break;
                case 190:
                    $Nom = "Jersyng";
                break;
                case 191:
                    $Nom = "Swiessee";
                break;
                case 192:
                    $Nom = "Niegsia";
                break;
                case 193:
                    $Nom = "Wurx";
                break;
                case 194:
                    $Nom = "Arx";
                break;
                case 195:
                    $Nom = "Treesai";
                break;
                case 196:
                    $Nom = "Creekith";
                break;
                case 197:
                    $Nom = "Duvisia";
                break;
                case 198:
                    $Nom = "Gleahkia";
                break;
                case 199:
                    $Nom = "Gionvylma";
                break;
                case 200:
                    $Nom = "Doggo";
                break;
                default:
                    $Nom = "Asteus";
            }
            return $NameType." ".$Adjectif." ".$Nom;
        }

        /** Génére et Return un lien d'image en fonction du Type */
        public function generateImageMonster($topic){
            //echo '<img src="'.$partialString3.'" widht="200px">';
            if(empty($topic)){
                $topic='creature';
            }
            $ofs=mt_rand(0, 100);
            $geturl='https://www.bing.com/images/search?q='.$topic.'&first='.$ofs.'&tsc=ImageHoverTitle';
            $data=file_get_contents($geturl);
            //partialString1 is bigger link.. in it will be a scr for the beginning of the url
            $f1='<div class="img_cont hoff">';
            $pos1=strpos($data, $f1)+strlen($f1);
            $partialString1 = substr($data, $pos1);
            $f1bis='src="';
            $pos1=strpos($partialString1, $f1bis)+strlen($f1bis);
            $partialString1 = substr($partialString1, $pos1);
            //PartialString3 ends the url when it sees the "&amp;"
            $f3='"';
            $urlLength=strpos($partialString1, $f3);
            $partialString3 = substr($partialString1, 0, $urlLength);
            return $partialString3;
        }

        /** Reset HealthNow de Monster by ID */
        public function healMonsterspawn($idEntite){
            $this->_bdd->query("UPDATE `Entite` SET `healthNow` = '".$this->healthMax."' WHERE `idEntite` = $idEntite");
        }

        /** Affiche le rendu HTML du Monster */
        public function displayHTML(){
            $Pourcentage = round(100*$this->_healthNow/$this->_healthMax); // Remettre en place le % de vie visible via le style
            ?>
                <div class="Monster">
                    <div class="EntiteInfo">
                        <div class="EntiteName">
                            <p><?= $this->getNameEntite() ?></p>
                        </div>
                    </div>
                    <div class="divimgEntite">
                        <img class="imgEntite" src="<?= $this->_imgEntite ?>">
                    </div>
                    <div class="valueEntite">
                        <div class="backgroundAttaque" id="attaqueEntiteValeur<?= $this->_idEntite ?>">
                            <img class="imgAttaque" src="./css/epee.cur"/><p><?= $this->getAttaque() ?></p>
                        </div>
                        <div class="backgroundArmor" id="defenseEntiteValeur<?= $this->_idEntite ?>">
                            <img class="imgArmor" src="./assets/image/armor.png"/><p><?= $this->getDefense() ?></p>
                        </div>
                    </div>
                    <div class="healthBar" id="healthEntite<?= $this->_idEntite ?>">
                        <div class="healthNow" id="healthEntiteValeur<?= $this->_idEntite ?>">
                            <p>♥️ <?= $this->_healthNow ?> / <?= $this->_healthMax ?></p>
                        </div>
                    </div>
                </div>
            <?php
        }
    }
?>
