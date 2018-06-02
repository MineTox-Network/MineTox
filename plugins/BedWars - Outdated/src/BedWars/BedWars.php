<?php

namespace BedWars;

use pocketmine\minetox\MTUtility;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\math\Vector3;
use BedWars\Arena\Arena;

class BedWars extends PluginBase
{
    private $arenas = [];

    public function onEnable()
    {
        $this->getServer()->loadLevel("BedWars1");
        $this->getServer()->loadLevel("BedWars2");

        $this->registerArena(1, [4, 2], 4, 1000, -1274, 88, -504);
        $this->registerArena(2, [4, 3], 1, 1000, 344, 91, 24);

        $this->arenas[1]->setArenaData("creator", "Julnico");
        $this->arenas[1]->setArenaData("name", "BedWars1");
        $this->arenas[1]->setArenaData("displayname", "Rustic 4x2");
        $this->arenas[1]->setArenaData("max_distance", 0);

        $this->arenas[1]->setArenaData("Team1", new Vector3(-1266, 100, -851)); //Rot
        $this->arenas[1]->setArenaData("Team2", new Vector3(-1266, 100, -977)); //Blau
        $this->arenas[1]->setArenaData("Team3", new Vector3(-1203, 100, -914)); //Grün
        $this->arenas[1]->setArenaData("Team4", new Vector3(-1329, 100, -914)); //Gelb


        $this->arenas[2]->setArenaData("creator", "Maxiiineeee");
        $this->arenas[2]->setArenaData("name", "BedWars2");
        $this->arenas[2]->setArenaData("displayname", "Futuristic 4x3");
        $this->arenas[2]->setArenaData("max_distance", 10);

        $this->arenas[2]->setArenaData("Team1", new Vector3(353, 39, 442)); //Rot
        $this->arenas[2]->setArenaData("Team2", new Vector3(353, 39, 634)); //Blau
        $this->arenas[2]->setArenaData("Team3", new Vector3(257, 39, 538)); //Grün
        $this->arenas[2]->setArenaData("Team4", new Vector3(449, 39, 538)); //Gelb
    }

    public function registerArena($id, $data, $players, $time, $x, $y, $z, $build = false)
    {
        $level = $this->getServer()->getLevelByName("BedWars".$id);
        $this->arenas[$id] = new Arena($id, $this, $data);
        $this->arenas[$id]->setArenaData("required_Players", $players);
        $this->arenas[$id]->setArenaData("level", $level);
        $this->arenas[$id]->setArenaData("x", $x);
        $this->arenas[$id]->setArenaData("y", $y);
        $this->arenas[$id]->setArenaData("z", $z);
        $level->setAutoSave($build);
        $level->setTime($time);
        $level->stopTime();

        MTUtility::getSMOfCore()->addServer($this->arenas[$id]);
    }

    public function getPlayer($target)
    {
        return null;
    }

    public function sendStats(Player $player, $target)
    {
        $data = $this->getPlayer($target);
        if($data != null)
        {
            $player->sendMessage("==============================================");
            $player->sendMessage(">> BedWars Statistiken für ".$target);
            $player->sendMessage(">> Siege: ".$data["siege"]);
            $player->sendMessage(">> Kills: ".$data["kills"]);
            $player->sendMessage(">> Tode: ".$data["tode"]);
            $player->sendMessage(">> K/D: ".$data["kills"]/$data["tode"]);
            $player->sendMessage("==============================================");
        }
        else
        {
            $player->sendMessage("==============================================");
            $player->sendMessage(">> BedWars Statistiken für ".$target);
            $player->sendMessage(">> Siege: -");
            $player->sendMessage(">> Kills: -");
            $player->sendMessage(">> Tode: -");
            $player->sendMessage(">> K/D: -");
            $player->sendMessage("==============================================");
        }
    }


    /**
     * @return Arena[]
     */
    public function getArenas()
    {
        return $this->arenas;
    }
}