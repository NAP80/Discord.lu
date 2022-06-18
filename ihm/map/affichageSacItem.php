<div class="divSacItem">
    <p class="pTitleSacItems">Items</p>
    <ul id="SacItem" class="ulSac">
        <?php
            $listItems = $Joueur1->getPersonnage()->getItems();
            if(count($listItems) > 0){
                foreach($listItems as $Item){
                    ?>
                        <li id="itemSac<?= $Item->getId() ?>">
                            <a onclick="useItem(<?= $Item->getId() ?>)">
                            <img class='imgItemSac' src='<?= $Item->getLienImage() ?>'/>
                                <span class='spanItemSac'>
                                    <?= $Item->getNom() ?> lvl <?= $Item->getLvl() ?>
                                </span>
                            </a>
                        </li>
                    <?php
                }
            }
        ?>
    </ul>
</div>