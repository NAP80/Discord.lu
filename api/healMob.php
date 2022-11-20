<?php
    // API pour soigner un Creature capturé
    session_start();
    include '../map.php';
    if($access){
        //Recupère l'id du Créature a soigné depuis l'url
        $idEntite = $_GET['idEntite'];
        //Créer un Creature et lui donne comme id celui du Créature a charger
        $cible = New Creature($mabase);
        $cible->setCreatureById($idEntite);
        //Soigne le Creature
        $cible->healCreaturespawn($idEntite);
        //Recupère la healthNow et la healthMax du Créature
        $healthNow = $cible->getHealthNow();
        $healthMax = $cible->getHealthNow();
        //Si la healthNow est égale à la healthMax max on return true sinon false
        if($healthNow == $healthMax){
            $reponse = true;
        }
        else{
            $reponse = false;
        }
        //Renvoie le resultats
        echo json_encode($reponse);
    }
?>