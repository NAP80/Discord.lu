<?php
    session_start(); 
    //une API ne dois sortir qu'un seul Echo celui de la reponse !!!!
    //cet API permet de vérifier qu'un item est sur une map et que celui qui l'appel peut le mettre dans son sac
    include "../session.php"; 
    $reponse[0]=0;
    $reponse[1]=0;
    if($access){
        if(isset($_GET["idItem"])){
            //on doit toujours vérifier en bdd la posibilité de l'appel de API
            //iici on va pour un personnage prendre un item de la map et la mettre dans son sac.
            $reponse[0]=0;
            $reponse[1]=0;
            $Perso = $Joueur1->getPersonnage();
            if($Perso->getHealthNow()==0){
                $Perso->resurection();
                $reponse[1]="Ton personnage est mort.";
            }
            $MapPersonnage = $Perso->getMapEntite();
            //une fois que j'ai mes objet je vérifie que le perso est bien sur la map
            $idmap = $MapPersonnage->getIdMap();
            //que l'item est bien dans la map si ya un Monster on peut pas le prendre
            foreach($MapPersonnage->getItems() as $item){
                if($_GET["idItem"]==$item->getIdItem()){
                    //vérifier si ya des Monster
                    if(count($MapPersonnage->getAllMonsterContre($Joueur1))==0){
                        //on retire l'item de la map et on la rajoute dans le sac
                        $MapPersonnage->removeItemById($_GET["idItem"]);
                        $item = new Item($mabase);
                        $item->setItemByID($_GET["idItem"]);
                        $Perso->addItem($item);
                        $reponse[1]=1;
                        $reponse[0]=1;
                    }
                    else{
                        $reponse[2]="On ne peut pas voler des objets s'il y a des monstres encore vivants.";
                        $reponse[1]=0;
                        $reponse[0]=1;
                    }
                }
            }
        }
    }
    echo json_encode($reponse);
?>