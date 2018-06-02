<?php

namespace BedWars\Arena;

use pocketmine\minetox\TeamManager;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ArenaTeams extends TeamManager
{

    /**
     * @var Arena
     */
    private $api;

    public function __construct(Arena $arena, $amount, $perTeam)
    {
        $this->createTeams($amount, $perTeam, []);
        $this->api = $arena;
        foreach($this->getTeams() as $teams)
        {
            $this->setBedAlive($teams, true);
        }
    }

    public function resetTeams()
    {
        $this->__construct($this->api, $this->getTeams()["settings"]["amount"], $this->getTeams()["settings"]["perTeam"]);
    }

    /**
     * @param int $team
     * @return bool
     */
    public function getBedAlive($team)
    {
        return $this->getTeams()[$team]["bett"];
    }

    /**
     * @param int $team
     * @param bool $status
     */
    public function setBedAlive($team, $status = false)
    {
        $this->getTeams()[$team]["bett"] = $status;
    }

    /**
     * @param Player $p
     * @param int $team
     * @return bool
     */
    public function canBreakBed(Player $p, $team)
    {
        if(isset($this->getTeams()[$team][$p->getName()]))
        {
            return false;
        }
        return true;
    }

    /**
     * @param $team
     * @return int
     */
    public function getWoolColor($team)
    {
        switch($this->getChatColor($team))
        {
            case TextFormat::BLUE:
                return 11;
                break;
            case TextFormat::RED:
                return 14;
                break;
            case TextFormat::YELLOW:
                return 4;
                break;
            case TextFormat::GREEN:
                return 5;
                break;
            case TextFormat::BLACK:
                return 15;
                break;
            case TextFormat::GOLD:
                return 1;
                break;
            case TextFormat::LIGHT_PURPLE:
                return 10;
                break;
            case TextFormat::AQUA:
                return 9;
                break;
        }
    }



} 