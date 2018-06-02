<?php

namespace Annihilation\Arena;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\math\Vector3;
use pocketmine\tile\Sign;
use Annihilation\Arena\Arena;
use pocketmine\block\Block;

class ArenaSchedule extends Task{
    
    public $api;
    private $time = 0;
    private $time1 = 120;
    private $popup = 0;
    
    public $pool = [];
    public $cobble = [];
    
    public function __construct(Arena $plugin) {
		$this->api = $plugin;
	}
    
    public function onRun($currentTick){
        if($this->popup == 0 || $this->popup == 2){
            while($this->next($currentTick) === true){
                $block = $this->unhashBlock(array_shift($this->pool));
            }
        }
        if($this->popup === 0){
            $this->setJoinSigns();
        }
        $this->popup++;
        
        if($this->popup === 4){ 
            $this->popup = 0;
        }
        if($this->api->phase === 0){
            $this->sendVotes();
        }
        if($this->api->starting == true){
            $this->starting();
        }
            
        if($this->api->phase >= 1){
            $this->running();
            $this->sendTeamsStats();
        }
            
        if($this->api->ending == true){
            $this->ending();
        }
    }
    
    public function onCancel(){ 
        foreach($this->pool as $string){
            $block = $this->unhashString($string);
            if($block->isValid()){
                $block->getLevel()->setBlock($block, $block); 
            }
        }
    }
    public function push(Block $block){
        switch($block->getId()){
            case 14:
                $restoreTick = $this->getOwner()->getServer()->getTick() + 600; 
                array_push($this->pool, "$restoreTick:$block->x:$block->y:$block->z:{$block->getId()}:{$block->getDamage()}:{$block->getLevel()->getName()}");
                //$block->getLevel()->setBlock(new Vector3($block->x, $block->y, $block->z), Block::get(4, 0));
                break;
            case 15:
                $restoreTick = $this->getOwner()->getServer()->getTick() + 400; 
                array_push($this->pool, "$restoreTick:$block->x:$block->y:$block->z:{$block->getId()}:{$block->getDamage()}:{$block->getLevel()->getName()}");
                //$block->getLevel()->setBlock(new Vector3($block->x, $block->y, $block->z), Block::get(4, 0));
                break;
            case 16:
                $restoreTick = $this->getOwner()->getServer()->getTick() + 200; 
                array_push($this->pool, "$restoreTick:$block->x:$block->y:$block->z:{$block->getId()}:{$block->getDamage()}:{$block->getLevel()->getName()}");
                //$block->getLevel()->setBlock(new Vector3($block->x, $block->y, $block->z), Block::get(4, 0));
                break;
            case 21:
                $restoreTick = $this->getOwner()->getServer()->getTick() + 600; 
                array_push($this->pool, "$restoreTick:$block->x:$block->y:$block->z:{$block->getId()}:{$block->getDamage()}:{$block->getLevel()->getName()}");
                //$block->getLevel()->setBlock(new Vector3($block->x, $block->y, $block->z), Block::get(4, 0));
                break;
            case 56:
                $restoreTick = $this->getOwner()->getServer()->getTick() + 600; 
                array_push($this->pool, "$restoreTick:$block->x:$block->y:$block->z:{$block->getId()}:{$block->getDamage()}:{$block->getLevel()->getName()}");
                //$block->getLevel()->setBlock(new Vector3($block->x, $block->y, $block->z), Block::get(4, 0));
                break;
            case 73:
                $restoreTick = $this->getOwner()->getServer()->getTick() + 600; 
                array_push($this->pool, "$restoreTick:$block->x:$block->y:$block->z:{$block->getId()}:{$block->getDamage()}:{$block->getLevel()->getName()}");
                //$block->getLevel()->setBlock(new Vector3($block->x, $block->y, $block->z), Block::get(4, 0));
                break;
            case 74:
                $restoreTick = $this->getOwner()->getServer()->getTick() + 600; 
                array_push($this->pool, "$restoreTick:$block->x:$block->y:$block->z:{$block->getId()}:{$block->getDamage()}:{$block->getLevel()->getName()}");
                //$block->getLevel()->setBlock(new Vector3($block->x, $block->y, $block->z), Block::get(4, 0));
                break;
            case 129:
                $restoreTick = $this->getOwner()->getServer()->getTick() + 1200; 
                array_push($this->pool, "$restoreTick:$block->x:$block->y:$block->z:{$block->getId()}:{$block->getDamage()}:{$block->getLevel()->getName()}");
                //$block->getLevel()->setBlock(new Vector3($block->x, $block->y, $block->z), Block::get(4, 0));
                break;
            case 13:
                $restoreTick = $this->getOwner()->getServer()->getTick() + 200; 
                array_push($this->pool, "$restoreTick:$block->x:$block->y:$block->z:{$block->getId()}:{$block->getDamage()}:{$block->getLevel()->getName()}");
                break;
            case 17:
                $restoreTick = $this->getOwner()->getServer()->getTick() + 300; 
                array_push($this->pool, "$restoreTick:$block->x:$block->y:$block->z:{$block->getId()}:{$block->getDamage()}:{$block->getLevel()->getName()}");
                break;
            case 162:
                $restoreTick = $this->getOwner()->getServer()->getTick() + 300; 
                array_push($this->pool, "$restoreTick:$block->x:$block->y:$block->z:{$block->getId()}:{$block->getDamage()}:{$block->getLevel()->getName()}");
                break;
            case 103:
                $restoreTick = $this->getOwner()->getServer()->getTick() + 300; 
                array_push($this->pool, "$restoreTick:$block->x:$block->y:$block->z:{$block->getId()}:{$block->getDamage()}:{$block->getLevel()->getName()}");
                break;
        }
    }
    public function next($currentTick){
        if(isset($this->pool[0])){
            $nextTick = (int) explode(':', $this->pool[0])[0];
            if($nextTick <= $currentTick){
                return true;
            }
        }
        return false;
    }
    
    private function unhashBlock($string){
        list($tick, $x, $y, $z, $id, $damage, $lvName) = explode(":", $string);
        $this->getOwner()->getServer()->getLevelByName($lvName)->setBlock(new Vector3($x, $y, $z), Block::get($id, $damage));
    }
    
    public function getOwner(){
        return $this->api->plugin;
    }
    
    public function sendVotes(){
        foreach($this->api->arenateams->getAllPlayers() as $p){
            $vm = $this->api->votingManager;
            $votes = [$vm->allVotes[$vm->currentTable[0]], $vm->allVotes[$vm->currentTable[1]], $vm->allVotes[$vm->currentTable[2]]];
            $p->sendTip("                                                                                          §8Voting §f| §6/vote <name>"
                    . "\n                                                                                        §b[1] §8$votes[0] §c» §a{$vm->currentTable['stats'][1]} Votes"
                    . "\n                                                                                        §b[2] §8$votes[1] §c» §a{$vm->currentTable['stats'][2]} Votes"
                    . "\n                                                                                        §b[3] §8$votes[2] §c» §a{$vm->currentTable['stats'][3]} Votes");
        }
    }

    public function setJoinSigns(){
        $lobby = $this->api->getServer()->getLevelByName("anni_lobby");
        $signb = $lobby->getTile(new Vector3(126, 48, 108));
        $signr = $lobby->getTile(new Vector3(126, 48, 145));
        $signy = $lobby->getTile(new Vector3(145, 48, 127));
        $signg = $lobby->getTile(new Vector3(108, 48, 127));
        
        if($signb instanceof Sign){
            $signb->setText("", TextFormat::DARK_BLUE."[BLUE]",TextFormat::GRAY.count($this->api->arenateams->getTeamPlayers(1)).TextFormat::GRAY." players", "");
        }
        if($signr instanceof Sign){
            $signr->setText("", TextFormat::DARK_RED."[RED]",TextFormat::GRAY.count($this->api->arenateams->getTeamPlayers(2)).TextFormat::GRAY." players", "");
        }
        if($signy instanceof Sign){
            $signy->setText("", TextFormat::YELLOW."[YELLOW]",TextFormat::GRAY.count($this->api->arenateams->getTeamPlayers(3)).TextFormat::GRAY." players", "");
        }
        if($signg instanceof Sign){
            $signg->setText("", TextFormat::DARK_GREEN."[GREEN]",TextFormat::GRAY.count($this->api->arenateams->getTeamPlayers(4).TextFormat::GRAY." players"), "");
        }
    }
    
    public function starting(){
            foreach($this->api->arenateams->getAllPlayers() as $p){
                $p->sendPopup(TextFormat::BOLD.TextFormat::DARK_PURPLE.$this->time1);
            }
            $this->time1--;
            if($this->time1 === 5){
            }
            if($this->time1 === 0){
                $this->api->starting = false;
                $this->api->startRound();
                $this->time1 = 30;
            }
    }
    
    public function running(){
        //$this->api->checkAlive();
        $this->time++;
        if($this->time == 1200){
            $this->api->changePhase(2);
        }
        if($this->time == 2400){
           $this->api->changePhase(3);
        }
        if($this->time == 3600){
            $this->api->changePhase(4);
        }
        if($this->time == 4800){
            $this->api->changePhase(5);
        }
        if($this->time == 10800){
            $this->api->ending = true;
        }
    }
    
    public function ending(){
        foreach($this->api->arenaTeams->getAllPlayers() as $p){
            $p->sendTip(TextFormat::BOLD.TextFormat::DARK_PURPLE."Restarting\n".TextFormat::PURPLE.$this->time1);
        }
        $this->time1--;
        if($this->time1 == 0){
            $this->api->ending = false;
            $this->api->stopRound();
            $this->time1 = 120;
        }
    }
    
    public function sendTeamsStats(){
        $nex = [$this->api->arenateams->getNexusHp(1), $this->api->arenateams->getNexusHp(2), $this->api->arenateams->getNexusHp(3), $this->api->arenateams->getNexusHp(4)];
        $map = $this->api->map;
        foreach($this->api->arenateams->getAllPlayers() as $p){
            $p->sendTip("                                                                                                 §8Map: §6$map\n"
                      . "                                                                                             §eYellow Nexus  §c$nex[2]\n"
                      . "                                                                                             §cRed Nexus     §c$nex[1]\n"
                      . "                                                                                             §9Blue Nexus    §c$nex[0]\n"
                      . "                                                                                             §aGreen Nexus  §c$nex[3]");
        }
    }
}