<div class="divSacItem">
    <p class="pTitleSacItems">Items</p>
    <ul id="SacItem" class="ulSac">
        <?php
            $listItems = $Joueur1->getPersonnage()->getItems();
            if(count($listItems) > 0){
                foreach($listItems as $Item){
                    ?>
                        <li id="itemSac<?= $Item->getIdObject() ?>">
                            <a onclick="useItem(<?= $Item->getIdObject() ?>)">
                            <img class='imgItemSac' src='<?= $Item->getImgItem() ?>'/>
                                <span class='spanItemSac'>
                                    <?= $Item->getNameObject() ?> lvl <?= $Item->getLvl() ?>
                                </span>
                            </a>
                        </li>
                    <?php
                }
            }
        ?>
    </ul>
</div>