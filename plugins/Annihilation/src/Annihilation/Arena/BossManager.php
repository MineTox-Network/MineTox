<?php

namespace Annihilation\Arena;

use pocketmine\level\Position;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\level\Location;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\Double;
use pocketmine\nbt\tag\Enum;
use pocketmine\nbt\tag\Float;
use pocketmine\nbt\tag\Short;
use pocketmine\nbt\tag\String;
use pocketmine\entity\Zombie;
use pocketmine\tile\Chest;
use Annihilation\Arena\Arena;

class BossManager{
    
    public $arena;
    
    public function __construct(Arena $arena){
        $this->arena = $arena;
    }
    
    //boss (1/2)
    public function spawnBoss($boss){
        $pos = $this->arena->data['bosses'][$boss]['pos'];
        $name = $this->arena->data['bosses'][$boss]['name'];
        $chunk = $pos->level->getChunk($pos->x >> 4, $pos->z >> 4, false);
        $golem = new Zombie($chunk, $this->getNbt($pos, $name));
	$golem->setPosition($pos);
	$golem->spawnToAll();
        $this->arena->data['bosses'][$boss]['ins'] = $golem;
    }
    
    public function getNbt(Position $pos, $name){
    $pos = new Location($pos->x, $pos->y, $pos->z, 0.0, 0.0, $pos->level);
    $nbt = new Compound;
    $nbt->NameTag = new String("name", $name." HP 200/200");
    $nbt->Pos = new Enum("Pos", [
            new Double("", 0),
            new Double("", 0),
            new Double("", 0),
    ]);
    $nbt->Motion = new Enum("Motion", [
            new Double("", 0),
            new Double("", 0),
            new Double("", 0),
    ]);
    $nbt->Rotation = new Enum("Rotation", [
            new Float("", 0),
            new Float("", 0)
    ]);
    $nbt->Health = new Short("Health", 200);
    return $nbt;
    }
    
    public function spawnChest(Position $pos){
        $level = $this->arena->level;
        $tile = $level->getTile($pos);
        if(!$tile instanceof Chest){
            $level->setBlock($pos, Block::get(54, 0));
        }
        $tile->getInventory()->setItem(rand(0, 26), $this->getDrop());
    }
    
    public function getDrop(){
        switch(rand(1, 6)){
            case 1:
                return Item::get(310, 0, 1);
            case 2:
                return Item::get(311, 0, 1);
            case 3:
                return Item::get(312, 0, 1);
            case 4:
                return Item::get(313, 0, 1);
            case 5:
                return Item::get(276, 0, 1);
            case 6:
                return Item::get(261, 0, 1);
        }
    }
    
    public function onBossDeath($boss){
        $this->spawnChest($this->arena->data['bosses'][$boss]['chest']);
    }
}