

<?php



$newMob = new Mob($mabase);
$map = New Map($mabase);
$map->setMapByID($idMap);
if(is_null($map)){
    echo "<h1>map non chargé</h1>";
}





echo '<div class="testUnitaire"><p>Test 2 création d\'un Mob Aléatoire </p>';


$newMob = $newMob->CreateMobAleatoire($map);
if(!is_null($newMob)){
    $newMob->getBardeVie();
    $newMob->renderHTML();
}



echo "</div>";

echo '<div class="testUnitaire"><p>Test 1 Lecture d\'un Mob  </p>';
$newMob2 = new Mob($mabase);
$newMob2->setMobById($newMob->getId());
$newMob2->getNom();
$newMob2->getBardeVie();
$newMob2->renderHTML();

echo "</div>";

echo '<div class="testUnitaire"><p>Test 3 création de 20 Mob Aléatoire </p>';

for($i=0;$i<20;$i++){
    $newMob = new Mob($mabase);
    $newMob = $newMob->CreateMobAleatoire($map);
    if(!is_null($newMob)){
        $newMob->getBardeVie();
        $newMob->renderHTML();
    }    
}

echo "</div>";



?>





