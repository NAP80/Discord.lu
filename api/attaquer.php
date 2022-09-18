<?php
    //cette api doit etre lancé pour attaquer un id
    //cette API retourne un tableau avec idDuPersoattaque, sa Health restant et sa Health de base
    // cette api retour un tableau avec 0 si elle n'a pas eccecuter le code attendu
    //une API ne dois sortir qu'un seul Echo celui de la reponse !!!!
    session_start();
    include "../session.php"; 
    $reponse[0]=0;
    if($access){
        if(isset($_GET["idEntite"]) && isset($_GET["idTypeEntite"])){
            //on récupere la force du perso en cours
            //un joueur est un USER
            $Attaquant = $Joueur1->getPersonnage();
            $Attaquant->addXP(2);
            $message="";
            $healthMax=0;
            $healthNow=0;
            $healthAttaquant=$Attaquant->getHealthNow();
            $healthMaxAttaquant=$Attaquant->getHealthMax();
            //attaque sur perso
            if($_GET["idTypeEntite"] == 1 ){
                $Deffensseur = new Personnage($mabase);
                $Deffensseur->setPersonnageByIdWithoutMap($_GET["idEntite"]);
                $healthMax=$Deffensseur->getHealthMax();
                $healthNow=$Deffensseur->getHealthNow();
                //on verrifie que le perso n'est pas mort
                if($Deffensseur->getHealthNow()>0){
                    if($healthAttaquant!=0){
                        $healthNow = $Deffensseur->SubitDegatByPersonnage($Attaquant->getAttaque());
                        $healthMax = $Deffensseur->getHealthMax();
                        $Deffensseur->addXP(1);
                        //on va retirer le coup d'attaque de base du deffensseur
                        //car une attaque n'est pas gratuite
                        $healthAvant = $Attaquant->getHealthNow();
                        $healthAttaquant=$Attaquant->SubitDegatByPersonnage($Deffensseur->getAttaque());
                        $perte = $healthAvant-$healthAttaquant;
                        $message .= "vous avez subit ".$perte." pts de degat ";
                        if($healthAttaquant==0){
                            $message .= " Ton personnage est mort.";
                        }
                        if($healthNow==0){
                            $lvlEntite = $Deffensseur->getLvlEntite();
                            $Attaquant->addXP($lvlEntite*rand(8,10));
                            $message .= " Tu as tué ".$Deffensseur->getNameEntite();
                        }
                    }
                    else{
                        $message .= " Tu es déjà mort, tu ne peux plus attaquer.";
                    }
                }
                else{
                    $message .= " Ce personnage est déjà mort.";
                }
            }
            //attaque sur Monster
            if($_GET["idTypeEntite"] == 0){
                $DeffensseurMonster = new Monster($mabase);
                $DeffensseurMonster->setMonsterByIdWithMap($_GET["idEntite"]);
                $healthMax=$DeffensseurMonster->getHealthMax();
                $healthNow=$DeffensseurMonster->getHealthNow();
                if($DeffensseurMonster->getHealthNow()>0){
                    if($healthAttaquant!=0){
                        //Utilisation méthode pour attaquer le Monster
                        $SubitDegat = $DeffensseurMonster->SubitDegat($Attaquant);
                        //healthNow du Monster renvoyer après avoir subit l attaque du joueur
                        $healthNow = $SubitDegat[0];
                        $healthMax = $DeffensseurMonster->getHealthMax();
                        //Si le Monster as de la healthNow, il attaque. Sinon, rien ne se passe
                        if($healthNow>0 && $SubitDegat[1]!='coolDown'){
                            $healthAvant = $Attaquant->getHealthNow();
                            //Sinon : retour de bâton le deffenseur aussi attaque
                            $healthAttaquant=$Attaquant->SubitDegatByMonster($DeffensseurMonster);
                            $perte = $healthAvant-$healthAttaquant;
                            $message .= "vous avez subit ".$perte." pts de degat ";
                        }
                        //Affichage d'un message avec les dégats ingligé + info de si c'est un cout critique
                        //Si vous voulez retirer le popup, c'est ici; Gros Chien.
                        $message .= $SubitDegat[1];
                        if($healthAttaquant==0){
                            $message .= "Ton personnage est mort.";
                        }
                        //si le perso tu le Monster il faut envoyer un message
                        if($healthNow<=0){
                            $lvlEntite = $DeffensseurMonster->getLvlEntite();
                            $Attaquant->addXP($lvlEntite*rand(8,10)*$DeffensseurMonster->getCoefXp());
                            $message .= "Tu as participé à la capture de ce monstre.";
                        }
                    }
                    else{
                        $message .= "Tu es déjà mort, tu ne peux plus attaquer.";
                    }
                }
                else{
                    $message .= "Ce monstre est déjà capturé.";
                }
            }
            $reponse[0]=$_GET["idEntite"];
            $reponse[1]=$healthNow;
            $reponse[2]=$healthMax;
            $reponse[3]=$healthAttaquant;
            $reponse[4]=$healthMaxAttaquant;
            $reponse[5]=$Attaquant->getIdEntite();
            $reponse[6]=$message;
            $reponse[7]=$Attaquant->getExpPersonnage();
            $reponse[8]=$Attaquant->getDefense();
        }
    }
    echo json_encode($reponse);
?>