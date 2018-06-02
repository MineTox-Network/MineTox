<?php

namespace Annihilation\Arena\Kits;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\Player;
use Annihilation\Arena\Arena;
use pocketmine\event\Listener;
use pocketmine\item\Item;

class Berserker implements Listener{
    
    public $plugin;
    public $players;
    public $name;
    
    public function __construct(Arena $arena){
        $this->plugin = $arena;
        $this->name = 'berserker';
    }
    
    public function give(Player $p){
        $inv = $p->getInventory();
        $inv->setItem(0, Item::get(272, 0, 1));
        $inv->setItem(1, Item::get(270, 0, 1));
        $inv->setItem(2, Item::get(271, 0, 1));
        $inv->setItem(3, Item::get(345, 0, 1));
        $inv->setItem(0, Item::get(54, 0, 1));
    }
    
    public function onDamage(EntityDamageEvent $e){
        if($e instanceof EntityDamageByEntityEvent){
            $killer = $e->getDamager();
            $victim = $e->getEntity();
            if($kiler instanceof Player && $e->getFinalDamage() >= $victim->getHealth() && $killer->getMaxHealth() <= 14){
                $killer->setMaxHealth($killer->getMaxHealth());
            }
        }
    }
}