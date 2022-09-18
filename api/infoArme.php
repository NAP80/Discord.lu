<?php
    // Envoie d'info sur l'arme équipée notamment type et cooldown 
    session_start();
    include '../session.php';
    if($access){
        // Création du tableau de réponse
        $reponse = array();
        $Personnage = $Joueur1->getPersonnage();
        //Recupération du type d'arme
        if($tab = $Personnage->getArme()){
            $tab = $tab->getTypeEquipement();
            $reponse["arme"] = $tab["nameEquipement"];
            //Recupération du cooldown de l'arme
            $reponse["cooldown"] = $tab["Cooldown"];
            //Envoie du tableau de réponse
            echo json_encode($reponse);
        }
    }
?>
