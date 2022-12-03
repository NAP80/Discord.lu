<?php
    class Marche extends Map{
        public function __construct($bdd){
            parent::__construct($bdd);
        }          

        public function livraison($nbrItem){
            for($i=0; $i<$nbrItem; $i++){
                $item = new item($this->_bdd);
                $this->addItem($item->createItemAleatoire()); 
            }
        }

        public function acheter($entite, $idMap, $idEntite){
            $req = $this->_bdd->prepare("SELECT mapitems.idMap, item.nameItem, item.valeur FROM `mapitems`, `item` WHERE item.idItem=mapitems.idItem AND `idMap`=:idMap");
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

            // Récupère l'argent du user
            $req = $this->_bdd->prepare("SELECT user.money FROM `user`, `entite` WHERE user.idPersonnage=entite.idEntite AND entite.idEntite:idEntite");
            $req->execute(['idEntite' => $idEntite]);
            while($Tab = $req->fetch()){
                $money = $Tab[0];
            }
            if(isset($_POST['radio'])){
                foreach($_POST['radio'] as $checkId){
                    $item = new Item($this->_bdd);
                    $item->setItemById($checkId);
                    $valeur = $item->getValeur($checkId);
                }
                if($valeur > $money){
                    ?>
                        <p>Vous n'avez pas assez d'argent</p>
                    <?php
                }
                else{
                    $entite->addItem($item);
                    $this->removeItemByID($checkId);
                    $money -= $valeur;
                    $req = $this->_bdd->prepare("UPDATE `user`, `entite` SET user.money=:money WHERE user.idPersonnage=entite.idEntite AND entite.idEntite=:idEntite");
                    $req->execute(['money' => $money, 'idEntite' => $idEntite]);
                }
            }
        }

        public function vendre($entite, $idEntite){
            $req = $this->_bdd->prepare("SELECT persosacitems.idItem, item.nameItem, item.valeur FROM `persosacitems`, `item`, `user`, `entite` WHERE item.idItem=persosacitems.idItem AND user.idPersonnage=entite.idEntite AND entite.idEntite=:idEntite");
            $req->execute(['idEntite' => $idEntite]);
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
            $req = $this->_bdd->prepare("SELECT user.money FROM `user`, `entite` WHERE user.idPersonnage=entite.idEntite AND entite.idEntite=:idEntite");
            $req->execute(['idEntite' => $idEntite]);
            while($Tab = $req->fetch()){
                $money = $Tab[0];
            }
            if(isset($_POST['checkbox'])){
                foreach($_POST['checkbox'] as $checkId){
                    $item = new item($this->_bdd);
                    $item->setItemById($checkId);
                    $valeur = $item->getValeur($checkId);
                    $items = $entite->removeEquipeBydId($checkId);
                    $money += $valeur;
                }
            }
            $req = $this->_bdd->prepare("UPDATE `user`, `entite` SET user.money=:money WHERE user.idPersonnage=entite.idEntite AND entite.idEntite:idEntite");
            $req->execute(['money' => $money, 'idEntite' => $idEntite]);
        }

        /** Return Name Marché */
        public function getNomMarche(){
            return '<p>Je suis le marché '.$this->_nom.'.</p>';
        }

        public function setMarcheById($idMap){
            parent::setMapById($idMap);
        }
    }
?>