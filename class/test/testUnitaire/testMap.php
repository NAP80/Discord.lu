<?php

include "../../../session.php";

$map = new Map($mabase);
$res = $map->getMapWithOneMob();
$res2 = $map->getMapWithoutMob();

echo "Map avec un mob au minimum ".$res."<br/>";
echo "Map sans mob".$res2;