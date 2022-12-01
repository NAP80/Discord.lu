<?php
    session_start();
    include "../session.php";
    $reponse[0] = 0;// Id Entite
    $reponse[1] = 0;// Id Type Equipement
    $reponse[2] = 0;// Name Equipement
    $reponse[3] = 0;// Id Equipement
    $reponse[4] = 0;// Name Equipement
    $reponse[5] = 0;// Stats Attaque
    $reponse[6] = 0;// Coef Defense
    $reponse[7] = 0;// HealthNow
    $reponse[8] = 0;// HealthMax
    $message = '';// Message
    $reponse[10] = '';// Img Equipement
    if($access){
        if(isset($_GET["idEquipement"])){
            $Perso = $Joueur1->getPersonnage();
            if($Perso->getHealthNow() <= 0){
                $Perso->resurection();
                $message = "Ce personnage est mort. ";
            }
            foreach($Perso->getEquipements() as $equipement){// Optimisable
                if($_GET["idEquipement"] == $equipement->getIdEquipement()){
                    switch($equipement->getIdCategorie()){
                        case 1:// Arme
                            if(!is_null($Perso->getArme())){
                                $reponse[3] = $Perso->getArme()->getIdEquipement();
                                $reponse[4] = $Perso->getArme()->getNameEquipement();
                                $reponse[10] = $Perso->getArme()->getImgEquipement();
                                $Perso->desequipeArme();
                            }
                            $equipement->equipeEntite($Perso);
                            $reponse[2] = $equipement->getNameEquipement() ." LV ". $equipement->getLvlEquipement();
                            $message.= 's\'équipe de '.$equipement->getNameEquipement();
                            $reponse[1] = 1;
                            break;
                        case 2:// Armure
                            if(!is_null($Perso->getArmure())){
                                $reponse[3] = $Perso->getArmure()->getIdEquipement();
                                $reponse[4] = $Perso->getArmure()->getNameEquipement();
                                $reponse[10] = $Perso->getArmure()->getImgEquipement();
                                $Perso->desequipeArmure();
                            }
                            $equipement->equipeEntite($Perso);
                            $reponse[2] = $equipement->getNameEquipement() ." LV ". $equipement->getLvlEquipement();
                            $message.= 's\'équipe de '.$equipement->getNameEquipement();
                            $reponse[1] = 2;
                            break;
                        default:
                            $Perso->removeEquipementById($equipement->getIdEquipement());
                            $val = $equipement->getValeur();
                            $attaque = round($val/10);
                            $healthmore = round($val/5);
                            $message = $Perso->getNameEntite()." a utilisé un équipement pour booster ses stats.";
                            $Perso->lvlupAttaque($attaque);
                            $Perso->lvlupHealthNow($healthmore);
                            $Perso->lvlupHealthMax($healthmore);
                            break;
                    }
                    $reponse[0] = $Perso->getIdEntite();
                    $reponse[5] = $Perso->getAttaque();
                    $reponse[6] = $Perso->getDefense();
                    $reponse[7] = $Perso->getHealthNow();
                    $reponse[8] = $Perso->getHealthMax();
                    $reponse[9] = $message;
                    break;
                }
            }
        }
    }
    echo json_encode($reponse);
?>