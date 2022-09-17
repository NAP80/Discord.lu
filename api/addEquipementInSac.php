<?php
    session_start(); 
    //une API ne dois sortir qu'un seul Echo celui de la reponse !!!!
    //cet API permet de vérifier qu'un item est sur une map et que celui qui l'appel peut le mettre dans son sac
    include "../session.php"; 
    $reponse[5]=0;//retourne un tableau id a retirer du front
    $reponse[4]=0;// id a supprimer si il y a eu fusion l'ancien item doit etre suprimé
    $reponse[3]='init Api' ;//non de item;
    $reponse[2]=0; //,nouveau lvl si fusion 
    $reponse[0]=0;
    $reponse[1]=0;
    if($access){
        if(isset($_GET["idEquipement"])){
            //on doit toujours vérifier en bdd la posibilité de l'appel de API
            //iici on va pour un personnage prendre un item de la map et la mettre dans son sac.
            $reponse[0]=0;
            $reponse[1]=0;
            $Perso = $Joueur1->getPersonnage();
            if($Perso->getHealthNow()==0){
                $Perso->resurection();
                $reponse[1]="Ton personnage est mort.";
            }
            $map=$Perso->getMap();
            //une fois que j'ai mes objet je vérifie que le perso est bien sur la map
            $idmap = $map->getIdMap();
            //que l'item est bien dans la map si ya un Monster on peut pas le prendre
            foreach ($map->getEquipements() as $item) {
                if($_GET["idEquipement"]==$item->getIdObject()){
                    //vérifier si ya des Monster
                    if(count($map->getAllMonsterContre($Joueur1))==0){
                        //on retire l'item de la map et on la rajoute dans le sac
                        $map->removeEquipementById($_GET["idEquipement"]);
                        $item = new Equipement($mabase);
                        $item->setEquipementByID($_GET["idEquipement"]);
                        $reponse[5]=$Perso->addEquipement($item); //retourne un tableau id a retirer du front
                        $reponse[4]=$item->getIdObject();//retourne l'ancien id si y a eu fusion
                        $reponse[3]=$item->getNameObject();
                        $reponse[2]=$item->getLvl();
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