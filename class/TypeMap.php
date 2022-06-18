
<?php
//cette classe est développé par : Duval

class TypeMap  extends Map{
    //privée:
    private $_Nom;
    private $_NomFr;
    private $_IdTypeMap;
    private $_bdd;
    //public : 

    public function __construct($IdTypeMap, $bdd)
    {
        $this->_bdd = $bdd;
        $this->_IdTypeMap = $IdTypeMap;
        
    }

    //fonction qui permet de modifier le NomFr en base elle attend en paramettre le nouveau nomFr:
    public function UpdateNomFr($newNomFr){
        $prepartRequ = $this->_bdd -> prepare("UPDATE `TypeMap` SET `nomFr`= ? WHERE `id` = ?");
        $prepartRequ->execute(array($newNomFr, $this->_IdTypeMap));
        $this->_NomFr = $newNomFr;


    }
    
    //fonction qui permet de modifier le Nom en base elle attend en paramettre le nouveau nom:
    public function UpdateNom($newNom){
        $prepartRequ = $this->_bdd -> prepare("UPDATE `TypeMap` SET `nom`= ? WHERE `id` = ?");
        $prepartRequ->execute(array($newNom, $this->_IdTypeMap));
        $this->_Nom = $newNom;
        
    }

    //fonction qui permet de suprimer une type map en base elle attend rien en paramètre:
    public function Delete(){
        $prepartRequ = $this->_bdd -> prepare("DELETE FROM `TypeMap` WHERE `id` = ?");
        $prepartRequ->execute(array($this->_IdTypeMap));
    }

    //fonction qui permet d'ajoute une type map en base elle attend en paramètre un Nom et un NomFr:
    public function Insert($Nom, $Nomfr){
        $prepartRequ = $this->_bdd -> prepare("INSERT INTO `TypeMap`(`nom`, `nomFr`) VALUES (?,?)");
        $prepartRequ->execute(array($Nom, $Nomfr));
    }

    //fonction qui permet de selectioner une type map en base elle attend rien en paramètre:
    public function Select(){
        $prepartRequ = $this->_bdd -> prepare("SELECT * FROM `TypeMap` WHERE `id` = ?");
        $executeRequ =  $prepartRequ->execute(array($this->_IdTypeMap));
        $data = $executeRequ -> fetch();
        $this->_Nom = $data['nom'];
        $this->_NomFr = $data['nomFr'];
    }

    //finction qui return le nom typemap elle attend rien en coummentaire:
    public function getNom(){
        return $this->_Nom;        
    }

    //finction qui return le nomFr typemap elle attend rien en coummentaire:
    public function getNomFr(){
        return $this->_NomFr;        
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

        $req = $this->_bdd->prepare("SELECT nom FROM TypeMap WHERE id = ?");
        $req->execute($id);

        return $req->fetch();
    }

    /**
     * 
     * Cette fonction retourne le nomFr en fonction d'id
     * 
     * Entries :
     * 
     * $id = id du typeitem
    */
    public function getNomFrById($id) {

        $req = $this->_bdd->prepare("SELECT nomFr FROM TypeMap WHERE id = ?");
        $req->execute($id);

        return $req->fetch();
    }
    
}


?>