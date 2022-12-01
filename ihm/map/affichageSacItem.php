<div class="divSacItem">
    <p class="pTitleSacItems">Items</p>
    <ul id="SacItem" class="ulSac">
        <?php
            $listItems = $Joueur1->getPersonnage()->getItems();
            if(count($listItems) > 0){
                foreach($listItems as $Item){
                    ?>
                        <li id="itemSac<?= $Item->getIdItem() ?>">
                            <a onclick="useItem(<?= $Item->getIdItem() ?>)">
                            <img class='imgItemSac' src='<?= $Item->getImgItem() ?>'/>
                                <span class='spanItemSac'>
                                    <?= $Item->getNameItem() ?> LV <?= $Item->getLvlItem() ?>
                                </span>
                            </a>
                        </li>
                    <?php
                }
            }
        ?>
    </ul>
</div>