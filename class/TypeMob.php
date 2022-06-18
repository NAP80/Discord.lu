
<?php
//cette classe est développé par : Lucas ghyselen

class TypeMob  extends Mob{

    private $_rare;
    private $_nom;
    private $_chance;
    private $_id;
    private $_bdd;

    public function __construct($bdd){
        $this->_bdd = $bdd;
    }
    
    public function changenom($id, $nom){ //prend en paramettre un l'id du mob a changer le nom et un nouveau nom.
        $this->_bdd->query("UPDATE `TypeMob` SET `nom` = '".$nom."' WHERE `TypeMob`.`id` = $id");
    }
    
    public function changerarete($id, $rarete){ //prend en paramettre un l'id du mob a changer la rarete et une nouvelle rarete.
        $this->_bdd->query("UPDATE `TypeMob` SET `rarete` = '".$rarete."' WHERE `TypeMob`.`id` = $id");
    }

    public function changechance($id, $chance){ //prend en paramettre un l'id du mob a changer la chance et la nouvelle chance.
        $this->_bdd->query("UPDATE `TypeMob` SET `chance` = '".$chance."' WHERE `TypeMob`.`id` = $id");
    }

    public function nouveaumob($nom, $chance, $rarete){ //prend en paramettre un nom, la chance et la rarete du nouveau mob.
        $this->_bdd->query("INSERT INTO `TypeMob`(`nom`, `rarete`, `chance`) VALUE('".$nom."','".$rarete."','".$chance."')");
    }

    public function affichemob(){ //a mettre dans une boucle
        $this->_bdd->query("SELECT nom FROM `typemob`");
    }

    public function deletemob($id){ //prend en paramettre l'id du mob a delete.
        $this->_bdd->query("DELETE FROM `typeMob` WHERE `id` = $id");
    }

    // Fonction permet de retourner la chance du typemob en fonction de l'id
    public function getChanceById($id) {
        $request = $this->_bdd->prepare("SELECT chance FROM TypeMob WHERE id = ?");
        $request->execute(array($id));
        $this->_chance = $request->fetch();

        return $this->_chance;
    }

    // Fonction permet de retourner la rareté de typemob en fonction de l'id
    public function getRareteById($id) {
        $request = $this->_bdd->prepare("SELECT rarete FROM TypeMob WHERE id = ?");
        $request->execute(array($id));
        $this->_rarete = $request->fetch();
 
        return $this->_rarete = $request->fetch();
    }

    // Fonction permet de retourner le nom de typemob en fonction de l'id
    public function getNomById($id) {
        $request = $this->_bdd->prepare("SELECT nom FROM TypeMob WHERE id = ?");
        $request->execute(array($id));
        $this->_nom = $request->fetch();

        return $this->_nom;
    }
    
}


?>