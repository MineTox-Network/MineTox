<?php

namespace TTT\Arena;

use pocketmine\scheduler\PluginTask;

class ArenaScheduler extends PluginTask
{

    public function onRun($currentTick)
    {
        foreach($this->getOwner()->getArenas() as $arena)
        {
            --$arena->second;
            switch($arena->seconds)
            {
                case 920:
                    $map = $arena->getWholeMapData();
                    $arena->MessageAll("Die Runde beginnt in ".($arena->second - 900)." Sekunden!");
                    $arena->MessageAll("Map: ".$map["name"]." von ".$map["creator"]);
                    $arena->MessageAll("Spieler online: ".$arena->getPlayerCount()."/".$arena->getMaxPlayers());
                    break;
                case 910:
                    $map = $arena->getWholeMapData();
                    if($arena->getPlayerCount() > $map["required_Players"])
                    {
                        $arena->MessageAll("Die Runde beginnt in 10 Sekunden!");
                        $arena->MessageAll("Map: " . $map["name"] . " von " . $map["creator"]);
                        $arena->MessageAll("Spieler online: " . $arena->getPlayerCount() . "/" . $arena->getMaxPlayers());
                    }
                    else
                    {
                        $arena->MessageAll("Es sind zu wenige Spieler online");
                        $arena->MessageAll("Der Countdown wurde zurückgesetzt!");
                        $arena->seconds += 51;
                    }
                    break;
                case $arena->second > 900 and $arena->second < 906:
                    $arena->MessageAll("Die Runde beginnt in " . ($arena->second - 900) . " Sekunden");
                    break;
                case 900:
                    foreach($arena->getAllPlayers() as $p)
                    {
                        //Teleport dem here
                        //TODO - On thursday as we promised
                    }
                    break;
                case 870:
                    $arena->assignRoles();
                    break;
                case 10:
                    $arena->setState(3);
                    $arena->MessageAll("Der Server startet in 10 Sekunden neu");
                    break;
                case ($arena->seconds > 1 and $arena->seconds < 6):
                    $arena->MessageAll("Der Server startet in " . ($arena->seconds) . " Sekunden neu");
                    break;
                case 1:
                    $arena->MessageAll("Der Server startet in 1 Sekunde neu");
                    break;
                case 0:
                    $arena->MessageAll("Das Server startet neu!");
                    $arena->started = false;
                    $arena->resetServer();
                    break;
            }
        }
    }

} 