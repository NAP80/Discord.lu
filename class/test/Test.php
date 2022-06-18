<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/map.css">
    <link rel="stylesheet" href="../../css/perso.css">
    <link rel="stylesheet" href="../../css/item.css">
    <link rel="stylesheet" href="../../css/entite.css">
    <link rel="stylesheet" href="../../css/test.css">
    <script src="main.js"></script>
    <title>Document</title>
</head>
<body class="bodyAccueil">
    <?php
    //c'est dans fonction que l'on gère les formulaires de Co et les sessions

    include "../../session.php"; 
    if($access){
        $access = $Joueur1->deconnectToi();
    }
    if($access){

        $user = "root";
        $pass = "root";
      
        $mabase = new PDO('mysql:host=127.0.0.1;dbname=lapro_virus', $user, $pass);

        $idMap = 0; //choisissez une map pour faire votre test
        $idEntitePersonnage = 1; //choisissez un pero de test
        echo '<div class="TestIntegration"> TEST ITEM';
            include "testUnitaire/testItem.php"; 
        echo "</div>" ;
        echo '<div class="TestIntegration"> TEST EQUIPEMENT';
            include "testUnitaire/testEquipement.php"; 
        echo "</div>" ;
        echo '<div class="TestIntegration"> TEST ARME';
            include "testUnitaire/testArme.php"; 
        echo "</div>" ;
        echo '<div class="TestIntegration"> TEST POUVOIR';
            include "testUnitaire/testPouvoir.php"; 
        echo "</div>" ;
        echo '<div class="TestIntegration"> TEST ARMURE';
            include "testUnitaire/testArmure.php"; 
        echo "</div>" ;
        echo '<div class="TestIntegration"> TEST ENTITE';
           include "testUnitaire/testEntite.php"; 
        echo "</div>" ;
        echo '<div class="TestIntegration"> TEST PERSONNAGE';
            include "testUnitaire/testPersonnage.php"; 
        echo "</div>" ;
        echo '<div class="TestIntegration"> TEST MOB';
            //include "testUnitaire/testMob.php"; 
        echo "</div>" ;
        echo '<div class="TestIntegration"> TEST MOB';
            //test de Boucher
            include "testUnitaire/testUser.php"; 
        echo "</div>" ;
        echo '<div class="TestIntegration"> TEST TypeItem';
        include "testUnitaire/testTypeItem.php"; 
    echo "</div>" ;



    }else{
        echo $errorMessage;
    }
    ?>
</body>
</html>

    