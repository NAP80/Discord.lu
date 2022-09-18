<?php
    // API pour soigner un Monster capturé
    session_start();
    include '../map.php';
    if($access){
        //Recupère l'id du monstre a soigné depuis l'url
        $idEntite = $_GET['idEntite'];
        //Créer un Monster et lui donne comme id celui du monstre a charger
        $cible = New Monster($mabase);
        $cible->setMonsterById($idEntite);
        //Soigne le Monster
        $cible->healMonsterspawn($idEntite);
        //Recupère la healthNow et la healthMax du monstre
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