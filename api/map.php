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
    $reponse["this"] = $Personnage->getMap()->getId();
    $reponse["Nord"] = $Personnage->getMap()->getMapNord()->getId();
    $reponse["Sud"] = $Personnage->getMap()->getMapSud()->getId();
    $reponse["Est"] = $Personnage->getMap()->getMapEst()->getId();
    $reponse["Ouest"] = $Personnage->getMap()->getMapOuest()->getId();

    //renvoi l'ensemble de tableau dans un tableau.

    echo json_encode($reponse);
}
