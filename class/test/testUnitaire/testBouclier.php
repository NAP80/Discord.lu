<?php
include "../Bouclier.php";
 echo '<div class="testUnitaire"><p>Test Bouclier</p>';

    $Result = new Bouclier($mabase);
    $Result = $Result->createBouclierAleatoire();

    echo "<p>Nom Bouclier : ".$Result->getNom()." ";
    echo "<p>ID : ".$Result->getId()." ";
    echo "<p>la valeur est : ".$Result->getValeur()." ";
    echo "<p>le lvl  est : ".$Result->getLvl()." ";
    echo "<p>l'efficacite est de : ".$Result->getEfficacite()." ";

 echo '</div>';

?>