<?php

namespace AURA\Arena;

use AURA\AURA;
use pocketmine\minetox\MTUtility;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;

class ArenaSchedule extends Task
{

    private $api;

    public function __construct(AURA $plugin)
    {
        $this->api = $plugin;
    }

    public function onRun($currentTick)
    {
        foreach($this->api->arenas as $arena)
        {
            if($arena instanceof Arena)
            {
                /* Function calls */

                $arena->getWinner();

                /* Schedule */

                switch($arena->second)
                {
                    case 640:
                        $arena->MessageAll(TextFormat::GOLD."Das Spiel beginnt in ".TextFormat::AQUA."40".TextFormat::GOLD." Sekunden");
                        break;
                    case 620:
                        $arena->MessageAll(TextFormat::GOLD."Das Spiel beginnt in ".TextFormat::AQUA."20".TextFormat::GOLD." Sekunden");
                        break;
                    case 610:
                        if($arena->getPlayerCount() > 1) //May require 6 here
                        {
                            $arena->MessageAll(TextFormat::GOLD."Das Spiel beginnt in ".TextFormat::AQUA."10".TextFormat::GOLD." Sekunden");
                        }
                        else
                        {
                            $arena->second = 661;
                            $arena->MessageAll("§cEs sind zu wenig Spieler online");
                        }
                        break;
                    case 605:
                        $arena->MessageAll(TextFormat::GOLD."Das Spiel beginnt in ".TextFormat::AQUA."5".TextFormat::GOLD." Sekunden");
                        break;
                    case 604:
                        $arena->MessageAll(TextFormat::GOLD."Das Spiel beginnt in ".TextFormat::AQUA."4".TextFormat::GOLD." Sekunden");
                        break;
                    case 603:
                        $arena->MessageAll(TextFormat::GOLD."Das Spiel beginnt in ".TextFormat::AQUA."3".TextFormat::GOLD." Sekunden");
                        break;
                    case 602:
                        $arena->MessageAll(TextFormat::GOLD."Das Spiel beginnt in ".TextFormat::AQUA."2".TextFormat::GOLD." Sekunden");
                        break;
                    case 601:
                        $arena->MessageAll(TextFormat::GOLD."Das Spiel beginnt in ".TextFormat::AQUA."1".TextFormat::GOLD." Sekunden");
                        break;
                    case 600:
                        $arena->setState(1);
                        $arena->MessageAll(TextFormat::GOLD."Das Spiel beginnt");
                        $arena->MessageAll(TextFormat::GOLD."Du hast ".TextFormat::AQUA."30 Sekunden ".TextFormat::GOLD." Schutzzeit");
                        foreach($arena->getAllPlayers() as $player)
                        {
                            $player->teleport($arena->getMapData("inGameSpawn"));
                        }
                        $arena->equipPlayers();
                        break;
                    case 570:
                        $arena->MessageAll(TextFormat::GOLD."Die Schutzzeit ist beendet");
                        break;
                    case 70:
                        $arena->MessageAll(TextFormat::GOLD."Das Spiel endet in 1 Minute");
                        break;
                    case 10:
                        $arena->MessageAll("§cDas Spiel ist beendet!");
                        if($arena->winner instanceof Player)
                        {
                            MTUtility::unNickPlayer($arena->winner);
                            $arena->MessageAll(TextFormat::GOLD."Der Gewinner ist ".TextFormat::BLUE.$arena->winner->getName());
                        }
                        else
                        {
                            $arena->MessageAll("§cEs gibt keinen Gewinner");
                        }
                        $arena->MessageAll("§cDer Server startet in 10 Sekunden neu");
                        break;
                    case 3:
                        $arena->MessageAll("§cDer Server startet in 3 Sekunden neu");
                        break;
                    case 2:
                        $arena->MessageAll("§cDer Server startet in 2 Sekunden neu");
                        break;
                    case 1:
                        $arena->MessageAll("§cDer Server startet in 1 Sekunde neu");
                        break;
                    case 0:
                        $arena->MessageAll("§cDer Server startet neu");
                        $arena->resetServer();
                        break;
                }
            }
        }
    }

} 