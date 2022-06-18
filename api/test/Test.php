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

        $user = "lapro_site";
        $pass = "TDataSource1234";
      
        $mabase = new PDO('mysql:host=127.0.0.1;dbname=lapro_virus', $user, $pass);

        $idMap = 0; //choisissez une map pour faire votre test
        $idEntitePersonnage = 1; //choisissez un pero de test
        echo '<div class="TestIntegration"> TEST COMBAT';
            include "testAPI/testCombat.php"; 
        echo "</div>" ;
        



    }else{
        echo $errorMessage;
    }
    ?>
</body>
</html>

    