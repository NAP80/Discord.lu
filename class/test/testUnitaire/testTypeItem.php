<?php
 echo '<div class="testUnitaire"><p>Test Type Item </p>';

    $Item = new Item($mabase);
    $Item = $Item->createItemAleatoire();

    echo "<p>Nom Item : ".$Item->getNom()." ";
    echo "<p>ID : ".$Item->getId()." ";
    echo "<p>la valeur est : ".$Item->getValeur()." ";
    echo "<p>le lvl  est : ".$Item->getLvl()." ";
    echo "<p>l'efficacite est de : ".$Item->getEfficacite()." ";
    echo "<p>l'image  est : ".$newItem->getLienImage()."</p>";
    $type = $Item->getType();

 echo '</div>';

?>