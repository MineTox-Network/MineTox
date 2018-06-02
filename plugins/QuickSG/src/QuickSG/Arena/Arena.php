<?php

namespace QuickSG\Arena;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\minetox\MTMinigame;
use pocketmine\minetox\MTUtility;
use pocketmine\minetox\MTServer;
use pocketmine\entity\PrimedTNT;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\block\Block;
use pocketmine\tile\Chest;
use pocketmine\item\Item;
use pocketmine\block\TNT;
use pocketmine\Player;
use QuickSG\QuickSG;

class Arena extends MTServer implements Listener, MTMinigame
{
    public $arenaType;
    
    public $deathmatch = false;
    public $isPreparing = false;
    public $hasStarted = false;
    public $winner = false;
    public $pvp = false;
    public $task;

    private $ArenaChests = [];
    private $Chests = [];


    public function __construct($id, QuickSG $plugin)
    {
        $this->api = $plugin;
        /** 7 Minuten und 25 Sekunden */
        $this->api->getServer()->getScheduler()->scheduleRepeatingTask(new ArenaSchedule($this), 20);
        $this->setDefaultColor("§3");
        $this->setName("-QuickSG".$id."-");
        $this->setGameName("QuickSG");
        $this->setMaxPlayers(12);
        $this->shuffleItems();
        $this->seconds = 445;
        $this->setState(0);
        $this->setType(2);
    }

    public function onArenaLeave(Player $player)
    {
        $player->move = true;
    }

    public function shuffleItems() //Method only allows 20 lines...
    {
        unset($this->ArenaChests);
        $this->ArenaChests =
        [
            307, 311, 315, 298, 303, 302, 306, 314, 304, 308, 316, 301, 305, 309, 317, //Rüstung
            268, 272, 261, 345,
            297, 364, 260,
            262, 30, 10, 9
        ];
        shuffle($this->ArenaChests);
    }

    public function getOwner()
    {
        return $this->api;
    }

    public function onRestart()
    {
        $this->isPreparing = false;
        $this->deathmatch = false;
        $this->hasStarted = false;
        $this->shuffleItems();
        /*7 Minuten und 25 Sekunden*/
        $this->seconds = 445;
        $this->disablePVP();
    }

    public function track(Player $player)
    {
        if($this->isPlayerOnline($player))
        {
            if(count($this->getAllPlayers()) > 1)
            {
                $players = $this->getAllPlayers();
                $copied_array = $players;
                unset($copied_array[$player->getName()]);
                $nearest =
                [
                    "blocks" => 10000,
                    "player" => ""
                ];
                $Vector1 = $player->getPosition();
                foreach($copied_array as $p)
                {
                    $ppos = $p->getPosition();
                    $blocks = $Vector1->distance($ppos);
                    if($blocks < $nearest["blocks"])
                    {
                        $nearest["blocks"] = $blocks;
                        $nearest["player"] = $p->getName();
                    }
                }
                $player->sendMessage("[QuickSG] Spieler ".$nearest["player"]." getrackt: ".round($nearest["blocks"])." Blöcke");
            }
            else
            {
                $this->messagePlayer($player, "§4Es konnte lebender kein Spieler getrackt werden");
            }
        }
    }

    public function refillChests()
    {
        foreach($this->getMapData("level")->getTiles() as $tile)
        {
            if($tile instanceof Chest)
            {
                $items = $this->ArenaChests;
                $Chests = $this->Chests;
                if(!in_array($tile->getId(), $Chests))
                {
                    $inv = $tile->getInventory();
                    $amount = mt_rand(1, 5);
                    $Chests[] = $tile->getId();
                    $inv->clearAll();
                    $put = 0;
                    while($put <= $amount)
                    {
                        $item = new Item($items[array_rand($items)]);
                        if(MTUtility::isItem($item->getId())) //Returns always false
                        {
                            $item->setCount(mt_rand(1, 4));
                        }
                        $inv->setItem(rand(0, $inv->getSize()), $item);
                        ++$put;
                    }
                }
            }
        }
    }
	
    public function checkPlayers()
    {
        foreach($this->getAllPlayers() as $player)
        {
            $name = $player->getName();
            $player->getInventory()->clearAll();
            if(!$this->getOwner()->isPlayerRegistered($name))
            {
                $this->getOwner()->registerPlayer($name);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getWinner()
    {
        if(count($this->getAllPlayers()) === 1)
        {
            foreach($this->getAllPlayers() as $p)
            {
                return $p;
                break;
            }
        }
        return false;
    }

    public function releasePlayers()
    {
        foreach($this->getAllPlayers() as $player)
        {
            $player->move = true;
        }
    }
	
    public function teleportPlayerstoArenaSpawns()
    {
        $spawns = $this->getMapData("arena_spawns");
        $totalcount = (count($spawns) - 1);
        $spawncount = rand(0, ($this->getMaxPlayers() - 1));
        foreach($this->getAllPlayers() as $p)
        {
            $p->teleport($spawns[$spawncount]);
            ++$spawncount;
            if($spawncount > $totalcount)
            {
                $spawncount = 0;
            }
            $p->move = false;
        }
    }
	
    public function teleportPlayersToDeathmatchArena()
    {
        $arena = mt_rand(1, 2);
        $spawns = $this->getMapData("dm_spawns".$arena); //TODO ADD TO EVERY SG MAP 2 Deathmatch Arenas
        $totalcount = (count($spawns) - 1);
        $spawncount = 0;
        foreach($this->getAllPlayers() as $p)
        {
            $p->teleport($spawns[$spawncount]);
            ++$spawncount;
            if($spawncount > $totalcount)
            {
                $spawncount = 0;
            }
        }
    }

    /**
     * @return bool
     */
    public function isPreparingForDeathmatch()
    {
        return $this->seconds <= 191;
    }

    public function checkAlive()
    {
        if($this->getState() === 1)
        {
            if(!$this->winner)
            {
                $players = $this->getPlayerCount();
                if($players <= 4 and $players > 1)
                {
                    if(!$this->isPreparingForDeathmatch())
                    {
                        $this->seconds = 191;
                    }
                    $this->MessageAll("§e".($this->getPlayerCount() - 1)." §3verbleibende Spieler");
                }
                elseif($players <= 1)
                {
                    if($this->seconds > 11)
                    {
                        $this->stopRound();
                    }
                }
            }
        }
    }

    public function boostTimer()
    {
        if(!$this->isPreparing)
        {
            $this->seconds = 386;
            $this->isPreparing = true;
        }
    }

    public function startRound()
    {
        $this->setState(1);
        $this->checkPlayers();
        $this->teleportPlayerstoArenaSpawns();
    }
	
    public function stopRound()
    {
        $this->seconds = 11;
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

    public function startDeathmatch()
    {
        $this->teleportPlayersToDeathmatchArena();
        $this->deathmatch = true;
    }

    public function announceWinner()
    {

    }

    public function startGame()
    {
        $this->enablePVP();
        $this->refillChests();
        $this->releasePlayers();
        $this->hasStarted = true;
    }

    /**
     * @param PlayerQuitEvent $e
     */
    public function onQuit(PlayerQuitEvent $e)
    {
        $player = $e->getPlayer();
        if($this->isPlayerOnline($player))
        {
            if($this->getState() === 1)
            {
                $this->removePlayer($player);
                $this->checkAlive();
            }
        }
    }


    /**
     * @param EntityDamageEvent $e
     */
    public function onDamage(EntityDamageEvent $e)
    {
        $entity = $e->getEntity();
        if($entity instanceof player)
        {
            if($this->isPlayerOnline($entity))
            {
                if(!$this->pvp)
                {
                    $e->setCancelled();
                }
            }
        }
    }

    /**
     * @param PlayerInteractEvent $event
     */
    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        if($this->isPlayerOnline($player))
        {
            $itemId = $event->getItem()->getId();
            $blockId = $event->getBlock()->getId();
            if($itemId === 345)
            {
                $this->track($player);
            }
            elseif($blockId === 54)
            {
                if(!$this->hasStarted)
                {
                    $event->setCancelled();
                }
            }
        }
    }



    /**
     * @param BlockBreakEvent $event
     */
    public function onBreak(BlockBreakEvent $event)
    {
        if($this->isPlayerOnline($event->getPlayer()))
        {
            if($this->hasStarted)
            {
                $blockid = $event->getBlock()->getID();
                switch($blockid)
                {
                    case 18:  //Leaves :*
                    case 30:  //Cockweb
                    case 31:  //Gras
                    case 39:  //Brown Mushroom
                    case 40:  //Red Mushroom
                    case 46:  //TNT
                    case 51:  //Fire
                    case 92:  //Cake Block
                        break;
                    default:
                        $event->setCancelled(true);
                        break;
                }
            }
            else
            {
                $event->setCancelled(true);
            }
	    }
    }

    /**
     * @param BlockPlaceEvent $e
     */
    public function onPlace(BlockPlaceEvent $e)
    {
        if($this->isPlayerOnline($e->getPlayer()))
        {
            $block = $e->getBlock();
            $blockId = $block->getId();
            switch($blockId)
            {
                case 30:  //Cockweb
                case 46:  //TNT
                case 92:  //Cake Block
                    break;

                default:
                    $e->setCancelled();
                    break;
            }
            if($block instanceof TNT)
            {
                $block->onActivate(Item::get(Item::FLINT_STEEL));
                $block->getLevel()->setBlock(new Vector3($block->x, $block->y, $block->z), new Block(Block::AIR));
            }
	    }
    }

    public function onTNT(EntityExplodeEvent $e)
    {
        if($e->getEntity() instanceof PrimedTNT)
        {
            if($e->getPosition()->getLevel()->getName() === $this->getMapData("name"))
            {
                $e->setYield(0);
            }
        }
    }

    /**
     * @param PlayerDeathEvent $e
     */
    public function EDE(PlayerDeathEvent $e)
    {
        $player = $e->getEntity();
        if($this->isPlayerOnline($player))
        {
            $killer = $player->getLastDamageCause();
            if($killer instanceof EntityDamageByEntityEvent)
            {
                $killer = $killer->getDamager();
                if($killer instanceof Player)
                {
                    $player->sendMessage("[QuickSG] ".MTUtility::getColoredName($killer, true)." hat dich getötet");
                    $this->messagePlayer($killer, "§3Du hast ".MTUtility::getColoredName($player, true)." §3getötet! §e+20 §3Punkte");

                    $this->MessageAll(MTUtility::getColoredName($player, true)." §3wurde von ".MTUtility::getColoredName($killer, true)." §3getötet");

                    $this->getOwner()->addPoints($killer->getName(), 20);
                    $this->getOwner()->addDeath($player->getName());
                    $this->getOwner()->addKill($killer->getName());

                    if($this->getPlayerCount() <= 1)
                    {
                        $this->messagePlayer($killer, "Du hast +200 Punkte erhalten");
                        $this->getOwner()->addPoints($killer->getName(), 200);
                        $this->getOwner()->addWin($killer->getName());
                        $this->winner = true;
                        $this->seconds = 11;
                    }
                }
                else
                {
                    $this->MessageAll(MTUtility::getColoredName($player, true)." §3ist gestorben");
                }
            }
            else
            {
                $this->MessageAll(MTUtility::getColoredName($player, true)." §3ist gestorben");
            }
            if($this->getPlayerCount() >= 3)
            {
                $this->MessageAll("§e".($this->getPlayerCount() - 1)." §3verbleibende Spieler");
            }
            $this->removePlayer($player);
            $this->checkAlive();
        }
    }
}