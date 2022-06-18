
<?php

        $newItem = new Item($mabase);



        echo '<div class="testUnitaire"><p>Test 1 Creation Item de Soin </p>';
        $newItem= $newItem->createItemSoinConsommable();
        echo "<p>le nom est : ".$newItem->getNom()."</p>";
        echo "<p>le id est : ".$newItem->getId()."</p>";
        echo "<p>la valeur est : ".$newItem->getValeur()."</p>";
        echo "<p>l'image  est : ".$newItem->getLienImage()."</p>";

        $newItem->getClassRarete();
        if($newItem->getId()>0){
            echo "<p>suppression de l'item ".$newItem->deleteItem($newItem->getId())."</p>";
        }else{
            echo '<div style="color:red">la suppression a echoué car pas id</div>';
        }
        echo "</div>";

        echo '<div class="testUnitaire"><p>Test 2 Creation Item aléatoire </p>';
        $newItem= $newItem->createItemAleatoire();
        echo "<p>le nom est : ".$newItem->getNom()." ";
        echo " le id est : ".$newItem->getId()." ";
        echo " la valeur est : ".$newItem->getValeur()." ";
        echo " le lvl  est : ".$newItem->getLvl()." ";
        echo " l'efficacite est : ".$newItem->getEfficacite()." ";
        $type = $newItem->getType();
        echo "  <p>le type est : id ".$type['id']." / info :".$type['information']." / nom : ".$type['nom']." </p>" ;
        $newItem->getClassRarete();
        if($newItem->getId()>0){
            echo "<p>suppression de l'item".$newItem->deleteItem($newItem->getId())."</p>";
        }else{
            echo '<div style="color:red">la suppression a echoué car pas id</div>';
        }
        echo "</div>";


        



        echo '<div class="testUnitaire"><p>Test 3 création 20 Item aléatoire </p>';
        $listItems = array();
        for($i=0;$i<20;$i++){
            $item = $newItem->createItemAleatoire();
            if(is_null($item)){
                echo '<div style="color:red">Un item  null a été créer c\'est pas normal </div>';
            }else{
                array_push($listItems,$newItem->createItemAleatoire());
            }

            
            
        }
        
        if(count($listItems)>0){
            echo '<div class="left">Items Présent : <div class="divRarete"> Commun - Rare</div><ul class="Item">';
            foreach ( $listItems as  $Item) {
                ?>
                <li id="item<?php echo $Item->getId()?>" style="<?php echo $Item->getClassRarete()?>">
                    <a onclick="CallApiAddItemInSac(<?php echo $Item->getId()?>)">
                        <?php echo $Item->getNom() ?></a>
                </li>
                <?php 

                if($item->getEfficacite() == 0 || $item->getLvl() == 0  || $item->getValeur() == 0){
                    echo '<div style="color:red">il est pas possible d\'avoir 0 en valeur efficacite lvl</div>';
                }

                
                $Item->deleteItem($Item->getId());
            }
            echo '</ul></div>';
        }
       
        echo "</div>";

?>
        
        
        


