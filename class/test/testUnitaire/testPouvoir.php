<?php
 echo '<div class="testUnitaire"><p>Test de creation Pouvoir </p>';

    $Pouvoir = new Pouvoir($mabase);
    $Pouvoir = $Pouvoir->createPouvoirAleatoire();


    echo 'Affichage de l Pouvoir ';
    $Pouvoir->setEquipementByID($Pouvoir->getId());
    echo "<p>le nom est : ".$Pouvoir->getNom()." ";
    echo " l'id est : ".$Pouvoir->getId()." ";
    echo " la valeur est : ".$Pouvoir->getValeur()." ";
    echo " le lvl  est : ".$Pouvoir->getLvl()." ";
    echo " l'efficacite est de : ".$Pouvoir->getEfficacite()." ";
    echo " la categorie est : ".var_dump($Pouvoir->getCategorie())." ";
    $type = $Pouvoir->getType();
    echo "  <p>ID de lPouvoir ".$type['id']." / nom : ".$type['nom']." </p>" ;
    
    
    $perso = new Personnage($mabase);
    $perso->setEntiteById($idEntitePersonnage);
    echo '<p>Attaque du personnage  '.$perso->getAttaque().'</p>';
    echo 'Ajout dans le sac ';
    $perso->addEquipement($Pouvoir);
    echo 'Puis equipement ';
    $Pouvoir->equipeEntite($perso);
    echo 'Affichage des nouvelle stat de l attaque : ';
    echo $perso->getAttaque();

    
    $Pouvoir->desequipeEntite($perso);
    $perso->removeEquipementById($Pouvoir->getId());
    
    if($Pouvoir->getId()>0){
        echo "<p>suppression de l'equipement".$Pouvoir->deleteEquipement($Pouvoir->getId())."</p>";
    }else{
        echo '<div style="color:red">la suppression a echoué car pas id</div>';
    }

    echo 'Attaque du personnage  :  ';
    echo $perso->getAttaque();

 echo '</div>';

?>