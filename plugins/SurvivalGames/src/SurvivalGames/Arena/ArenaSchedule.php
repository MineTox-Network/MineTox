<?php

/*
*  _____                              __  __   _                  _
* |_   _|   ___    __ _   _ __ ___   |  \/  | (_)  _ __     ___  | |_    ___   __  __
*   | |    / _ \  / _` | | '_ ` _ \  | |\/| | | | | '_ \   / _ \ | __|  / _ \  \ \/ /
*   | |   |  __/ | (_| | | | | | | | | |  | | | | | | | | |  __/ | |_  | (_) |  >  <
*   |_|    \___|  \__,_| |_| |_| |_| |_|  |_| |_| |_| |_|  \___|  \__|  \___/  /_/\_\
*
*/


namespace SurvivalGames\Arena;

use pocketmine\scheduler\Task;

class ArenaSchedule extends Task
{
    private $main;

    public function __construct(Arena $sg)
    {
        $this->main = $sg;
    }

    public function onRun($currentTick)
    {
        $arena = $this->main;
        --$arena->seconds;
        switch($arena->seconds)
        {
            case 1490:
                $arena->MessageAll("Die Runde beginnt in §e60 §3Sekunden");
                break;
            case 1450:
                $map = $arena->getWholeMapData();
                $arena->MessageAll("Die Runde beginnt in §e20 §3Sekunden");
                $arena->MessageAll("§eMap: §b" . $map["displayname"] . " §evon §b" . $map["creator"]);
                $arena->MessageAll("§eSpieler online: §b" . $arena->getPlayerCount() . "/" . $arena->getMaxPlayers());
                break;
            case 1440:
                $map = $arena->getWholeMapData();
                if($arena->getPlayerCount() >= $map["required_Players"])
                {
                    $arena->isPreparing = true;
                    $arena->MessageAll("Die Runde beginnt in §e10 §3Sekunden");
                    $arena->MessageAll("§eMap: §b" . $map["displayname"] . " §evon §b" . $map["creator"]);
                    $arena->MessageAll("§eSpieler online: §b" . $arena->getPlayerCount() . "/" . $arena->getMaxPlayers());
                }
                else
                {
                    $arena->MessageAll("§4Es sind zu wenige Spieler online");
                    $arena->MessageAll("§4Der Countdown wurde zurückgesetzt!");
                    $arena->seconds += 51;
                }
                break;
            case 1435:
                $arena->MessageAll("Die Runde beginnt in §e5 §3Sekunden");
                break;
            case 1434:
                $arena->MessageAll("Die Runde beginnt in §e4 §3Sekunden");
                break;
            case 1433:
                $arena->MessageAll("Die Runde beginnt in §e3 §3Sekunden");
                break;
            case 1432:
                $arena->MessageAll("Die Runde beginnt in §e2 §3Sekunden");
                break;
            case 1431:
                $arena->MessageAll("Die Runde beginnt in §e1 §3Sekunde");
                break;
            case 1430:
                $arena->MessageAll("§eAlle werden in die Arena teleportiert");
                $arena->startRound();
                break;
            case 1425:
                $arena->MessageAll("Die SurvivalGames beginnen in §e15 §3Sekunden");
                break;
            case 1420:
                $arena->MessageAll("Die Survivalgames beginnen in §e10 §3Sekunden");
                break;
            case 1415:
                $arena->MessageAll("Die SurvivalGames beginnen in §e5 §3Sekunden");
                break;
            case 1414:
                $arena->MessageAll("Die SurvivalGames beginnen in §e4 §3Sekunden");
                break;
            case 1413:
                $arena->MessageAll("Die SurvivalGames beginnen in §e3 §3Sekunden");
                break;
            case 1412:
                $arena->MessageAll("Die SurvivalGames beginnen in §e2 §3Sekunden");
                break;
            case 1411:
                $arena->refillChests();
                $arena->MessageAll("Die SurvivalGames beginnen in §e1 §3Sekunde");
                break;
            case 1410:
                $arena->MessageAll("§eDie Schutzzeit hat begonnen! Sammle Kisten und überlebe");
                break;
            case 1395:
                $arena->enablePVP();
                $arena->MessageAll("Die Schutzzeit ist zuende");
                $arena->MessageAll("Das Deathmatch beginnt in §e20 Minuten");
                break;
            case 1360:
                $arena->refillChests();
                break;
            case 795:
                $arena->MessageAll("Das Deathmatch beginnt in §e10 §3Minutem");
                break;
            case 495:
                $arena->MessageAll("Das Deathmatch beginnt in §e5 §3Minutem");
                break;
            case 315:
                $arena->MessageAll("Das Deathmatch beginnt in §e120 §3Sekunden");
                break;
            case 255:
                $arena->MessageAll("Das Deathmatch beginnt in §e60 §3Sekunden");
                break;
            case 225:
                $arena->MessageAll("Das Deathmatch beginnt in §e30 §3Sekunden");
                break;
            case 215:
                $arena->MessageAll("Das Deathmatch beginnt in §e20 §3Sekunden");
                break;
            case 205:
                $arena->MessageAll("Das Deathmatch beginnt in §e10 §3Sekunden");
                break;
            case 200:
                $arena->MessageAll("Das Deathmatch beginnt in §e5 §3Sekunden");
                break;
            case 199:
                $arena->MessageAll("Das Deathmatch beginnt in §e4 §3Sekunden");
                break;
            case 198:
                $arena->MessageAll("Das Deathmatch beginnt in §e3 §3Sekunden");
                break;
            case 197:
                $arena->MessageAll("Das Deathmatch beginnt in §e2 §3Sekunden");
                break;
            case 196:
                $arena->MessageAll("Das Deathmatch beginnt in §e1 §3Sekunde");
                break;
            case 195:
                $arena->startDeathmatch();
                $arena->MessageAll("§eDas Deathmatch beginnt");
                break;
            case 135:
                $arena->MessageAll("Die Runde endet in §e120 §3Sekunden");
                break;
            case 75:
                $arena->MessageAll("Die Runde endet in §e60 §3Sekunden");
                break;
            case 45:
                $arena->MessageAll("Die Runde endet in §e30 §3Sekunden");
                break;
            case 35:
                $arena->MessageAll("Die Runde endet in §e20 §3Sekunden");
                break;
            case 30:
                $arena->MessageAll("Die Runde endet in §e15 §3Sekunden");
                break;
            case 25:
                $arena->MessageAll("Die Runde endet in §e10 §3Sekunden");
                break;
            case 20:
                $arena->MessageAll("Die Runde endet in §e5 §3Sekunden");
                break;
            case 19:
                $arena->MessageAll("Die Runde endet in §e4 §3Sekunden");
                break;
            case 18:
                $arena->MessageAll("Die Runde endet in §e3 §3Sekunden");
                break;
            case 17:
                $arena->MessageAll("Die Runde endet in §e2 §3Sekunden");
                break;
            case 16:
                $arena->MessageAll("Die Runde endet in §e1 §3Sekunden");
                break;
            case 15:
                $arena->setState(3);
                $arena->announceWinner();
                $arena->disablePVP();
                $arena->MessageAll("§cDer Server startet in §e15 §cSekunden neu");
                break;
            case 10:
                $arena->MessageAll("§c4Der Server startet in §e10 §cSekunden neu");
                break;
            case 5:
                $arena->MessageAll("§cDer Server startet in §e5 §cSekunden neu");
                break;
            case 4:
                $arena->MessageAll("§cDer Server startet in §e4 §cSekunden neu");
                break;
            case 3:
                $arena->MessageAll("§cDer Server startet in §e3 §cSekunden neu");
                break;
            case 2:
                $arena->MessageAll("§cDer Server startet in §e2 §cSekunden neu");
                break;
            case 1:
                $arena->MessageAll("§cDer Server startet in §e1 §cSekunde neu");
                break;
            case 0:
                $arena->MessageAll("§cDer Server startet neu!");
                $arena->resetServer(true);
                break;
        }
    }
}