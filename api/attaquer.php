<?php //fichier modifier par M Vollet
//cette api doit etre lancé pour attaquer un id
//cette API retourne un tableau avec idDuPersoattaque, sa vie restant et sa vie de base
// cette api retour un tableau avec 0 si elle n'a pas eccecuter le code attendu
//une API ne dois sortir qu'un seul Echo celui de la reponse !!!!

session_start();
include "../session.php"; 
$reponse[0]=0;
if($access){
    if(isset($_GET["id"]) && isset($_GET["type"])){
        
        //on récupere la force du perso en cours
        //un joueur est un USER
        $Attaquant = $Joueur1->getPersonnage();
        $Attaquant->addXP(2);
        $message="";
        $vieMax=0;
        $vie=0;
        $vieAttaquant=$Attaquant->getVie();
        $vieMaxAttaquant=$Attaquant->getVieMax();

                //attaque sur perso
            if($_GET["type"]==0 ){
                $Deffensseur = new Personnage($mabase);
                $Deffensseur->setPersonnageByIdWithoutMap($_GET["id"]);
                $vieMax=$Deffensseur->getVieMax();
                $vie=$Deffensseur->getVie();
                //on verrifie que le perso n'est pas mort
                if($Deffensseur->getVie()>0){
                    if($vieAttaquant!=0){
                        $vie=$Deffensseur->SubitDegatByPersonnage($Attaquant);
                        $vieMax = $Deffensseur->getVieMax();
                        $Deffensseur->addXP(1);
                        //on va retirer le coup d'attaque de base du deffensseur
                        //car une attaque n'est pas gratuite
                        $vieAvant = $Attaquant->getVie();
                        $vieAttaquant=$Attaquant->SubitDegatByPersonnage($Deffensseur);
                        $perte = $vieAvant-$vieAttaquant;
                            $message .= "vous avez subit ".$perte." pts de degat ";
                        
                        if($vieAttaquant==0){
                            $message .= " Ton personnage est mort.";
                        }
                        if($vie==0){
                            $lvl = $Deffensseur->getLvl();
                            $Attaquant->addXP($lvl*rand(8,10));
                            $message .= " Tu as tué ".$Deffensseur->getNom();
                            
                        }
                    }else{
                        $message .= " Tu es déjà mort, tu ne peux plus attaquer.";
                    }
                }else{
                    $message .= " Ce personnage est déjà mort.";
                }
            }

            //attaque sur mob
            if($_GET["type"]==1){
                $DeffensseurMob = new Mob($mabase);
                $DeffensseurMob->setMobByIdWithMap($_GET["id"]);
                $vieMax=$DeffensseurMob->getVieMax();
                $vie=$DeffensseurMob->getVie();

                if($DeffensseurMob->getVie()>0){
                    if($vieAttaquant!=0){
                        //Utilisation méthode pour attaquer le mob
                        $SubitDegat = $DeffensseurMob->SubitDegat($Attaquant);
                        
                        //Vie du mob renvoyer après avoir subit l attaque du joueur
                        $vie = $SubitDegat[0];
                        $vieMax = $DeffensseurMob->getVieMax();
                        
                        //Si le mob as de la vie, il attaque. Sinon, rien ne se passe
                        if ($vie>0 && $SubitDegat[1]!='coolDown')
                        {
                            $vieAvant = $Attaquant->getVie();
                            //Sinon : retour de bâton le deffenseur aussi attaque
                            $vieAttaquant=$Attaquant->SubitDegatByMob($DeffensseurMob);

                            $perte = $vieAvant-$vieAttaquant;
                            $message .= "vous avez subit ".$perte." pts de degat ";
                        }

                        //Affichage d'un message avec les dégats ingligé + info de si c'est un cout critique
                        //Si vous voulez retirer le popup, c'est ici; Gros Chien.
                        $message .= $SubitDegat[1];

                        if($vieAttaquant==0){
                            $message .= "Ton personnage est mort.";
                        }

                        //si le perso tu le mob il faut envoyer un message
                        if($vie<=0)
                        {
                            $lvl = $DeffensseurMob->getLvl();
                            $Attaquant->addXP($lvl*rand(8,10)*$DeffensseurMob->getCoefXp());
                            $message .= "Tu as participé à la capture de ce monstre.";
                        }

                    }else{
                        $message .= "Tu es déjà mort, tu ne peux plus attaquer.";

                    }
                }else{
                    $message .= "Ce monstre est déjà capturé.";
                }
            }
        $reponse[0]=$_GET["id"];
        $reponse[1]=$vie;
        $reponse[2]=$vieMax;
        $reponse[3]=$vieAttaquant;
        $reponse[4]=$vieMaxAttaquant;
        $reponse[5]=$Attaquant->getId();
        $reponse[6]=$message;
        $reponse[7]=$Attaquant->getXp();
        $reponse[8]=$Attaquant->getDefense();


    }
}
echo json_encode($reponse); 
?>
