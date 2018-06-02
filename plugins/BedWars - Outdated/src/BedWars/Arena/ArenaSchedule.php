<?php

namespace BedWars\Arena;

use pocketmine\level\Position;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;

class ArenaSchedule extends Task
{

    private $arena;

    public function __construct(Arena $bw)
    {
        $this->arena = $bw;
    }

    public function onRun($tick)
    {
        --$this->arena->second;
        $arena = $this->arena;
        // Calls //

        switch($arena->second)
        {
            case 3670:
                $arena->setState(0);
                $map = $arena->getWholeMapData();
                $arena->MessageAll(TextFormat::GOLD."Die Runde beginnt in ".TextFormat::AQUA."60 ".TextFormat::GOLD."Sekunden");
                $arena->MessageAll(TextFormat::GOLD."Map: ".TextFormat::AQUA.$map["displayname"].TextFormat::GOLD." von ".TextFormat::AQUA.$map["creator"]);
                $arena->MessageAll(TextFormat::GOLD."Spieler online: ".TextFormat::AQUA.$arena->getPlayerCount()."/".$arena->getMaxPlayers());
                break;
            case 3630:
                $map = $arena->getWholeMapData();
                $arena->MessageAll(TextFormat::GOLD."Die Runde beginnt in ".TextFormat::AQUA."20 ".TextFormat::GOLD."Sekunden");
                $arena->MessageAll(TextFormat::GOLD."Map: ".TextFormat::AQUA.$map["displayname"].TextFormat::GOLD." von ".TextFormat::AQUA.$map["creator"]);
                $arena->MessageAll(TextFormat::GOLD."Spieler online: ".TextFormat::AQUA.$arena->getPlayerCount()."/".$arena->getMaxPlayers());
                break;
            case 3620:
                $map = $arena->getWholeMapData();
                if($arena->getPlayerCount() >= $map["required_Players"])
                {
                    $arena->MessageAll(TextFormat::GOLD."Die Runde beginnt in ".TextFormat::AQUA."10 ".TextFormat::GOLD."Sekunden");
                    $arena->MessageAll(TextFormat::GOLD."Map: ".TextFormat::AQUA.$map["displayname"].TextFormat::GOLD." von ".TextFormat::AQUA.$map["creator"]);
                    $arena->MessageAll(TextFormat::GOLD."Spieler online: ".TextFormat::AQUA.$arena->getPlayerCount()."/".$arena->getMaxPlayers());
                }
                else
                {
                    $arena->MessageAll("§cEs sind zu wenige Spieler online");
                    $arena->MessageAll("§cDer Countdown wurde zurückgesetzt!");
                    $arena->seconds += 51;
                }
                break;
            case 3615:
                $arena->MessageAll(TextFormat::GOLD."Die Runde beginnt in ".TextFormat::AQUA."5".TextFormat::GOLD." Sekunden");
                break;
            case 3614:
                $arena->MessageAll(TextFormat::GOLD."Die Runde beginnt in ".TextFormat::AQUA."4".TextFormat::GOLD." Sekunden");
                break;
            case 3613:
                $arena->MessageAll(TextFormat::GOLD."Die Runde beginnt in ".TextFormat::AQUA."3".TextFormat::GOLD." Sekunden");
                break;
            case 3612:
                $arena->MessageAll(TextFormat::GOLD."Die Runde beginnt in ".TextFormat::AQUA."2".TextFormat::GOLD." Sekunden");
                break;
            case 3611:
                $arena->MessageAll(TextFormat::GOLD."Die Runde beginnt in ".TextFormat::AQUA."1".TextFormat::GOLD." Sekunde");
                break;
            case 3610:
                foreach($arena->getAllPlayers() as $p)
                {
                    $p->getInventory()->clearAll();
                    $arena->tpArena($p);
                    $p->despawnFromAll();
                    $p->spawnToAll();
                }
                $arena->setState(1);
                $arena->MessageAll(TextFormat::GREEN."Das Spiel beginnt");
                break;
            case 1800:
                $arena->MessageAll(TextFormat::GREEN."Das Spiel endet in 30 Minuten");
                break;
            case 10:
                $arena->pvp = false;
                $arena->MessageAll("§cDas Spiel ist beendet");
                foreach($arena->getAllPlayers() as $p)
                {
                    $p->teleport(new Position($arena->getMapData("x"), $arena->getMapData("y"), $arena->getMapData("z"), $arena->getMapData("level")));
                }
                $arena->MessageAll(TextFormat::RED."Der Server wird in 10 Sekunden neu gestartet");
                break;
            case 5:
                $arena->MessageAll(TextFormat::RED."Der Server wird in 5 Sekunden neu gestartet");
                break;
            case 4:
                $arena->MessageAll(TextFormat::RED."Der Server wird in 4 Sekunden neu gestartet");
                break;
            case 3:
                $arena->MessageAll(TextFormat::RED."Der Server wird in 3 Sekunden neu gestartet");
                break;
            case 2:
                $arena->MessageAll(TextFormat::RED."Der Server wird in 2 Sekunden neu gestartet");
                break;
            case 1:
                $arena->MessageAll(TextFormat::RED."Der Server wird in 1 Sekunde neu gestartet");
                break;
            case 0:
                $arena->MessageAll(TextFormat::RED."Der Server wird in neu gestartet");
                $arena->resetServer();
                break;
        }
    }

}