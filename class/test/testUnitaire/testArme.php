<?php
 echo '<div class="testUnitaire"><p>Test de creation Arme </p>';

    $Arme = new Arme($mabase);
    $Arme = $Arme->createArmeAleatoire();


    echo 'Affichage de l arme ';
    $Arme->setEquipementByID($Arme->getId());
    echo "<p>le nom est : ".$Arme->getNom()." ";
    echo " l'id est : ".$Arme->getId()." ";
    echo " la valeur est : ".$Arme->getValeur()." ";
    echo " le lvl  est : ".$Arme->getLvl()." ";
    echo " l'efficacite est de : ".$Arme->getEfficacite()." ";
    echo " la categorie est : ".var_dump($Arme->getCategorie())." ";
    $type = $Arme->getType();
    echo "  <p>ID de lArme ".$type['id']." / nom : ".$type['nom']." </p>" ;
    
    
    $perso = new Personnage($mabase);
    $perso->setEntiteById($idEntitePersonnage);
    echo '<p>Attaque du personnage  '.$perso->getAttaque().'</p>';
    echo 'Ajout dans le sac ';
    $perso->addEquipement($Arme);
    echo 'Puis equipement ';
    $Arme->equipeEntite($perso);
    echo 'Affichage des nouvelle stat de l attaque : ';
    echo $perso->getAttaque();

    
    $Arme->desequipeEntite($perso);
    $perso->removeEquipementById($Arme->getId());
    
    if($Arme->getId()>0){
        echo "<p>suppression de l'equipement".$Arme->deleteEquipement($Arme->getId())."</p>";
    }else{
        echo '<div style="color:red">la suppression a echoué car pas id</div>';
    }

    echo 'Attaque du personnage  :  ';
    echo $perso->getAttaque();

 echo '</div>';

?>