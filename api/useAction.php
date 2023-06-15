<?php
    session_start();
    include "../session.php";
    $reponse[0] = 0;// Id Action
    $reponse[1] = 0;// Refusé = 0 / Validé = 1
    $reponse[9] = "Action refusée.";// Message Texte
    if($access){
        if(isset($_GET["idAction"])){
            $Perso = $Joueur1->getPersonnage();
            if($Perso->getHealthNow() <= 0){
                $Perso->resurection();
                $message = "Ce personnage est mort. ";
            }
            else{
                $reponse[0] = substr($_GET["idAction"], 0,3);
                switch($reponse[0]){
                    case "B00": // Sanction 0
                        if($Joueur1->getPermPlay() == 0){
                            $reponse[1] = 1;
                            $reponse[9] = "";
                        }
                        break;
                    case "B01": // Sanction 1
                        if($Joueur1->getPermPlay() == 0){
                            $reponse[1] = 1;
                            $reponse[9] = "";
                        }
                        break;
                    case "J00": // Fouiller
                        if($Joueur1->getPermPlay() == 1){
                            $reponse[1] = 1;
                            $reponse[9] = "Zone fouillé.";
                        }
                        break;
                    case "J01": // Se Cacher/Sortir
                        if($Joueur1->getPermPlay() == 1){
                            $reponse[1] = 1;
                            $reponse[9] = "Cacher/Sortie.";
                        }
                        break;
                    case "J02": // Fuir
                        if($Joueur1->getPermPlay() == 1){
                            $reponse[1] = 1;
                            $reponse[9] = "Vous fuyez.";
                        }
                        break;
                    case "J03": // Se Téléporter Home
                        if($Joueur1->getPermPlay() == 1){
                            $reponse[1] = 1;
                            $reponse[9] = "Vous rentrez à votre maison.";
                        }
                        break;
                    case "T00": // Régénérer Vie
                        if($Joueur1->getPermBypass()){
                            $reponse[1] = 1;
                            $reponse[9] = "Vie Regen";
                        }
                        break;
                    case "T01": // Régénérer Déplacement
                        if($Joueur1->getPermBypass()){
                            $reponse[1] = 1;
                            $reponse[9] = "Deplacement Regen";
                        }
                        break;
                    case "S00": // Staff 0
                        if($Joueur1->getPermStaff()){
                            $reponse[1] = 1;
                            $reponse[9] = "Staff 1";
                        }
                        break;
                    case "S01": // Staff 1
                        if($Joueur1->getPermStaff()){
                            $reponse[1] = 1;
                            $reponse[9] = "Staff 2";
                        }
                        break;
                    case "A00": // Tuer tout les joueurs
                        if($Joueur1->getPermAdmin()){
                            $reponse[1] = 1;
                            $reponse[9] = "Joueurs Tuées";
                        }
                        break;
                    case "A01": // Tuer tout les monstres
                        if($Joueur1->getPermAdmin()){
                            $reponse[1] = 1;
                            $reponse[9] = "Monstres Tuées";
                        }
                        break;
                    case "A02": // Détruire Objet au sol
                        if($Joueur1->getPermAdmin()){
                            $reponse[1] = 1;
                            $reponse[9] = "Zone netoyé";
                        }
                        break;
                    case "A03": // Se cacher (Admin)
                        if($Joueur1->getPermAdmin()){
                            $reponse[1] = 1;
                            $reponse[9] = "Invisible Admin";
                        }
                        break;
                    case "A04": // Sortir Cachette (Admin)
                        if($Joueur1->getPermAdmin()){
                            $reponse[1] = 1;
                            $reponse[9] = "Visible Admin";
                        }
                        break;
                    case "A05": // Générer Objet
                        if($Joueur1->getPermAdmin()){
                            $reponse[1] = 1;
                            $reponse[9] = "Objet généré";
                        }
                        break;
                    case "A06": // Générer Monstre
                        if($Joueur1->getPermAdmin()){
                            $reponse[1] = 1;
                            $reponse[9] = "Monstre Généré";
                        }
                        break;
                    case "A07": // Se Téléporter
                        if($Joueur1->getPermAdmin()){
                            $reponse[1] = 1;
                            $reponse[9] = "Téléportation Faite";
                        }
                        break;
                    default:
                        break;
                }
            }
        }
    }
    echo json_encode($reponse);
?>