<?php

namespace BedWars\Arena;

use pocketmine\block\Block;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\event\Listener;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;

class ArenaTrading implements Listener
{

    /**
     * @var ArenaTeams
     */
    public $teams;

    /**
     * @var Arena
     */
    public $api;

    /**
     * @var
     */
    private $shops;

    /**
     * @var Shop
     */
    private $shop;

    /**
     * @var
     */
    private $shopping = [];

    public function __construct(Arena $arena)
    {
        $this->api = $arena;
        $this->teams = $arena->teams;
        $this->shop = new Shop($this);
    }

    public function generateShopInventories()
    {
        $level = $this->api->getMapData("level");
        foreach ($this->teams->getTeams() as $id => $team) {
            $base = $this->api->getMapData("Team" . $id);
            if ($base instanceof Vector3 and $level instanceof Level) {
                for ($i = 0; $this->teams->getTeams()["settings"]["perTeam"] < $i; ++$i) {
                    $pos = new Position($base->getX(), ($base->getY() - 2 - $i), $base->getZ(), $level);
                    $level->setBlock($pos, new Block(Block::CHEST));
                    $this->shops[] = $pos;
                }
            }
        }
    }

    /**
     * @param Player $p
     * @return bool
     */
    public function canGoShopping(Player $p)
    {
        if (isset($p->team) and isset($this->teams->getTeams()[$p->team][$p->getName()])) {
            return true;
        }
        return false;
    }

    /**
     * @param Player $player
     * @param bool $directOpen
     * @return bool|Block
     */
    public function getPlayerShop(Player $player, $directOpen = false)
    {
        if($this->canGoShopping($player))
        {
            $found = false;
            while(!$found)
            {
                $shop = $this->shops[array_rand($this->shops)];
                if($shop instanceof Position)
                {
                    $shop = $shop->getLevel()->getBlock($shop);
                    if($shop instanceof \pocketmine\tile\Chest)
                    {
                        if(count($shop->getInventory()->getViewers()) < 1)
                        {
                            if($directOpen)
                            {
                                $shop->getInventory()->onOpen($player);
                                $this->shopping[$player->getName()] = $shop;
                            }
                            $found = true;
                            return $shop;
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function isShopping(Player $player)
    {
        if (isset($this->shopping[$player->getName()])) {
            return true;
        }
        return false;
    }


    public function makeShop(Player $player)
    {

    }

    /**
     * Listening parts
     */
    public function onInvOpen(InventoryOpenEvent $e)
    {
        if($this->api->isPlayerOnline($e->getPlayer()))
        {
            if(isset($this->shopping[$e->getPlayer()->getName()]))
            {
                if($this->shopping[$e->getPlayer()->getName()] === $e->getInventory())
                {
                    return true;
                }
            }
        }
        return false;
    }

    public function onInvClose(InventoryCloseEvent $e)
    {
        if($this->api->isPlayerOnline($e->getPlayer()))
        {
            if(isset($this->shopping[$e->getPlayer()->getName()]))
            {
                unset($this->shopping[$e->getPlayer()->getName()]);
                return true;
            }
        }
        return false;
    }

    public function onTransaction()
    {

    }

} 