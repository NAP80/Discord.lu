<?php
    // envoie d'info sur la map actuelle et les maps environnantes pour actualiser avec le slide téléphone
    session_start();
    include '../session.php';
    if($access){
        $reponse = array();
        //Retourne l'objet de la map et des maps environnantes,
        //Retourne l'objet de la map si la map est découverte,
        //Retourne null(moi j'ai rien) dans le cas contraire.
        $Personnage = $Joueur1->getPersonnage();
        //Pour acceder au propriétés remettre les accesseurs après les getMap.
        $reponse["this"] = $Personnage->getMap()->getIdMap();
        $reponse["Nord"] = $Personnage->getMap()->getMapNord()->getIdMap();
        $reponse["Sud"] = $Personnage->getMap()->getMapSud()->getIdMap();
        $reponse["Est"] = $Personnage->getMap()->getMapEst()->getIdMap();
        $reponse["Ouest"] = $Personnage->getMap()->getMapOuest()->getIdMap();
        //renvoi l'ensemble de tableau dans un tableau.
        echo json_encode($reponse);
    }
?>