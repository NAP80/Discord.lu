<?php
    if(rand(0,1)==1){
        ?>
            <div class="divTooltip">
                <?php
                    $tooltip = new Tooltip($mabase);
                    echo $tooltip->getTooltipAleatoire();
                ?>
            </div>
        <?php
    }
?>