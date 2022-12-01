<?php
    session_start();
    include "../session.php";
    $reponse[0] = 0;// Id Entite
    $reponse[1] = 0;// Id Type Equipement
    $reponse[2] = 0;// Id Equipement
    $reponse[3] = 0;// Name Equipement
    $reponse[4] = 0;// Stats Attaque
    $reponse[5] = 0;// Coef Defense
    $reponse[6] = 0;// HealthNow
    $reponse[7] = 0;// HealthMax
    $message = '';// Message
    $reponse[9] = 0;// Img Equipement
    if($access){
        if(isset($_GET["idEquipement"])){
            $Perso = $Joueur1->getPersonnage();
            if($Perso->getHealthNow() <= 0){
                $Perso->resurection();
                $message = "Ce personnage est mort. ";
            }
            foreach($Perso->getEquipements() as $equipement){// Optimisable
                if($_GET["idEquipement"] == $equipement->getIdEquipement()){
                    switch($equipement->getIdCategorie()) {
                        case 1:// Arme
                            if(!is_null($Perso->getArme())){
                                $reponse[2] = $_GET["idEquipement"];
                                $reponse[3] = $equipement->getNameEquipement().' LV'.$equipement->getLvlEquipement();
                                $reponse[9] = $equipement->getImgEquipement();
                                $equipement->desequipeEntite($Perso);
                                $message.= 'retire de '.$equipement->getNameEquipement();
                                $reponse[1] = 1;
                            }
                            else{
                                $message.= 'vous n\'avez pas bien reussi à retirer '.$equipement->getNameEquipement();
                                $reponse[1] = 0;
                            }
                            break;
                        case 2:// Armure
                            if(!is_null($Perso->getArmure())){
                                $reponse[2] = $_GET["idEquipement"];
                                $reponse[3] = $equipement->getNameEquipement().' LV'.$equipement->getLvlEquipement();
                                $reponse[9] = $equipement->getImgEquipement();
                                $equipement->desequipeEntite($Perso);
                                $message.= 'retire de '.$equipement->getNameEquipement();
                                $reponse[1] = 2;
                            }
                            else{
                                $message.= 'vous n\'avez pas bien reussi à retirer '.$equipement->getNameEquipement();
                                $reponse[1] = 0;
                            }
                            break;
                        default:
                            break;
                    }
                    $reponse[0] = $Perso->getIdEntite();
                    $reponse[4] = $Perso->getAttaque();
                    $reponse[5] = $Perso->getDefense();
                    $reponse[6] = $Perso->getHealthNow();
                    $reponse[7] = $Perso->getHealthMax();
                    $reponse[8] = $message;
                    break;
                }
            }
        }
    }
    echo json_encode($reponse);
?>