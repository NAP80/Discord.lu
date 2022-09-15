<?php
    // API pour soigner un Monster capturé
    session_start();
    include '../map.php';
    if($access){
        //Recupère l'id du monstre a soigné depuis l'url
        $id = $_GET['id'];
        //Créer un Monster et lui donne comme id celui du monstre a charger
        $cible = New Monster($mabase);
        $cible->setMonsterById($id);
        //Soigne le Monster
        $cible->healMonsterspawn($id);
        //Recupère la vie et la vie max du monstre
        $vieActuel = $cible->getVie();
        $vieMax = $cible->getVieMax();
        //Si la vie est égale à la vie max on return true sinon false
        if($vieActuel == $vieMax){
            $reponse = true;
        }
        else{
            $reponse = false;
        }
        //Renvoie le resultats
        echo json_encode($reponse);
    }
?>