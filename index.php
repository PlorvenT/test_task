<?php

require_once __DIR__ . '/Item.php';
require_once __DIR__ . '/RandomItemManager.php';
$items[] = new Item('iron', 40, 75);
$items[] = new Item('copper', 30, 75);
$items[] = new Item('gold', 15, 150);
$items[] = new Item('platinum', 10, 500);
$items[] = new Item('diamond', 5, 1000);

$randomItemManager = new RandomItemManager(4, 6, 3000);
$randomSet = $randomItemManager->generateSet($items);
var_dump($randomSet);
$newSet = $randomItemManager->generateChangedSet($randomSet, $items);
var_dump($newSet);
