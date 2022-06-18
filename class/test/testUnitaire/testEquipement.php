
<?php

$newEquipement = new Equipement($mabase);





echo '<div class="testUnitaire"><p>Test 1 Creation Equipement aléatoire </p>';
$newEquipement= $newEquipement->createEquipementAleatoire();
echo "<p>le nom est : ".$newEquipement->getNom()." ";
echo " le id est : ".$newEquipement->getId()." ";
echo " la valeur est : ".$newEquipement->getValeur()." ";
echo " le lvl  est : ".$newEquipement->getLvl()." ";
echo "<p>l'image  est : ".$newEquipement->getLienImage()."</p>";

        
echo " l'efficacite est : ".$newEquipement->getEfficacite()." ";
$type = $newEquipement->getType();
echo "  <p>le type est : id ".$type['id']." / info :".$type['information']." / nom : ".$type['nom']." </p>" ;
$newEquipement->getClassRarete();
if($newEquipement->getId()>0){
    echo "<p>suppression de l'equipement".$newEquipement->deleteEquipement($newEquipement->getId())."</p>";
}else{
    echo '<div style="color:red">la suppression a echoué car pas id</div>';
}
echo "</div>";






echo '<div class="testUnitaire"><p>Test 3 création 20 Equipement aléatoire </p>';
    $listEquipements = array();
    for($i=0;$i<20;$i++){
        $equipement = $newEquipement->createEquipementAleatoire();
        if(is_null($equipement)){
            echo '<div style="color:red">Un equipement  null a été créer c\'est pas normal </div>';
        }else{
            array_push($listEquipements,$equipement);
        }

        
        
    }
    $map = New Map($mabase);
    $map->setMapByID($idMap);

    if(count($listEquipements)>0){
        echo '<div class="left">Equipements Présent : <div class="divRarete"> Commun - Rare</div><ul class="Equipement">';
        foreach ( $listEquipements as  $Equipement) {
            ?>
            <li id="equipement<?php echo $Equipement->getId()?>" style="<?php echo $Equipement->getClassRarete()?>">
                <a onclick="CallApiAddEquipementInSac(<?php echo $Equipement->getId()?>)">
                    <?php echo $Equipement->getNom() ?></a>
            </li>
            <?php if(empty($Equipement->getId())){
                 echo '<div style="color:red">il est pas possible d\'avoir un id vide</div>';
            }
            ?>
            <?php 

            if($equipement->getEfficacite() == 0 || $equipement->getLvl() == 0  || $equipement->getValeur() == 0){
                echo '<div style="color:red">il est pas possible d\'avoir 0 en valeur efficacite lvl</div>';
            }

            echo "<p>ajout de l'equipement dans une map</p>";
            $map->addEquipement($Equipement);
            echo "<p>Suppresion de l'equipement dans la map et dans le jeu</p>";
            $map->removeEquipementByID($Equipement->getId());
            $Equipement->deleteEquipement($Equipement->getId());
            
        }
        echo '</ul></div>';
    }

echo "</div>";

?>





