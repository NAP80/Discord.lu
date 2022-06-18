

<?php


$newPersonnage = new Personnage($mabase);



echo '<div class="testUnitaire"><p>Test 1 Lecture d\'un  Personnage  </p>';
$newPersonnage->setPersonnageById(1);
echo $newPersonnage->getNom();
$newPersonnage->renderHTML();
echo "<p>Ajout de 10 pts xp</p>";
$newPersonnage->addXP(10);
$newPersonnage->renderHTML();


echo "</div>";


echo '<div class="testUnitaire"><p>Test 2 création d\'un Personnage de type Perso </p>';
$newPersonnage = $newPersonnage->CreatNewPersonnage(1);
if(!is_null($newPersonnage)){
    $newPersonnage->getBardeVie();
    $newPersonnage->renderHTML();
}



echo "</div>";



?>





