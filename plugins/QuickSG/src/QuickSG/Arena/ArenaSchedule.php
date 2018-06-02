<?php

/*
*  _____                              __  __   _                  _
* |_   _|   ___    __ _   _ __ ___   |  \/  | (_)  _ __     ___  | |_    ___   __  __
*   | |    / _ \  / _` | | '_ ` _ \  | |\/| | | | | '_ \   / _ \ | __|  / _ \  \ \/ /
*   | |   |  __/ | (_| | | | | | | | | |  | | | | | | | | |  __/ | |_  | (_) |  >  <
*   |_|    \___|  \__,_| |_| |_| |_| |_|  |_| |_| |_| |_|  \___|  \__|  \___/  /_/\_\
*
*/


namespace QuickSG\Arena;

use pocketmine\minetox\MTUtility;
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
        $seconds = $arena->seconds;
        switch($seconds)
        {
            case $seconds > 400:
                $arena->sendPopup("§e".($seconds - 381));
                break;
            case 400:
                $map = $arena->getWholeMapData();
                $arena->MessageAll("Die Runde beginnt in §e20 §3Sekunden");
                $arena->MessageAll("§eMap: §b" . $map["displayname"] . " §evon §b" . $map["creator"]);
                $arena->MessageAll("§eSpieler online: §b" . $arena->getPlayerCount() . "/" . $arena->getMaxPlayers());
                break;
            case 390:
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
            case $seconds > 380:
                $arena->sendPopup("§e".($seconds - 381));
                break;
            case 380:
                $arena->MessageAll("§eAlle werden in die Arena teleportiert");
                $arena->sendPopup("§3Die Spiele beginnen in §e10 §3Sekunden");
                $arena->startRound();
                break;
            case $seconds > 370:
                $arena->sendPopup("§3Die Runde beginnt in §e".($arena->seconds - 371)." §3Sekunden");
                break;
            case 370:
                $arena->startGame();
                $arena->sendPopup("");
                $arena->MessageAll("§eDie Spiele beginnen!");
                $arena->MessageAll("§eSammle Kisten und überlebe!");
                break;
            case 340:
                $arena->refillChests();
                break;
            case 310:
                $arena->refillChests();
                break;
            case 250:
                $arena->MessageAll("Das Deathmatch startet in §e2 §3Minuten");
                break;
            case 190:
                $arena->MessageAll("Das Deathmatch startet in §e60 §3Sekunden");
                break;
            case 150:
                $arena->MessageAll("Das Deathmatch startet in §e20 §3Sekunden");
                break;
            case 140:
                $arena->MessageAll("Das Deathmatch startet in §e10 §3Sekunden");
                break;
            case 135:
                $arena->MessageAll("Das Deathmatch startet in §e5 §3Sekunden");
                break;
            case 134:
                $arena->MessageAll("Das Deathmatch startet in §e4 §3Sekunden");
                break;
            case 133:
                $arena->MessageAll("Das Deathmatch startet in §e3 §3Sekunden");
                break;
            case 132:
                $arena->MessageAll("Das Deathmatch startet in §e2 §3Sekunden");
                break;
            case 131:
                $arena->MessageAll("Das Deathmatch startet in §e1 §3Sekunde");
                break;
            case 130:
                $arena->startDeathmatch();
                $arena->MessageAll("§eDas Deathmatch beginnt");
                break;
            case 70:
                $arena->MessageAll("Das Deathmatch endet in §e60 §3Sekunden");
                break;
            case 30:
                $arena->MessageAll("Das Deathmatch endet in §e20 §3Sekunden");
                break;
            case 20:
                $arena->MessageAll("Das Deathmatch endet in §e10 §3Sekunden");
                break;
            case 10:
                $arena->setState(3);
                $arena->disablePVP();
                $arena->announceWinner();
                $arena->broadcastMessage("§7=======================================");
                $arena->MessageAll("§eDie SurvivalGames sind beendet");
                $winner = $arena->getWinner();
                if(!$arena->winner)
                {
                    $arena->MessageAll("Niemand konnte sich als Sieger erweisen");
                }
                else
                {
                    $arena->MessageAll($winner->getName() . " hat die Spiele gewonnen");
                }
                foreach($arena->getAllPlayers() as $player)
                {
                    if($player->isNicked )
                    {
                        $arena->MessageAll($winner->getName() . " hat die Spiele gewonnen");
                        if($winner->isNicked )
                        {
                            MTUtility::unNickPlayer($winner);
                        }
                    }
                }
                $arena->broadcastMessage("§7=======================================");
                $arena->MessageAll("§cDer Server startet in §e10 §cSekunden neu");
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