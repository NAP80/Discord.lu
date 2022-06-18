<?php
 echo '<div class="testUnitaire"><p>Test 1 Creation Armure </p>';

    $Armure = new Armure($mabase);
    $Armure = $Armure->createArmureAleatoire();
 

    echo 'Affichage de l armure ';
    $Armure->setEquipementByID($Armure->getId());

    echo "<p>le nom est : ".$Armure->getNom()."</p>";
    echo "<p> le id est : ".$Armure->getId()." </p>";
    echo "<p> la valeur est : ".$Armure->getValeur()."</p> ";
    echo "<p> le lvl  est : ".$Armure->getLvl()."</p> ";
    echo "<p> l'efficacite est : ".$Armure->getEfficacite()." </p>";
    echo "<p> la categorie est : ".var_dump($Armure->getCategorie())." </p>";
    $type = $Armure->getType();
    echo "  <p>le type est : id ".$type['id']." / info :".$type['information']." / nom : ".$type['nom']." </p>" ;
    
    
    $perso = new Personnage($mabase);
    $perso->setEntiteById($idEntitePersonnage);
    echo '<p>Equipe '.$perso->getNom().' de defense  '.$perso->getDefense().'</p>';
    echo 'Ajout dans le sac ';
    $perso->addEquipement($Armure);
    echo 'Puis equipement ';
    $Armure->equipeEntite($perso);
    echo 'Affichage des nouvelle stat de la defense :  ';
    echo $perso->getDefense();
    $perso->renderHTML();

    
    $Armure->desequipeEntite($perso);
    $perso->removeEquipementById($Armure->getId());
    
    if($Armure->getId()>0){
        echo "<p>suppression de l'equipement".$Armure->deleteEquipement($Armure->getId())."</p>";
    }else{
        echo '<div style="color:red">la suppression a echoué car pas id</div>';
    }

    echo "<p>Affichage des nouvelles stats de la defense :";
    echo "".$perso->getDefense()."</p>";

    echo '<p>Affichage nouvelle force : ';
    echo ''.$Armure->getForce().'</p>';

    echo "<p>Affichage lvl : ";
    echo "".$Armure->getlvl()."</p>";

    echo "<p>Affichage valeur : ";
    echo "".$Armure->getValeur()."</p>";

 echo '</div>';

?>