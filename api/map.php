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
        $reponse["this"] = $Personnage->getMapEntite()->getIdMap();
        $reponse["Nord"] = $Personnage->getMapEntite()->getMapNord()->getIdMap();
        $reponse["Sud"] = $Personnage->getMapEntite()->getMapSud()->getIdMap();
        $reponse["Est"] = $Personnage->getMapEntite()->getMapEst()->getIdMap();
        $reponse["Ouest"] = $Personnage->getgetMapEntiteMap()->getMapOuest()->getIdMap();
        //renvoi l'ensemble de tableau dans un tableau.
        echo json_encode($reponse);
    }
?>