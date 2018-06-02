<?php

namespace TTT\Arena;

use pocketmine\block\Lapis;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\minetox\MTMinigame;
use pocketmine\minetox\MTServer;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\block\Block;
use pocketmine\block\Chest;
use pocketmine\item\Item;
use pocketmine\Player;
use TTT\TTT;
use TTT\TTTData;

class Arena extends MTServer implements Listener, MTMinigame
{

    public $second = 961;

    private $roles = [];

    public function __construct($id, TTT $api)
    {
        $this->setName("-TTT" . $id . "-");
        $this->setGameName("TTT");
        $this->setMaxPlayers(12);
        $this->setType(2);
        $this->setState(2);
        $this->api = $api;
        $this->tester = new Tester($this);
    }

    public function getEstimatedShutdown()
    {
        return $this->second;
    }

    public function onRestart()
    {
        $this->second = 961;
    }

    public function stopGame()
    {
        $this->second = 11;
    }

    public function isPreparing()
    {
        return true;
    }

    public function startGame()
    {
        $this->second = 911;
    }

    public function checkAlive()
    {
        $t = false;
        $i = false;

        foreach($this->getAllPlayers() as $player)
        {
            if($player->ttt_role === TTTData::$traitor)
            {
                $t = true;
            }
            if($player->ttt_role === TTTData::$innocent or $player->ttt_role === TTTData::$detective)
            {
                $i = true;
            }
        }

        if(!$t)
        {
            $this->MessageAll("Die INNOCENT haben gewonnen!");
            $this->broadcastMessage($this->traitorString());
            $this->second = 11;
        }
        elseif(!$i)
        {
            $this->MessageAll("Die TRAITOR haben gewonnen!");
            $this->broadcastMessage($this->traitorString());
            $this->second = 11;
        }
    }

    public function traitorString()
    {
        $traitors = "Die Traitor waren: ";

        foreach($this->roles["t"] as $p)
        {
            $traitors .= $p . " ";
        }
        return $traitors;
    }

    public function assignRoles()
    {

        $traitor_total = rand(1, TTTData::$max_traitors);
        $detective_total = rand(1, TTTData::$max_detectives);

        $traitors_set = 0;
        $detectives_set = 0;

        $players = $this->getAllPlayers();
        shuffle($players);

        for($i = 0; $i <= $traitor_total; ++$i) //TODO Show other Traitors assigned to Niggi
        {
            $players[$i]->ttt_role = TTTData::$traitor;
            $players[($i + $traitor_total)]->sendMessage("Du bist ein TRAITOR!");
            $players[($i + $traitor_total)]->tpoints = 0;
            ++$traitors_set;
        }


        for($d = 0; $i <= $detective_total; ++$i)
        {
            $players[($d + $detective_total)]->ttt_role = TTTData::$detective;
            $players[($d + $detective_total)]->sendMessage("Du bist ein DETECTIVE!");
            ++$detectives_set;
        }

        foreach($this->getAllPlayers() as $p)
        {
            if(!isset($p->ttt_role))
            {
                $p->ttt_role = TTTData::$innocent;
                $this->roles["i"] = $p->getDisplayName();
                $p->sendMessage("Du bist ein INNOCENT!");
            }

            if ($p->ttt_role === TTTData::$traitor)
            {
                $this->roles["t"] = $p->getDisplayName();
            }
            elseif ($p->ttt_role === TTTData::$detective)
            {
                $this->roles["d"] = $p->getDisplayName();
            }

        }
    }

    public function getTester()
    {
        return $this->tester;
    }

    public function onBlockBreak(BlockBreakEvent $event)
    {
        if($this->isPlayerOnline($event->getPlayer()))
        {
            $event->setCancelled();
        }
    }

    public function onBlockPlace(BlockPlaceEvent $event)
    {
        if($this->isPlayerOnline($event->getPlayer()))
        {
            $event->setCancelled();
        }
    }

    /**
     * @param EntityDamageEvent $event
     */
    public function onDamage(EntityDamageEvent $event)
    {
        $entity = $event->getEntity();
        if($entity instanceof Player)
        {
            if($this->isPlayerOnline($entity))
            {
                if($this->second > 870 or $this->second < 11)
                {
                    $event->setCancelled();
                }
                $this->checkAlive();
            }
        }
    }

    public function onDeath(PlayerDeathEvent $event)
    {
        $entity = $event->getEntity();
        if($entity instanceof Player)
        {
            if($this->isPlayerOnline($entity))
            {
                $this->checkAlive();
                unset($entity->ttt_role);
            }
        }
    }

    public function onTouch(PlayerInteractEvent $e)
    {
        $block = $e->getBlock();
        $player = $e->getPlayer();
        if($block instanceof Chest and $this->isPlayerOnline($player))
        {
            $pos = new Vector3($block->getX(), $block->getY(), $block->getZ());
            $block->getLevel()->setBlock($pos, Block::get(Block::AIR), true);
            $item = TTTData::getRandomChestItem();
            if($item == Item::BOW)
            {
                $player->getInventory()->addItem(Item::get($item));
                $player->getInventory()->addItem(Item::get(Item::ARROW, 0, 32)); //Add arrows, too!
            }
            else
            {
                $player->getInventory()->addItem(Item::get($item));
            }
            $e->setCancelled();
        }

        if($block instanceof Lapis and $this->isPlayerOnline($player))
        {
            $this->getTester()->testPlayer($player);
        }

    }

} 