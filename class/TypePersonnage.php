
<?php
//cette classe est développé par : équipe Carre

class TypePersonnage  extends CRUD{

    private $_id;
    private $_coefAttaque;
    private $_coefPouvoir;
    private $_coefBouclier;
    private $_coefDefense;
    private $_distance;
    private $_idFaction;
    private $_lienImage;
    private $_nom;
    private $_bdd;

    public function __construct($bdd){
        $this->_bdd = $bdd;
    }

    //on fourni l'id du personnage on récupér un type de perso
    public function setTypePersonnageByIdPerso($id){

         //select les info personnage
         $req  = "SELECT 
         
         `TypePersonnage`.id,
         `TypePersonnage`.coefAttaque,
         `TypePersonnage`.coefPouvoir,
         `TypePersonnage`.coefBouclier,
         `TypePersonnage`.coefDefense,
         `TypePersonnage`.distance,
         `TypePersonnage`.idFaction,
         `TypePersonnage`.lienImage,
         `TypePersonnage`.nom
         
          FROM `TypePersonnage`,`Personnage`
          WHERE `Personnage`.idTypePersonnage=  `TypePersonnage`.id
          AND `Personnage`.id='".$id."'";
         $Result = $this->_bdd->query($req);

         if($tab=$Result->fetch()){
            $this->_id = $tab['id'];
            $this->_coefAttaque= $tab['coefAttaque'];
            $this->_coefPouvoir= $tab['coefPouvoir'];
            $this->_coefBouclier= $tab['coefBouclier'];
            $this->_coefDefense= $tab['coefDefense'];
            $this->_distance= $tab['distance'];
            $this->_idFaction= $tab['idFaction'];
            $this->_lienImage= $tab['lienImage'];
            $this->_nom= $tab['nom'];
         }

    }
    
    //on fourni l'id du personnage on récupér un type de perso
    public function setTypePersonnageById($id){

        //select les info personnage
        $req  = "SELECT * FROM `TypePersonnage`
         WHERE id='".$id."'";
        $Result = $this->_bdd->query($req);

        if($tab=$Result->fetch()){
           $this->_id = $tab['id'];
           $this->_coefAttaque= $tab['coefAttaque'];
           $this->_coefPouvoir= $tab['coefPouvoir'];
           $this->_coefBouclier= $tab['coefBouclier'];
           $this->_coefDefense= $tab['coefDefense'];
           $this->_distance= $tab['distance'];
           $this->_idFaction= $tab['idFaction'];
           $this->_lienImage= $tab['lienImage'];
           $this->_nom= $tab['nom'];
        }

   }

    public function getId(){
        return $this->_id;
    }
    public function getNom(){
        return $this->_nom;
    }
    public function getCoefBouclier(){
        return $this->_coefBouclier;
    }
    public function getCoefDefense(){
        return $this->_coefDefense;
    }
    public function getCoefAttaque(){
        return $this->_coefAttaque;
    }
    public function getCoefPouvoir(){
        return $this->_coefPouvoir;
    }
    public function getDistance(){
        return $this->_distance;
    }
    public function getLienImage(){
        return $this->_lienImage;
    }
    public function getFaction(){
        $faction = new Faction($this->_bdd);
        $faction->setFactionById($this->_idFaction);
        return $faction;
    }
    //fonction pour supprimé un type de personnage
    public function deleteuseradminversion($bdd){
        $Del = $bdd->query("DELETE FROM typepersonnage WHERE id= ".$_POST['id']."");
            if($Del){
                echo "Type de personnage supprimé";
            }else{
                echo "une erreur est survenue";
            }
    }
    public function showpersonnage($bdd){
        $all = $bdd->query("SELECT * FROM typepersonnage");
        $show = $all->fetch();

        echo $show['id'];
        echo $show['nom'];
        echo $show['coefAttaque'];
        echo $show['coefDefense'];
        echo $show['coefPouvoir'];
        echo $show['coefBouclier'];
        echo $show['distance'];
        echo $show['lienimage'];
        echo $show['idFaction'];

    }
    //fonction pour ajouté un typepersonnage
    public function addperso($bdd){
        //ajoute un commentaire dans la base de la page du jeu selectionné
        $add = $bdd->query("INSERT INTO typepersonnage (nom, coefAttaque, coefDefense, coefPouvoir, coefBouclier, distance, lienImage, idFaction) 
                                               VALUES (".$_POST['nom'].",".$_POST['coefAttaque'].",".$_POST['coefDefense'].",".$_POST['coefBouclier'].",".$_POST['distance'].",".$_POST['lienImage'].",".$_POST['idFaction']." ) ");
        if($add){
            echo "utilisateur ajouté .";
        } else {
            echo "Une erreur est survenue.";
        }
    }
    //fonction pour modifier un nom en base
    public function updatenom($bdd){
        $Up = $bdd->query("UPDATE `typepersonnage` SET `nom`='".$POST['newnom']."' WHERE id=".$this->_id." ");
            if($Up){
                echo "Le nom a bien été changé.";
            }else{
                echo "Une erreur est survenue :/";
            }
    }
    //fonction pour modifier un coefAttaque en base
    public function updateattaque($bdd){
        $Up = $bdd->query("UPDATE `typepersonnage` SET `coefAttaque`='".$POST['newattaque']."' WHERE id=".$this->_id." ");
            if($Up){
                echo "L'attaque a bien été changé.";
            }else{
                echo "Une erreur est survenue :/";
            }
    }
    //fonction pour modifier un coefDeffense en base
    public function updatedeffense($bdd){
        $Up = $bdd->query("UPDATE `typepersonnage` SET `coefDefense`='".$POST['newdefense']."' WHERE id=".$this->_id." ");
            if($Up){
                echo "La défense a bien été changé.";
            }else{
                echo "Une erreur est survenue :/";
            }
    }
    //fonction pour modifier un coefPouvoir en base
    public function updatepouvoir($bdd){
        $Up = $bdd->query("UPDATE `typepersonnage` SET `coefPouvoir`='".$POST['newpouvoir']."' WHERE id=".$this->_id." ");
            if($Up){
                echo "Le pouvoir a bien été changé.";
            }else{
                echo "Une erreur est survenue :/";
            }
    }
    //fonction pour modifier un coefBouclier en base
    public function updatebouclier($bdd){
        $Up = $bdd->query("UPDATE `typepersonnage` SET `coefBouclier`='".$POST['newbouclier']."' WHERE id=".$this->_id." ");
            if($Up){
                echo "Le bouclier a bien été changé.";
            }else{
                echo "Une erreur est survenue :/";
            }
    }
    //fonction pour modifier un distance en base
    public function updatedistance($bdd){
        $Up = $bdd->query("UPDATE `typepersonnage` SET `distance`='".$POST['newdistance']."' WHERE id=".$this->_id." ");
            if($Up){
                echo "La distance a bien été changé.";
            }else{
                echo "Une erreur est survenue :/";
            }
    }
    //fonction pour modifier un lienimage en base
    public function updateimage($bdd){
        $Up = $bdd->query("UPDATE `typepersonnage` SET `lienimage`='".$POST['newlienimage']."' WHERE id=".$this->_id." ");
            if($Up){
                echo "Le lienimage a bien été changé.";
            }else{
                echo "Une erreur est survenue :/";
            }
    }
    //fonction pour modifier un idFaction en base
    public function updatefaction($bdd){
        $Up = $bdd->query("UPDATE `typepersonnage` SET `idFaction`='".$POST['newidfaction']."' WHERE id=".$this->_id." ");
            if($Up){
                echo "L'idFaction a bien été changé.";
            }else{
                echo "Une erreur est survenue :/";
            }
    }
}
?>