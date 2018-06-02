<?php

namespace AURA;

use AURA\Arena\Arena;
use AURA\Arena\ArenaSchedule;
use pocketmine\level\Position;
use pocketmine\plugin\PluginBase;

class AURA extends PluginBase
{

    public $arenas = [];

    public function onEnable()
    {
        $this->addNewArena(1);
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new ArenaSchedule($this), 20);
    }

    public function addNewArena($id)
    {
        $this->arenas[$id] = new Arena($id, $this);
        $arena = $this->getArena($id);
        $this->getServer()->loadLevel("AURA1");
        $level = $this->getServer()->getLevelByName("AURA1");
        $arena->setArenaData("level", $level);
        $arena->setArenaData("name", "AURA".$id);
        $arena->setArenaData("display_name", "AURA");
        $arena->setArenaData("x", 60);
        $arena->setArenaData("y", 50);
        $arena->setArenaData("z", -642);
        $arena->setArenaData("inGameSpawn", new Position(5, 66, -11, $level));
        $this->getServer()->getPluginManager()->registerEvents($arena, $this);
        $mnger = $this->getServer()->getPluginManager()->getPlugin("MTCore");
        $mnger->getServerManager()->addServer($arena);
    }

    /**
     * @param $id
     * @return Arena
     */
    public function getArena($id)
    {
        return $this->arenas[$id];
    }

} 