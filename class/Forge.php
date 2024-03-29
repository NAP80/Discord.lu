<?php
    class Forge extends Map{
        public function __construct($bdd){
            parent::__construct($bdd);
        }

        public function livraison($nbrEquipement){
            for($i=0; $i<$nbrEquipement; $i++){
                $equipement = new Equipement($this->_bdd);
                $this->addEquipement($equipement->createEquipementAleatoire());
            }
        }

        public function acheter($Personnage, $idMap, $idPersonnage){
            $req = $this->_bdd->prepare("SELECT mapequipements.idEquipement, equipement.nameEquipement, equipement.valeur FROM `mapequipements`, `equipement` WHERE equipement.idEquipement=mapequipements.idEquipement AND `idMap`:idMap");
            $req->execute(['idMap' => $idMap]);
            ?>
                <form method="post">
                    <table>
                        <?php
                            while($Tab = $req->fetch()){
                                ?>
                                    <tr>
                                        <td><?= $Tab[1] ?></td>
                                        <td><?= $Tab[2] ?></td>
                                        <td><input type="radio" name="radio[]" value="<?= $Tab[0] ?>"></td>
                                    </tr>
                                <?php
                            }
                        ?>
                    </table>
                    <input type="submit" name="acheter" value="Acheter">
                </form>
            <?php

            // Récupère l'argent du user // Refaire complètement
            $req = $this->_bdd->prepare("SELECT user.money FROM `user`, `entite` WHERE user.idPersonnage=entite.idEntite AND entite.idEntite:idPersonnage");
            $req->execute(['idPersonnage' => $idPersonnage]);
            while($Tab = $req->fetch()){
                $money = $Tab[0];
            }
            if(isset($_POST['radio'])){
                foreach($_POST['radio'] as $checkId){
                    $equipement = new equipement($this->_bdd);
                    $equipement->setEquipementById($checkId);
                    $valeur = $equipement->getValeur($checkId);
                }
                if($valeur > $money){
                    ?>
                        <p>Vous n'avez pas assez d'argent.</p>
                    <?php
                }
                else{
                    $Personnage->addEquipement($equipement);
                    $this->removeEquipementById($checkId);
                    $money -= $valeur;
                    $req = $this->_bdd->prepare("UPDATE `personnage`, `entite` SET personnage.moneyPersonnage=:money WHERE personnage.idPersonnage=entite.idEntite AND personnage.idEntite=:idPersonnage");
                    $req->execute(['money' => $money, 'idPersonnage' => $idPersonnage]);
                }
            }
        }

        public function vendre($Personnage, $idPersonnage){
            $req = $this->_bdd->prepare("SELECT Entiteequipement.idEquipement, Equipement.nameEquipement, Equipement.valeur FROM `Entiteequipement`, `Equipement` WHERE Equipement.idEquipement=entiteequipement.idEquipement AND `equipe`!=1 AND `idEntite`:idPersonnage");
            $req->execute(['idPersonnage' => $idPersonnage]);
            $equipements = $Personnage->getEquipementNonPorte();
            ?>
                <form method="post">
                    <table>
                        <?php
                            while($Tab = $req->fetch()){
                                ?>
                                    <tr>
                                        <td><?= $Tab[1] ?></td>
                                        <td><?= $Tab[2] ?></td>
                                        <td><input type="checkbox" name="checkbox[]" value="<?= $Tab[0] ?>"></td>
                                    </tr>
                                <?php
                            }
                        ?>
                    </table>
                    <input type="submit" name="vendre" value="Vendre">
                </form>
            <?php

            // Récupère l'argent du user
            $req = $this->_bdd->prepare("SELECT user.money FROM `user`, `entite` WHERE user.idPersonnage=entite.idEntite AND entite.idEntite=:idPersonnage");
            $req->execute(['idPersonnage' => $idPersonnage]);
            while($Tab = $req->fetch()){
                $money = $Tab[0];
            }
            if(isset($_POST['checkbox'])){
                foreach($_POST['checkbox'] as $checkId){
                    $equipement = new equipement($this->_bdd);
                    $equipement->setEquipementById($checkId);
                    $valeur = $equipement->getValeur($checkId);
                    $equipements = $Personnage->removeEquipementByID($checkId);
                    $money += $valeur;
                }
            }
            $req = $this->_bdd->prepare("UPDATE `user`, `entite` SET user.money=:money WHERE user.idPersonnage=entite.idEntite AND entite.idEntite=:idPersonnage");
            $req->execute(['money' => $money, 'idPersonnage' => $idPersonnage]);
        }

        /** Return Name Forge */
        public function getNomForge(){
            return 'Je suis la forge '.$this->_nom;
        }

        public function setForgeById($idMap){
            parent::setMapById($idMap);
        }
    }
?>