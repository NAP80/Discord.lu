<?php
    session_start();
    //cette api retourne aprés usage d'une item l'atttaque la healthNow et la healthMax 
    //elle retourn 0 si çà c'est pas bien passé
    include "../session.php"; 
    $reponse[0]=0;
    $reponse[1]=0;
    if($access){
        if(isset($_GET["idItem"])){
            //on doit toujours vérifier en bdd la posibilité de l'appel de API
            //iici on va utiliser un item pour un personnage.
            $message ='';
            $reponse[0]=0;
            $reponse[1]=0;
            $Perso = $Joueur1->getPersonnage();
            if($Perso->getHealthNow()==0){
                $Perso->resurection();
                $reponse[1]="ton perso est mort";
            }
            //une fois que j'ai mes objet je vérifie que le perso possède bien item
            foreach ($Perso->getItems()  as $item) {
                if($_GET["idItem"]==$item->getIdItem()){
                    //on retire l'item du perso
                    $Perso->removeItemById($item->getIdItem());
                    //selon l'id du type on fait un truc différent
                    $type = $item->getTypeItem();
                    switch ($type['idTypeItem']) {
                        case 2:
                            $calcul = $item->getEfficacite()*$item->getLvlItem()*$item->getValeur();
                            $valeur = $Perso->SoinPourcentage($calcul);
                            $message = $Perso->getNameEntite()." à été soigné de ".$valeur."pts de vie avec une efficacite de ".$calcul."%";
                            break;
                        default:
                            $healthmore=round($item->getValeur()/2)*$item->getLvlItem();
                            if($healthmore<2){
                                $healthmore =2;
                            }
                            $attaque=round($healthmore/2);
                            $message = $Perso->getNameEntite()." a utilisé un objet.";
                            $Perso->lvlupAttaque($attaque);
                            $Perso->lvlupHealthNow($healthmore);
                            $Perso->lvlupHealthMax($healthmore);
                            break;
                    }
                    $reponse[0] = $Perso->getIdEntite();
                    $reponse[1] = $Perso->getAttaque();
                    $reponse[2] = $Perso->getDefense();
                    $reponse[3] = $Perso->getHealthNow();
                    $reponse[4] = $Perso->getHealthMax();
                    $reponse[5] = $message;
                }
            }
        }
    }
    echo json_encode($reponse);
?>