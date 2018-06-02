<?php

namespace Annihilation\Arena;

use pocketmine\Player;
use pocketmine\utils\TextFormat;
use Annihilation\Arena\Arena;

class ArenaTeams{
    private $plugin;
    public $teams = [];
    
    public function __construct(Arena $arena)
    {
        $this->createTeams();
        $this->plugin = $arena;
    }
    
    public function getNexusHp($team)
    {
        return $this->teams[$team]["nexus"];
    }
    
    public function setNexusHp($team, $hp)
    {
        return $this->teams[$team]["nexus"] = $hp;
    }
    
    public function getWinningTeam(){
        if($this->getNexusHp(1) > $this->getNexusHp(2) && $this->getNexusHp(1) > $this->getNexusHp(3) && $this->getNexusHp(1) > $this->getNexusHp(4)){
            return 1; //returns team id
        }
        if($this->getNexusHp(2) > $this->getNexusHp(1) && $this->getNexusHp(2) > $this->getNexusHp(3) && $this->getNexusHp(2) > $this->getNexusHp(4)){
            return 2; //returns team id
        }
        if($this->getNexusHp(3) > $this->getNexusHp(1) && $this->getNexusHp(3) > $this->getNexusHp(2) && $this->getNexusHp(3) > $this->getNexusHp(4)){
            return 3; //returns team id
        }
        if($this->getNexusHp(4) > $this->getNexusHp(2) && $this->getNexusHp(4) > $this->getNexusHp(3) && $this->getNexusHp(4) > $this->getNexusHp(1)){
            return 4; //returns team id
        }
    }
    
    public function createTeams(){
        $this->teams = [0 => ['players' => [], 'color' => "§5", 'name' => "lobby"], 1 => ['players' => [], 'nexus' => 75, 'color' => '§9', 'name' => "blue"], 2 => ['players' => [], 'nexus' => 75, 'color' => '§c', 'name' => "red"], 3 => ['players' => [], 'nexus' => 75, 'color' => '§e', 'name' => "yellow"], 4 => ['players' => [], 'nexus' => 75, 'color' => '§a', 'name' => "green"]];
    }
    
    public function getPlayerTeam(Player $player){
        if(isset($this->teams[0]['players'][strtolower($player->getName())])){
            return 0;
        }
        if(isset($this->teams[1]['players'][strtolower($player->getName())])){
            return 1;
        }
        if(isset($this->teams[2]['players'][strtolower($player->getName())])){
            return 2;
        }
        if(isset($this->teams[3]['players'][strtolower($player->getName())])){
            return 3;
        }
        if(isset($this->teams[4]['players'][strtolower($player->getName())])){
            return 4;
        }
        return false;
    }
    
    public function addToTeam(Player $player, $team){
        $this->teams[$team]['players'][strtolower($player->getName())] = $player;
        if($team === 0){
            $player->setDisplayName($this->teams[$team]['color'].$player->getName().TextFormat::WHITE);
            return;
        }
        $player->setNameTag($this->teams[$team]['color'].$player->getName().TextFormat::WHITE);
        $player->setDisplayName($this->teams[$team]['color'].$player->getName().TextFormat::WHITE);
    }
    
    public function getTeamPlayers($team){
        return $this->teams[$team]['players'];
    }
    
    public function removeFromTeam(Player $player, $team){
        if(isset($this->teams[$team]['players'][strtolower($player->getName())])){
            unset($this->teams[$team]['players'][strtolower($player->getName())]);
        }
    }
    
    public function getAllPlayers(){
        return array_merge($this->teams[0]['players'], $this->teams[1]['players'], $this->teams[2]['players'], $this->teams[3]['players'], $this->teams[4]['players']);
    }
    
    public function getAllPlayersInTeam(){
        return array_merge($this->teams[1]['players'], $this->teams[2]['players'], $this->teams[3]['players'], $this->teams[4]['players']);
    }
    
    public function isTeamFree($team){
        switch($team){
            case 1:
                if(count($this->getTeamPlayers(1)) - min(count($this->getTeamPlayers(2)), count($this->getTeamPlayers(3)), count($this->getTeamPlayers(4))) <= 2){
                    return true;
                }
                break;
            case 2:
                if(count($this->getTeamPlayers(2)) - min(count($this->getTeamPlayers(1)), count($this->getTeamPlayers(3)), count($this->getTeamPlayers(4))) <= 2){
                    return true;
                }
                break;
            case 3:
                if(count($this->getTeamPlayers(3)) - min(count($this->getTeamPlayers(2)), count($this->getTeamPlayers(1)), count($this->getTeamPlayers(4))) <= 2){
                    return true;
                }
                break;
            case 4:
                if((count($this->getTeamPlayers(4)) - min(count($this->getTeamPlayers(2)), count($this->getTeamPlayers(3)), count($this->getTeamPlayers(1)))) <= 2){
                    return true;
                }
                break;
        }
        return false;
    }
    
    public function getTeamName($team){
        return $this->teams[$team]['name'];
    }
    
    public function getTeamColor($team){
        return $this->teams[$team]['color'];
    }
    
    public function messageTeam($message, Player $player = null, $team = null){
        if($player === null){
            foreach($this->teams[$team]['players'] as $p){
                $p->sendMessage($message);
            }
            return;
        }
        foreach($this->teams[$this->getPlayerTeam($player)]['players'] as $p){
            if($player !== null){
                $color = $this->getTeamColor($this->getPlayerTeam($player));
                $p->sendMessage(TextFormat::GRAY."[{$color}Team".TextFormat::GRAY."]   ".$player->getDisplayName().TextFormat::DARK_AQUA." > ".$message);
            }
        }
    }
    
    public function messageAllPlayers($message, Player $player = null){
        foreach($this->getAllPlayers() as $p){
            if($player !== null){
                if($this->getPlayerTeam($player) === 0){
                    $p->sendMessage(TextFormat::GRAY."[".TextFormat::DARK_PURPLE."Lobby".TextFormat::GRAY."]  ".TextFormat::DARK_GRAY.$player->getName().TextFormat::DARK_AQUA." > ".$message);
                    return;
                }
                $color = $this->getTeamColor($this->getPlayerTeam($player));
                $p->sendMessage(TextFormat::GRAY."[{$color}All".TextFormat::GRAY."]   ".$player->getDisplayName().TextFormat::DARK_AQUA." > ".substr($message, 1));
            }
            else{
                $p->sendMessage($message);
            }
        }
    }
}

