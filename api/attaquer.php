<?php
    //cette api doit etre lancé pour attaquer un id
    //cette API retourne un tableau avec idDuPersoattaque, sa Health restant et sa Health de base
    // cette api retour un tableau avec 0 si elle n'a pas eccecuter le code attendu
    //une API ne dois sortir qu'un seul Echo celui de la reponse !!!!
    session_start();
    include "../session.php"; 
    $reponse[0]=0;
    if($access){
        if(isset($_GET["idEntite"])){
            $Personnage = $Joueur1->getPersonnage();
            $message="";
            $healthPersonnage = $Personnage->getHealthNow();
            $healthMaxPersonnage = $Personnage->getHealthMax();
            $EntiteCible = new Entite($mabase);
            $EntiteCible->setEntiteById($_GET["idEntite"]);
            $healthMaxCible=$EntiteCible->getHealthMax();
            $healthNowCible=$EntiteCible->getHealthNow();
            if($EntiteCible->getIdUser() == 0){
                $idTypeEntite = 0;
            }
            else{
                $idTypeEntite = 1;
            }
            // Vérification que Personnage n'est pas EntiteCible ou le même User.
            if(($EntiteCible->getIdEntite() !== $Personnage->getIdEntite()) && ($EntiteCible->getIdUser() !== $Personnage->getIdUser())){
                // Vérification Personnage & EntiteCible sur la même map
                if($EntiteCible->getMap() == $Personnage->getMap()){
                    // vérification que Map PVP
                    if($Personnage->getMap()->getPvP()){
                        // Attaque sur perso
                        if($idTypeEntite == 1){
                            $CiblePersonnage = new Personnage($mabase);
                            $CiblePersonnage->setPersonnageByIdWithoutMap($_GET["idEntite"]);
                            $healthMaxCible=$CiblePersonnage->getHealthMax();
                            $healthNowCible=$CiblePersonnage->getHealthNow();
                            //on verrifie que le perso n'est pas mort
                            if($CiblePersonnage->getHealthNow()>0){
                                if($healthPersonnage!=0){
                                    $healthNowCible = $CiblePersonnage->SubitDegatByPersonnage($Personnage->getAttaque());
                                    $healthMaxCible = $CiblePersonnage->getHealthMax();
                                    $CiblePersonnage->addXP(1);
                                    //on va retirer le coup d'attaque de base de Cible
                                    //car une attaque n'est pas gratuite
                                    $healthAvant = $Personnage->getHealthNow();
                                    $healthPersonnage=$Personnage->SubitDegatByPersonnage($CiblePersonnage->getAttaque());
                                    $perte = $healthAvant-$healthPersonnage;
                                    $message .= "Vous avez subit ".$perte." pts de degat. ";
                                    $Personnage->addXP(2); // À dégager
                                    if($healthPersonnage==0){
                                        $message .= "Votre personnage est mort. ";
                                    }
                                    if($healthNowCible==0){
                                        $lvlEntite = $CiblePersonnage->getLvlEntite();
                                        $Personnage->addXP($lvlEntite*rand(8,10));
                                        $message .= "Vous avez tué ".$CiblePersonnage->getNameEntite().". ";
                                    }
                                }
                                else{
                                    $message .= "Tu es déjà mort, tu ne peux plus attaquer. ";
                                }
                            }
                            else{
                                $message .= "Ce personnage est déjà mort. ";
                            }
                        }
                        // Attaque sur Monster
                        if($idTypeEntite == 0){
                            $CibleMonster = new Monster($mabase);
                            $CibleMonster->setMonsterByIdWithMap($_GET["idEntite"]);
                            $healthMaxCible=$CibleMonster->getHealthMax();
                            $healthNowCible=$CibleMonster->getHealthNow();
                            if($CibleMonster->getHealthNow()>0){
                                if($healthPersonnage!=0){
                                    //Utilisation méthode pour attaquer le Monster
                                    $SubitDegat = $CibleMonster->SubitDegat($Personnage);
                                    //healthNow du Monster renvoyer après avoir subit l attaque du joueur
                                    $healthNowCible = $SubitDegat[0];
                                    $healthMaxCible = $CibleMonster->getHealthMax();
                                    //Si le Monster as de la healthNow, il attaque. Sinon, rien ne se passe
                                    if($healthNowCible>0 && $SubitDegat[1]!='coolDown'){
                                        $healthAvant = $Personnage->getHealthNow();
                                        //Sinon : retour de bâton le deffenseur aussi attaque
                                        $healthPersonnage=$Personnage->SubitDegatByMonster($CibleMonster);
                                        $perte = $healthAvant-$healthPersonnage;
                                        $message .= "Vous avez subit ".$perte." pts de degat. ";
                                        $Personnage->addXP(2); // À dégager
                                    }
                                    //Affichage d'un message avec les dégats ingligé + info de si c'est un cout critique
                                    //Si vous voulez retirer le popup, c'est ici; Gros Chien.
                                    $message .= $SubitDegat[1];
                                    if($healthPersonnage==0){
                                        $message .= " Votre personnage est mort. ";
                                    }
                                    //si le perso tue le Monster il faut envoyer un message
                                    if($healthNowCible<=0){
                                        $lvlEntite = $CibleMonster->getLvlEntite();
                                        $Personnage->addXP($lvlEntite*rand(8,10)*$CibleMonster->getCoefXp());
                                        $message .= "Tu as participé à la capture de ce monstre. ";
                                    }
                                }
                                else{
                                    $message .= "Tu es déjà mort, tu ne peux plus attaquer. ";
                                }
                            }
                            else{
                                $message .= "Ce monstre est déjà capturé. ";
                            }
                        }
                    }
                    else{
                        $message .= "Les combats entre joueurs ne sont pas autorisé ici. ";
                    }
                }
                else{
                    $message .= "Le personnage n'est pas sur la même map que l'ennemie. ";
                }
            }
            else{
                $message .= "Vous ne pouvez pas vous attaquer vous même. ";
            }
            $reponse[0] = $_GET["idEntite"];
            $reponse[1] = $healthNowCible;
            $reponse[2] = $healthMaxCible;
            $reponse[3] = $healthPersonnage;
            $reponse[4] = $healthMaxPersonnage;
            $reponse[5] = $Personnage->getIdEntite();
            $reponse[6] = $message;
            $reponse[7] = $Personnage->getExpPersonnage();
            $reponse[8] = $Personnage->getDefense();
            $reponse[9] = $idTypeEntite;
        }
    }
    echo json_encode($reponse);
?>