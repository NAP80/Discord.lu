
<?php
//cette classe est développé par : Yann  Martin

class TypeItem  extends CRUD {

    /**
     * 
     * Cette fonction permet modifier le nom de l'item en fonction de son id.
     * 
     * Entries :
     * 
     * $nom = variable contenant le nouveau nom
    */
    public function setItemNom($nom) {

        $req = $this->_bdd->prepare("UPDATE TypeItem SET nom = ? WHERE id = ?");
        $req->execute(array($nom, $this->_id));

        $this->_nom = $nom;
    }

    /**
     * 
     * Cette fonction permet modifier la rareté d'un item en fonction de son id.
     * 
     * Entries :
     * 
     * $rarete = variable contenant la valeur de rareté
    */
    public function setRarete($rarete) {

        $req = $this->_bdd->prepare("UPDATE TypeItem SET rarete = ? WHERE id = ?");
        $req->execute(array($rarete, $this->_id));

        $this->_rarete = $rarete;
    }
    
    /**
     * 
     * Cette fonction permet modifier les informations d'un item en fonction de son id
     * 
     * Entries :
     * 
     * $information = variable contenant les nouvelles informations
    */
    public function setInformation($information) {

        $req = $this->_bdd->prepare("UPDATE TypeItem SET information = ? WHERE id = ?");
        $req->execute(array($information, $this->_id));

        $this->_information = $information;
    }

    /**
     * 
     * Cette fonction permet de supprimer un item en fonction de l'id
     * 
     * Entries :
     * 
     * Aucune entrée supplémentaire
    */
    public function deleteItem() {

        $req = $this->_bdd->prepare("DELETE FROM TypeItem WHERE id = ?");
        $req->execute(array($this->_id));
    }

    /**
     * 
     * Cette fonction permet d'ajouter un item
     * 
     * Entries :
     * 
     * $nom = variable contenant le nom de l'item
     * $rarete = variable contenant la valeur de rarete
     * $lienimage = variable contenant de lien de l'image
     * $information - variable contenant les informations
     * $chance = variable contenant la valeur de chance
     * 
     */
    public function addItem($nom, $rarete, $lienImage, $information, $chance) {

        $nom = htmlspecialchars($nom);
        $rarete = htmlspecialchars($rarete);
        $information = htmlspecialchars($information);
        $chance = htmlspecialchars($chance);

        if(!empty($nom) AND !empty($rarete) AND !empty($information) AND !empty($chance)) {
            $req = $this->_bdd->prepare("INSERT INTO user SET nom = ?, rarete = ?, lienImage = ?, information = ?, chance = ?");
            $req->execute(array($nom, $rarete, $lienImage, $information, $chance));

            $this->_nom = $nom;
            $this->_rarete = $rarete;
            $this->_lienImage = $lienImage;
            $this->_information = $information;
            $this->_chance = $chance;
        }
    }
    
    /**
     * 
     * Cette fonction permet de modifier le lien de l'iimage
     * 
     * Entries :
     * 
     * $lienImage = variable contenant le lien de l'image
    */
    public function setImage($lienImage) {

        $req = $this->_bdd->prepare("UPDATE TypeItem SET lienImage = ? WHERE id = ?");
        $req->execute(array($lienImage));
    }
    
    /**
     * 
     * Cette fonction permet d'afficher la liste des items
     * 
     * Entries :
     * 
     * Aucune entrée nécessaire
    */
    public function getItem() {

        $req = $this->_bdd->prepare("SELECT * FROM TypeItem");
        $req->execute();

        return $req->fetch();
    }

    /**
     * 
     * Cette fonction retourne l'information en fonction d'id
     * 
     * Entries :
     * 
     * $id = id du typeitem
    */
    public function getInfoById($id) {

        $req = $this->_bdd->prepare("SELECT information FROM TypeItem WHERE id = ?");
        $req->execute($id);

        return $req->fetch();
    }

    /**
     * 
     * Cette fonction retourne le nom en fonction d'id
     * 
     * Entries :
     * 
     * $id = id du typeitem
    */
    public function getNomById($id) {

        $req = $this->_bdd->prepare("SELECT nom FROM TypeItem WHERE id = ?");
        $req->execute($id);

        return $req->fetch();
    }

    /**
     * 
     * Cette fonction retourne la rareté en fonction d'id
     * 
     * Entries :
     * 
     * $id = id du typeitem
    */
    public function getRareteById($id) {

        $req = $this->_bdd->prepare("SELECT rarete FROM TypeItem WHERE id = ?");
        $req->execute($id);

        return $req->fetch();
    }

    /**
     * 
     * Cette fonction retourne le lien de l'image en fonction d'id
     * 
     * Entries :
     * 
     * $id = id du typeitem
    */
    public function getLienImageById($id) {

        $req = $this->_bdd->prepare("SELECT lienImage FROM TypeItem WHERE id = ?");
        $req->execute($id);

        return $req->fetch();
    }

    /**
     * 
     * Cette fonction retourne la chance en fonction d'id
     * 
     * Entries :
     * 
     * $id = id du typeitem
    */
    public function getChanceById($id) {

        $req = $this->_bdd->prepare("SELECT chance FROM TypeItem WHERE id = ?");
        $req->execute($id);

        return $req->fetch();
    }


?>