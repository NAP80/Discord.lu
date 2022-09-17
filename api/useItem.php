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
                if($_GET["idItem"]==$item->getIdObject()){
                    //on retire l'item du perso
                    $Perso->removeItemById($item->getIdObject());
                    //selon l'id du type on fait un truc différent
                    $type = $item->getType();
                    switch ($type['id']) {
                        case 2:
                            $calcul = $item->getEfficacite()*$item->getLvl()*$item->getValeur();
                            $valeur = $Perso->SoinPourcentage($calcul);
                            $message = $Perso->getNameEntite()." à été soigné de ".$valeur."pts de vie avec une efficacite de ".$calcul."%";
                            break;
                        default:
                            $healthmore=round($item->getValeur()/2)*$item->getLvl();
                            if($healthmore<2){
                                $healthmore =2;
                            }
                            $attaque=round($healthmore/2);
                            $message = $Perso->getNameEntite()." à utilisé un objet";
                            $Perso->lvlupAttaque($attaque);
                            $Perso->lvlupHealthNow($healthmore);
                            $Perso->lvlupHealthMax($healthmore);
                            break;
                    }
                    $reponse[8] = $Perso->getDefense();
                    $reponse[4] = $message;
                    $reponse[3] = $Perso->getHealthMax();
                    $reponse[2] = $Perso->getHealthNow();
                    $reponse[1] = $Perso->getAttaque();
                    $reponse[0] = $Perso->getIdEntite();
                }
            }
        }
    }
    echo json_encode($reponse);
?>