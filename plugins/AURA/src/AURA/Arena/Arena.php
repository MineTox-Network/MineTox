<?php

namespace AURA\Arena;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\minetox\MTMinigame;
use pocketmine\minetox\MTServer;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\Player;
use AURA\AURA;

class Arena extends MTServer implements MTMinigame, Listener
{

    public $winner = false;
    /** 11 Minutes 1 second */
    public $second = 661;
    public $api;

    public function __construct($id, AURA $plugin)
    {
        $this->api = $plugin;
        $this->setType(2);
        $this->setName("-AURA".$id."-");
        $this->setGameName("AURA");
        $this->setMaxPlayers(24);
        $this->setState(0);
    }

    /* AURA */

    /**
     * @param bool
     * @return bool
     */
    public function getWinner($set = true)
    {
        if($this->getPlayerCount() < 2)
        {
            if($this->second < 610 and $this->second > 11 and !isset($this->winner))
            {
                if($set)
                {
                    $player = $this->getAllPlayers();
                    array_values($player);
                    $this->winner = $player[0];
                    $this->second = 11;
                }
                return true;
            }
            return false;
        }
    }

    /**
     * Equip players
     */
    public function equipPlayers()
    {
        foreach($this->getAllPlayers() as $player)
        {
            if($player instanceof Player)
            {
                /* Items */
                $player->getInventory()->addItem(new Item(Item::STICK));
                $player->getInventory()->addItem(new Item(Item::SNOWBALL, 0, 16));
                $player->getInventory()->addItem(new Item(Item::PUMPKIN_PIE, 0, 20));
                /* Armor */
                $player->getInventory()->setHelmet(new Item(Item::DIAMOND_HELMET));
                $player->getInventory()->setChestplate(new Item(Item::DIAMOND_CHESTPLATE));
                $player->getInventory()->setLeggings(new Item(Item::DIAMOND_LEGGINGS));
                $player->getInventory()->setBoots(new Item(Item::DIAMOND_BOOTS));
            }
        }
    }

    /* Listener */

    public function onBreak(BlockPlaceEvent $e)
    {
        if($this->isPlayerOnline($e->getPlayer()))
        {
            $e->setCancelled();
        }
    }

    public function onPlace(BlockPlaceEvent $e)
    {
        if($this->isPlayerOnline($e->getPlayer()))
        {
            $e->setCancelled();
        }
    }

    public function onInteract(PlayerInteractEvent $e)
    {
        if($this->isPlayerOnline($e->getPlayer()))
        {
            $e->setCancelled();
        }
    }

    public function onDeath(PlayerDeathEvent $e)
    {
        $player = $e->getEntity();
        if($this->isPlayerOnline($player))
        {
            $this->MessageAll($player->getDisplayName()." ist gestorben");
            if($this->getPlayerCount() > 3)
            {
                $this->MessageAll(($this->getPlayerCount() - 1). " verbleibende Spieler");
            }
            $this->removePlayer($player);
        }
    }

    public function onDamage(EntityDamageEvent $e)
    {
        if($e->getEntity() instanceof Player)
        {
            if($this->isPlayerOnline($e->getEntity()))
            {
                if($this->second > 570 or $this->second < 11)
                {
                    $e->setCancelled();
                }
                else
                {
                    $entity = $e->getEntity();
                    if($entity instanceof Player)
                    {
                        if($e instanceof EntityDamageByEntityEvent)
                        {
                            $e->setDamage(1);
                            $e->setKnockBack(1.2);
                        }
                    }
                }
            }
        }
    }



    /* API */

    public function startGame()
    {
        $this->second = 611;
        $this->winner = false;
    }

    public function stopGame()
    {
        $this->second = 11;
    }

    public function isPreparing()
    {
        if($this->getAllPlayers() >= 6)
        {
            return true;
        }
        return false;
    }

    public function onRestart()
    {
        $this->second = 661;
        $this->winner = false;
    }

    public function checkAlive()
    {
        if($this->getPlayerCount() <= 0)
        {
            return true;
        }
        return false;
    }
} 