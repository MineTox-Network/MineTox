<?php
namespace Annihilation\Arena;

use Annihilation\Arena\Arena;
use pocketmine\Player;

class VotingManager{
    public $plugin;
    
    public $players = [];
    
    public $allVotes = ['canyon', 'canyon', 'canyon', 'canyon'];
    public $currentTable = [];
    
    public function __construct(Arena $plugin) {
        $this->plugin = $plugin;
    }
    
    public function createVoteTable(){
        $this->currentTable = array_rand($this->allVotes, 3);
        $this->currentTable['stats'] = [1 => 0, 2 => 0, 3 => 0];
    }
    
    public function onVote(Player $p, $vote){
        if($this->plugin->running === true || $this->plugin->phase !== 0){
            return;
        }
        if(is_integer($vote)){
            if((!intval($vote) >=1 || !intval($vote) <= 3)){
                $p->sendMessage(TextFormat::GOLD."[Annihilation] ".TextFormat::GRAY."use /vote [map]");
                return;
            }
            if(isset($this->players[strtolower($p->getName())])){
                $this->currentTable['stats'][$this->players[strtolower($p->getName())]]--;
            }
            $this->currentTable['stats'][intval($vote)]++;
            $this->players[strtolower($p->getName())] = intval($vote);
            return;
        }
        if(is_string($vote)){
            if($vote !== $this->currentTable[0] && $vote !== $this->currentTable[1] && $vote !== $this->currentTable[2]){
                $p->sendMessage(TextFormat::GOLD."[Annihilation] ".TextFormat::GRAY."use /vote [map]");
                return;
            }
            if(isset($this->players[strtolower($p->getName())])){
                $this->currentTable['stats'][$this->players[strtolower($p->getName())]]--;
            }
            $final = str_replace([$this->currentTable[0], $this->currentTable[1], $this->currentTable[2]], [1, 2, 3], $vote);
            $this->currentTable['stats'][$final]++;
            $this->players[strtolower($p->getName())] = $final;
            return;
        }
    }
}


