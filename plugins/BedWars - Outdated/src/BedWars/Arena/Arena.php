<?php

namespace BedWars\Arena;

use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\minetox\MTMinigame;
use pocketmine\minetox\MTServer;
use pocketmine\event\Listener;
use BedWars\BedWars;
use pocketmine\minetox\PlayerEvents;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

/**
 * Class Arena
 * @package BedWars\Arena
 * Sulfatezz: This is our "Listener"
 */
class Arena extends MTServer implements Listener, MTMinigame, PlayerEvents
{
	
    public $seconds = 3675;
    public $teams = [];
    public $api;
    public $id;

    public function __construct($id, BedWars $plugin, $data)
    {
        $this->second = 3675; //1 Stunde 1 Minute 15 Sekunden
        $this->setType(2);
        $this->api = $plugin;
        $this->id = $id;
        $this->setState(0);
        $this->setGameName("BedWars");
        $this->setName("-BedWars".$id."-");
        $this->setMaxPlayers(($data[0] * $data[1]));
    }

    /**
     * @return ArenaTeams
     */
    public function getTeamManager()
    {
        return $this->teams;
    }

    public function startRound()
    {

    }

    public function stopRound()
    {

    }

    public function boostTimer()
    {

    }

    public function onLeave(Player $p)
    {
        $this->getTeamManager()->removeFromTeam($p, $p->team);
    }

    public function onJoin(Player $p)
    {
        $this->getTeamManager()->addToTeam($p, $this->getTeamManager()->getFreeTeam(), true);
        $this->announceTeamSwitch($p);
    }

    public function onNickChange(Player $player)
    {
        $player->setNameTag($this->getTeamManager()->getChatColor($player->team).$player->getDisplayName());
    }

    public function announceTeamSwitch(Player $player)
    {
        $player->sendMessage(TextFormat::GREEN."Du bist in Team ".$this->getTeamManager()->getChatColor($player->team).$this->getTeamManager()->getTeamName($player->team));
    }

    public function equipSelection(Player $p)
    {
        foreach($this->getTeamManager()->getTeams() as $team)
        {
            $p->getInventory()->addItem(new Item(Item::WOOL, $this->teams->getWoolColor($team)));
        }
    }

    /* API */
    public function stopGame()
    {
        $this->second = 11;
    }

    public function startGame()
    {
        $this->second = 3621;
    }

    public function onRestart()
    {
        $this->second = 3675;
        $this->winner = false;
        $this->wmsg = false;
        $this->pvp = false;
        $this->getTeamManager()->resetTeams();
    }

    public function isPreparing()
    {
        if ($this->seconds < 3620)
        {
            return true;
        }
        return false;
    }

    public function checkAlive()
    {
        if($this->getPlayerCount() <= 0)
        {
            return true;
        }
        return false;
    }

    public function getOwner()
    {
        return $this->api;
    }

    public function teleportPlayersToArena()
    {
        foreach($this->getAllPlayers() as $player)
        {
            $player->teleport($this->getMapData("Team".$player->team));
        }
    }

    public function teleportPlayersToLobby()
    {
        foreach($this->getAllPlayers() as $p)
        {
            $p->teleport(new Position($this->getMapData("x"), $this->getMapData("y"), $this->getMapData("z"), $this->getMapData("level")));
        }
    }

    public function enablePVP()
    {
        $this->pvp = true;
    }

    public function disablePVP()
    {
        $this->pvp = false;
    }

    public function isPVPAllowed()
    {
        return $this->pvp;
    }
}