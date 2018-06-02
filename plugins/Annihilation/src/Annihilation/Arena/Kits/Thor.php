<?php

namespace Annihilation\Arena\Kits;

use pocketmine\Player;
use Annihilation\Arena\Arena;
use pocketmine\event\Listener;
use pocketmine\item\Item;

class Thor implements Listener{
    
    public $plugin;
    public $players;
    public $name;
    
    public function __construct(Arena $arena){
        $this->plugin = $arena;
        $this->name = 'thor';
    }
    
    public function give(Player $p){
        $inv = $p->getInventory();
        $inv->setItem(0, Item::get(272, 0, 1));
        $inv->setItem(1, Item::get(270, 0, 1));
        $inv->setItem(2, Item::get(271, 0, 1));
        $inv->setItem(3, Item::get(345, 0, 1));
        $inv->setItem(0, Item::get(54, 0, 1));
    }
}