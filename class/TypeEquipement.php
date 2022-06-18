<?php 

class TypeEquipement extends Equipement {

    private $_nom;
    private $_rarete;
    private $_idCategorie;
    private $_chance;
    private $_lienImage;
    private $_typeEquipement;

    // Fonction permet de récupèrer le nom à partir de l'id
    public function getNameById($id) {
        $request = $this->_bdd->prepare("SELECT nom FROM TypeEquipement WHERE id = ?");
        $request->execute(array($id));
        $this->_nom = $request->fetch();

        return $this->_nom;
   }

   // Function permet de récupèrer la rareté à partir de l'id
   public function getRareteById($id) {
       $request = $this->_bdd->prepare("SELECT rarete FROM TypeEquipement WHERE id = ?");
       $request->execute(array($id));
       $this->_rarete = $request->fetch();

       return $this->_rarete = $request->fetch();
   }

   // Fonction permet de recupèrer l'id de categorie
   public function getIdCategorieById($id) {
        $request = $this->_bdd->prepare("SELECT idCategorie FROM TypeEquipement WHERE id = ?");
        $request->execute(array($id));
        $this->_idCategorie = $request->fetch();

        return $this->_idCategorie;
   }

   // Fonction permet de recupèrer la chance
   public function getChanceById($id) {
       $request = $this->_bdd->prepare("SELECT chance FROM TypeEquipement WHERE id = ?");
       $request->execute(array($id));
       $this->_chance = $request->fetch();

       return $this->_chance;
   }

   // Fonction permet de récupèrer le lien d'image
   public function getLienImageById($id) {
       $request = $this->_bdd->prepare("SELECT lienImage FROM TypeEquipement WHERE id = ?");
       $request->execute(array($id));
       $this->_lienImage = $request->fetch();

       return $this->_lienImage;
   }

   // Fonction permet de retourner la ligne entière en fonction de l'id
   public function getTypeEquipement($id) {
       $request = $this->_bdd->prepare("SELECT * FROM TypeEquipement WHERE id = ?");
       $request->execute(array($id));
       $this->_typeEquipement = $request->fetch();

       return $this->_typeEquipement;
   }
   

}