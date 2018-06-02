<?php

namespace Annihilation\Arena;

use pocketmine\minetox\MTMinigame;
use pocketmine\minetox\MTServer;
use pocketmine\utils\TextFormat;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\level\Position;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\math\Vector3 as Vector3;
use pocketmine\math\AxisAlignedBB;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\block\Block;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\item\Item;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerBucketFillEvent;
use pocketmine\event\player\PlayerBucketEmptyEvent;
use pocketmine\event\player\PlayerAchievementAwardedEvent;
use pocketmine\level\sound\ClickSound;
use Annihilation\Annihilation;


class Arena extends MTServer implements Listener, MTMinigame{
    public $plugin;
    public $task;
    public $kitManager;
    public $votingManager;
    public $worldManager;
    public $bossManager;
    
    public $phase = 0;
    public $starting = false;
    public $ending = false;
    public $level;
    
    public $players = [];
    
    public $arenateams;
    public $arenaTeams;

    public $data;
    public $maindata;
    
    public $currentVotes = [];
    
    public $map = 'canyon';
    
    public function __construct($id, Annihilation $plugin){
        $this->arenateams = new ArenaTeams($this);
        $this->arenaTeams = $this->arenateams; //or you can rewrite variables to arenateams or to arenaTeams xD
        $this->plugin = $plugin;
        $this->data = $this->plugin->maps['canyon'];
        $this->kitManager = new KitManager($this);
        $this->votingManager = new VotingManager($this);
        $this->worldManager = new WorldManager();
        $this->bossManager = new BossManager($this);
        //$this->data = $this->getMapData($id);
        $this->plugin->getServer()->getScheduler()->scheduleRepeatingTask($this->task = new ArenaSchedule($this), 10);
        $this->maindata['blueJoinSign'] = new Vector3(126, 48, 108);
        $this->maindata['redJoinSign'] = new Vector3(126, 48, 145);
        $this->maindata['yellowJoinSign'] = new Vector3(145, 48, 127);
        $this->maindata['greenJoinSign'] = new Vector3(108, 48, 127);
        $this->maindata["Spawn"] = new Position(127, 47, 127, $this->plugin->getServer()->getLevelByName('anni_lobby'));
        $this->plugin->getServer()->getPluginManager()->registerEvents($this->kitManager, $this->plugin);
        $this->votingManager->createVoteTable();
    }
    
    public function onPlayerJoin(PlayerJoinEvent $e){
        $p = $e->getPlayer();
        if($this->arenateams->getPlayerTeam($p) !== 0 && $this->arenateams->getPlayerTeam($p) !== false){
            $this->teleportToArena($p);
            return;
        }
        $p->teleport($this->maindata["Spawn"]);
        $this->arenateams->addToTeam($p, 0);
        $this->kitManager->addKitWindow($p);
        $this->checkLobby();
    }
    
    public function onPlayerQuit(PlayerQuitEvent $e){
        $this->handlePlayerQuit($e->getPlayer());
    }
    
    public function onKick(PlayerKickEvent $e){
        $this->handlePlayerQuit($e->getPlayer());
    }
    
    public function handlePlayerQuit(Player $p){
        $this->players[strtolower($p->getName())]['team'] = $this->arenateams->getPlayerTeam($p);
        $this->players[strtolower($p->getName())]['inv']['items'] = $p->getInventory()->getContents();
        $this->players[strtolower($p->getName())]['inv']['armor'] = $p->getInventory()->getArmorContents();
        $this->arenateams->removeFromTeam($p, $this->arenateams->getPlayerTeam($p));
        $p->teleportImmediate($this->maindata["Spawn"]);
        $p->getInventory()->clearAll();
        $this->checkAlive();
    }
    
    public function onHurt(EntityDamageEvent $e){
        $entity = $e->getEntity();
            if($e instanceof EntityDamageByEntityEvent){
                if($e->getDamager() instanceof Player && $e->getEntity() instanceof Player){
                    $killer = $e->getDamager();
                    $victim = $e->getEntity();
                    if($this->arenateams->getPlayerTeam($killer) === $this->arenateams->getPlayerTeam($victim) || $this->phase === 0 || $victim->getLevel()->getName() == "anni_lobby"){
                        $e->setCancelled();
                        return;
                    }
                    if($e->getFinalDamage() >= $victim->getHealth()){
                        $this->plugin->addTokens($killer, 10);
                        $this->plugin->addKill($killer);
                        $this->plugin->addDeath($victim);
                    }
                }
            }
    }
    
    public function onBlockBreak(BlockBreakEvent $e){
        $b = $e->getBlock();
        $player = $e->getPlayer();
        if($b->getLevel()->getName() == 'anni_lobby'){
            $e->setCancelled(true);
            return;
        }
        if($this->phase >= 1){
            $blueNex = $this->data["1Nexus"];
            $redNex = $this->data["2Nexus"];
            $yellowNex = $this->data["3Nexus"];
            $greenNex = $this->data["4Nexus"];
            if(round($b->x) == round($blueNex->x) && round($b->y) == round($blueNex->y) && round($b->z) == round($blueNex->z)){
                $e->setCancelled(true);
                $this->breakNexus($player, 1);
                return;
            }
            if(round($b->x) == round($redNex->x) && round($b->y) == round($redNex->y) && round($b->z) == round($redNex->z)){
                $e->setCancelled(true);
                $this->breakNexus($player, 2);
                return;
            }
            if(round($b->x) == round($yellowNex->x) && round($b->y) == round($yellowNex->y) && round($b->z) == round($yellowNex->z)){
                $e->setCancelled(true);
                $this->breakNexus($player, 3);
                return;
            }
            if(round($b->x) == round($greenNex->x) && round($b->y) == round($greenNex->y) && round($b->z) == round($greenNex->z)){
                $e->setCancelled(true);
                $this->breakNexus($player, 4);
                return;
            }
            
            if($this->contains($blueNex->x+10, 128, $blueNex->z+10, $blueNex->x-10, 1, $blueNex->z-10, new Vector3($b->x, $b->y, $b->z)) || $this->contains($redNex->x+10, 128, $redNex->z+10, $redNex->x-10, 1, $redNex->z-10, new Vector3($b->x, $b->y, $b->z)) || $this->contains($yellowNex->x+10, 128, $yellowNex->z+10, $yellowNex->x-10, 1, $yellowNex->z-10, new Vector3($b->x, $b->y, $b->z)) || $this->contains($greenNex->x+10, 128, $greenNex->z+10, $greenNex->x-10, 1, $greenNex->z-10, new Vector3($b->x, $b->y, $b->z))){
                $e->setCancelled();
                $player->sendMessage(TextFormat::RED."You can't destroy blocks near the nexus");
                return;
            }
            
            if($b->getId() === 14 || $b->getId() === 15 || $b->getId() === 16 || $b->getId() === 21 || $b->getId() === 56 || $b->getId() === 73 || $b->getId() === 74 || $b->getId() === 129 || $b->getId() === 17 || $b->getId() === 162){
                    $this->task->push($b);
                    $e->setDrops([]);
                    $player->getInventory()->addItem(Item::get($b->getId(), $b->getDamage(), 1));
                    return;
            }
            
            if($b->getId() === 13){
                $this->task->push($b);
                $e->setDrops([]);
                foreach($this->getGravelDrops() as $item){
                    $player->getInventory()->addItem($item);
                }
                return;
            }
            
            if($b->getId() === 103){
                $this->task->push($b);
                $e->setDrops([]);
                $player->getInventory()->addItem(Item::get(360, 0, rand(3, 7)));
            }
        }
            /*if($b->getId() === 4){
                foreach($this->task->pool as $content){
                    list($tick, $x, $y, $z, $id, $damage, $lvName) = explode(":", $content);
                    if("$x:$y:$z:$lvName" == "$b->x:$b->y:$b->z:{$b->level->getName()}"){
                        $e->setCancelled();
                    }
                }
                return;
            }*/
    }
    
    public function onBlockPlace(BlockPlaceEvent $e){
        $player = $e->getPlayer();
        $b = $e->getBlock();
        if($b->getLevel()->getName() == 'anni_lobby'){
            $e->setCancelled();
            return;
        }
        if($this->phase >= 1){
            if($b->getId() === 14 || $b->getId() === 15 || $b->getId() === 16 || $b->getId() === 21 || $b->getId() === 56 || $b->getId() === 73 || $b->getId() === 74 || $b->getId() === 129 || $b->getId() === 17 || $b->getId() === 162 || $b->getId() === 103 || $b->getId() === 13){
                $e->setCancelled();
                return;
            }
            $blueNex = $this->data["1Nexus"];
            $redNex = $this->data["2Nexus"];
            $yellowNex = $this->data["3Nexus"];
            $greenNex = $this->data["4Nexus"];
            if($this->contains($blueNex->x+10, 128, $blueNex->z+10, $blueNex->x-10, 1, $blueNex->z-10, new Vector3($b->x, $b->y, $b->z)) || $this->contains($redNex->x+10, 128, $redNex->z+10, $redNex->x-10, 1, $redNex->z-10, new Vector3($b->x, $b->y, $b->z)) || $this->contains($yellowNex->x+10, 128, $yellowNex->z+10, $yellowNex->x-10, 1, $yellowNex->z-10, new Vector3($b->x, $b->y, $b->z)) || $this->contains($greenNex->x+10, 128, $greenNex->z+10, $greenNex->x-10, 1, $greenNex->z-10, new Vector3($b->x, $b->y, $b->z))){
                $e->setCancelled();
                $player->sendMessage(TextFormat::RED."You cannot build this close to the nexus!");
            }
        }
    }
    
    public function onBucketFill(PlayerBucketFillEvent $e){
        $p = $e->getPlayer();
        if($p instanceof Player){
            $e->setCancelled();
        }
    }
    
    public function onBucketEmpty(PlayerBucketEmptyEvent $e){
        $p = $e->getPlayer();
        if($p instanceof Player){
            $e->setCancelled();
        }
    }
    
    public function onAchievement(PlayerAchievementAwardedEvent $e){
        $p = $e->getPlayer();
        if($p instanceof Player){
            $e->setCancelled();
        }
    }
    
    public function onDeath(PlayerDeathEvent $e){
        if($e instanceof EntityDamageEvent){
            
        }
    }
    
    public function onRespawn(PlayerRespawnEvent $e){
        $p = $e->getPlayer();
        if($this->arenateams->getPlayerTeam($p) !== 0 && $this->arenaTeams->getPlayerTeam($p) !== false){
            $e->setRespawnPosition($this->data[strval($this->arenaTeams->getPlayerTeam($p))."Spawn"]);
            return;
        }
        $e->setRespawnPosition($this->maindata["Spawn"]);
    }
    
    public function onInteract(PlayerInteractEvent $e){
        $b = $e->getBlock();
        $p = $e->getPlayer();
        if(new Vector3($b->x, $b->y, $b->z) == $this->maindata["blueJoinSign"]){
            $this->joinTeam($p, 1);
            return;
        }
        if(new Vector3($b->x, $b->y, $b->z) == $this->maindata["redJoinSign"]){
            $this->joinTeam($p, 2);
            return;
        }
        if(new Vector3($b->x, $b->y, $b->z) == $this->maindata["yellowJoinSign"]){
            $this->joinTeam($p, 3);
            return;
        }
        if(new Vector3($b->x, $b->y, $b->z) == $this->maindata["greenJoinSign"]){
            $this->joinTeam($p, 4);
            return;
        }
    }
    
    public function onChat(PlayerChatEvent $e){
        $e->setCancelled();
        $p = $e->getPlayer();
        if(strpos($e->getMessage(), "!") === 0 || $this->arenaTeams->getPlayerTeam($p) === 0){
            $this->arenateams->messageAllPlayers($e->getMessage(), $p);
            return;
        }
        $this->arenateams->messageTeam($e->getMessage(), $p);
    }
    
    public function teleportToArena(Player $p){
        $team = $this->arenateams->getPlayerTeam($p);
        $level = $this->plugin->getServer()->getLevelByName('canyon');
        if(!$this->plugin->getServer()->isLevelGenerated('canyon')){
            $this->worldManager->addWorld($level->getName());
        }
        if(!$this->plugin->getServer()->isLevelLoaded('canyon')){
            $this->plugin->getServer()->loadLevel('canyon');
        }
        $p->teleport(new Position($this->data["{$team}Spawn"]->x, $this->data["{$team}Spawn"]->y, $this->data["{$team}Spawn"]->z, $level));
        if(isset($this->players[strtolower($p->getName())]['inv'])){
            foreach($this->players[strtolower($p->getName())]['inv']['items'] as $slot => $item){
                $p->getInventory()->setItem($slot, $item);
            }
            foreach($this->players[strtolower($p->getName())]['inv']['armor'] as $slot => $item){
                $p->getInventory()->setArmorItem($slot, $item);
            }
        }
        $this->kitManager->giveKit($p);
    }
    
    public function stopRound(){
        //$this->arenateams->messageTeam(TextFormat::BOLD.TextFormat::GOLD."You recieved 400 coins for a win!", null, $this->arenateams->getWinningTeam());
        $this->phase = 0;
        foreach($this->arenateams->getAllPlayers() as $p){
            //transfer to hub server
        }
        $this->players = [];
        $this->arenateams->createTeams();
        $this->worldManager->resetWorld($this->map);
        $this->votingManager->createVoteTable();
    }
    
    public function startRound(){
        $kits = $this->kitManager;
        $kits->registerKits();
        foreach($this->arenateams->getAllPlayersInTeam() as $p){
            $this->teleportToArena($p);
        }
        $this->votingManager->currentTable = [];
        $this->changePhase(1);
    }
    
    public function broadcastResults($winner){
        foreach($this->arenateams->getAllPlayers() as $p){
            $tip = TextFormat::GOLD.TextFormat::BOLD."-----------------------------";
            $tip .= TextFormat::DARK_RED.TextFormat::BOLD."\n     * CONGRATULATIONS *";
            $tip .= "\n     ".TextFormat::BOLD.$winner.TextFormat::DARK_RED." team wins!";
            $tip .= TextFormat::GOLD.TextFormat::BOLD."\n-----------------------------";
            $p->sendTip($tip);
        }
    }
    
    public function sendPlayerToHub($player){
        
    }
    
    public function spawnDiamonds(){
        $level = Server::getInstance()->getLevelByName($this->map);
        foreach($this->data["diamonds"] as $d){
            $level->setBlock($d, Block::get(56, 0));
        }
    }
    
    public function contains($x, $y, $z, $x1, $y1, $z1, Vector3 $pos) {
        $axis = new AxisAlignedBB($x, $y, $z, $x1, $y1, $z1);
        if($axis->isVectorInside($pos)){
            return true;
        }
        return false;
    }
    
    public function onRestart(){
        
    }
    
    public function checkAlive(){
        if($this->phase === 0){
            return;
        }
        if(count($this->arenateams->getTeamPlayers(1)) > 0 && count($this->arenateams->getTeamPlayers(2)) <= 0 && count($this->arenaTeams->getTeamPlayers(3)) <= 0 && count($this->arenaTeams->getTeamPlayers(4)) <= 0){
            $this->broadcastResults("blue");
            return;
        }
        if(count($this->arenateams->getTeamPlayers(1)) <= 0 && count($this->arenateams->getTeamPlayers(2)) > 0 && count($this->arenaTeams->getTeamPlayers(3)) <= 0 && count($this->arenaTeams->getTeamPlayers(4)) <= 0){
            $this->broadcastResults("red");
            return;
        }
        if(count($this->arenateams->getTeamPlayers(1)) <= 0 && count($this->arenateams->getTeamPlayers(2)) <= 0 && count($this->arenaTeams->getTeamPlayers(3)) > 0 && count($this->arenaTeams->getTeamPlayers(4)) <= 0){
            $this->broadcastResults("yellow");
            return;
        }
        if(count($this->arenaTeams->getTeamPlayers(1)) <= 0 && count($this->arenaTeams->getTeamPlayers(2)) <= 0 && count($this->arenaTeams->getTeamPlayers(3)) <= 0 && count($this->arenaTeams->getTeamPlayers(4)) > 0){
            $this->broadcastResults("green");
            return;
        }
    }
    
    public function boostTimer(){
        
    }
    
    public function getGravelDrops(){
        $items = [Item::get(287, 0, rand(0, 2)), Item::get(352, 0, rand(0, 2)), Item::get(318, 0, rand(0, 3)), Item::get(288, 0, rand(0, 3)), Item::get(262, 0, rand(0, 4))];
        return $items;
    }
    
    public function breakNexus(Player $player, $team){
        if($this->arenaTeams->getPlayerTeam($player) == 1){
            $player->sendTip(TextFormat::RED."You can't break your own nexus");
            return;
        }
        if($this->arenaTeams->teams[$team]['nexus'] >= 1){
            if($this->phase > 1 && $this->phase < 5){
                foreach($this->arenaTeams->getTeamPlayers($this->arenaTeams->getPlayerTeam($player)) as $p){
                    $p->sendTip($player->getNameTag().TextFormat::AQUA." damaged".TextFormat::DARK_BLUE." blue ".TextFormat::AQUA."team's nexus");
                }
                $this->arenaTeams->teams[$team]['nexus']--;
            }
            if($this->phase == 5){
                foreach($this->arenaTeams->getTeamPlayers($this->arenaTeams->getPlayerTeam($player)) as $p){
                    $p->sendTip($player->getNameTag().TextFormat::AQUA." damaged".TextFormat::DARK_BLUE." red ".TextFormat::AQUA."team's nexus");
                }
                foreach($this->arenaTeams->getTeamPlayers($team) as $p){
                    $p->sendTip(TextFormat::RED.TextFormat::BOLD.$this->arenaTeams->getNexusHp($team));
                    Server::getInstance()->getLevelByName($this->map)->addSound(new ClickSound(new Vector3($p->x, $p->y, $p->z), [$p]));
                }
                $this->arenaTeams->teams[$team]['nexus']--;
                $this->arenaTeams->teams[$team]['nexus']--;
            }
            return;
        }
        $this->arenateams->teams[$team]['nexus'] = 0;
        $nexus = $this->data[$team.'Nexus'];
        $player->getServer()->getLevelByName($this->map)->setBlockIdAt($nexus->x, $nexus->y, $nexus->z, 7);
        foreach($this->arenaTeams->getAllPlayers() as $p){
            $p->sendMessage(TextFormat::DARK_BLUE."----------------------------");
            $p->sendMessage($player->getNameTag().TextFormat::AQUA." from ".$this->getTeamName($this->getPlayerTeam($player))." team destroyed ".TextFormat::DARK_BLUE."blue ".TextFormat::AQUA."team's nexus");
            $p->sendMessage(TextFormat::DARK_BLUE."----------------------------");
        }
    }
    
    public function joinTeam(Player $p, $team, $forcejoin = false){
        if($this->arenateams->getPlayerTeam($p) == $team && !$p->isOp()){
            $p->sendPopup(TextFormat::GOLD."[Annihilation]".TextFormat::GRAY."You are already in ".$this->arenateams->getTeamColor($team).$this->arenateams->teams[$team]['name']." team");
            return;
        }
        if(!$this->arenateams->isTeamFree($team) && !$p->isOp()){
            $p->sendPopup(TextFormat::GOLD."[Annihilation]".TextFormat::GRAY."This team is full");
            return;
        }
        if($this->phase >= 3 && $forcejoin == false && !$p->isOp()){
            $p->sendMessage(TextFormat::GOLD."[Annihilation]".TextFormat::GRAY."You can't join in this phase");
            return;
        }
        if($this->arenaTeams->getPlayerTeam($p) !== 0){
            $p->sendMessage(TextFormat::GOLD."[Annihilation]".TextFormat::GRAY." You can not change teams");
            return;
        }
        $this->arenateams->removeFromTeam($p, 0);
        $this->arenateams->addToTeam($p, $team);
        $p->sendMessage(TextFormat::GOLD."[Annihilation]".TextFormat::GRAY." You joined ".$this->arenateams->getTeamColor($team).$this->arenaTeams->getTeamName($team));
        $this->checkLobby();
        return;
    }
    
    public function changePhase($phase){
        switch($phase){
            case 1:
                $this->phase = 1;
                $this->arenateams->messageAllPlayers(TextFormat::GRAY."===========[ ".TextFormat::AQUA."Progress".TextFormat::GRAY." ]===========\n"
                        . TextFormat::BLUE."Phase I ".TextFormat::GRAY."has started\n"
                        . TextFormat::GRAY."Each nexus is invicible until Phase II\n"
                        . TextFormat::GRAY."==================================");
                break;
            case 2:
                $this->phase = 2;
                //$this->bossManager->spawnBoss(1);
                //$this->bossManager->spawnBoss(2);
                $this->arenateams->messageAllPlayers(TextFormat::GRAY."===========[ ".TextFormat::AQUA."Progress".TextFormat::GRAY." ]===========\n"
                        . TextFormat::GREEN."Phase II ".TextFormat::GRAY."has started\n"
                        . TextFormat::GRAY."Each nexus is no longer invicible\n"
                        . TextFormat::GRAY."Boss Iron Golems will now spawn\n"
                        . TextFormat::GRAY."==================================");
                break;
            case 3:
                $this->phase = 3;
                $this->spawnDiamonds();
                break;
            case 4:
                $this->phase = 4;
                break;
            case 5:
                $this->phase = 5;
                break;
        }
    }
    
    public function checkLobby(){
        if($this->phase >= 1){
            return;
        }
        if(count($this->arenateams->getAllPlayers()) >= 1 && $this->phase === 0){
            $this->starting = true;
        }
    }
    
    public function selectMap(){
        $stats = $this->votingManager->currentTable['stats'];
        sort($stats);
        $map = $this->votingManager->currentTable[array_pop($stats)];
        $this->map = $map;
        $this->data = $this->plugin->maps[$map];
        foreach($this->arenateams->getAllPlayers() as $p){
            $p->sendTip(TextFormat::BOLD.TextFormat::YELLOW.$map.TextFormat::GOLD." was chosen");
        }
        $this->worldManager->addWorld($map);
    }
}