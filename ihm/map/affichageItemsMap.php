<div class="divMapItem">
    <?php
        $listItems = $MapPersonnage->getItems();
        if(count($listItems) > 0){
            ?>
                <div class="divInfoItem">
                    <p class="pItem">Items Présent :</p>
                    <p class="divRarete">Commun - Rare</p>
                </div>
                <ul class="ulItem">
                    <?php
                        foreach($listItems as $Item){
                            ?>
                                <li id="item<?= $Item->getIdItem() ?>" style="<?= $Item->getClassRarete() ?>">
                                    <a onclick="CallApiAddItemInSac(<?= $Item->getIdItem() ?>)">
                                        <?= $Item->getNameItem() ?>
                                    </a>
                                </li>
                            <?php
                        }
                    ?>
                </ul>
            <?php
        }
        else{
            $ZoneObjectEmpty++;
        }
    ?>
</div>