<?php

namespace kitpvp\arena;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\minetox\MTServer;
use pocketmine\minetox\PlayerEvents;
use pocketmine\Player;

class Arena extends MTServer implements Listener, PlayerEvents
{

    public $playerDefaults = array("kills" => 0,
                                   "deaths" => 0,
                                   "wheat" => 0,
                                   "fire" => false);

    public function __construct($id)
    {
        $this->setGameName("KitPvP");
        $this->setMaxPlayers(30);
        $this->setName("-KitPvP".$id."-");
        $this->setState(0);
        $this->setType(2);
    }

    /*
     * API
     */

    public function onJoin(Player $player)
    {
        $player->kitPvPKit = mt_rand(1,3);
        $player->kitPvPData = $this->playerDefaults;
        $this->equipPlayer($player);
    }

    public function onLeave(Player $player)
    {
        unset($player->kitPvPData);
        unset($player->kitPvPKit);
    }

    public function onNickChange(Player $player)
    {

    }

    /*
     * Plugin
     */

    public function equipPlayer(Player $player)
    {
        $player->getInventory()->clearAll();
        switch($player->kitPvPKit)
        {
            case 1:

                $player->getInventory()->setHelmet(new Item(Item::LEATHER_CAP));
                $player->getInventory()->setChestplate(new Item(Item::LEATHER_TUNIC));
                $player->getInventory()->setLeggings(new Item(Item::LEATHER_PANTS));
                $player->getInventory()->setBoots(new Item(Item::LEATHER_BOOTS));

                $player->getInventory()->addItem(new Item(Item::DIAMOND_SWORD));
                $player->getInventory()->addItem(new Item(Item::BOW));
                $player->getInventory()->addItem(new Item(Item::ARROW, 0, 64));

                break;
            case 2:

                $player->getInventory()->setHelmet(new Item(Item::CHAIN_HELMET));
                $player->getInventory()->setChestplate(new Item(Item::CHAIN_CHESTPLATE));
                $player->getInventory()->setLeggings(new Item(Item::CHAIN_LEGGINGS));
                $player->getInventory()->setBoots(new Item(Item::CHAIN_BOOTS));

                $player->getInventory()->addItem(new Item(Item::IRON_SWORD));
                $player->getInventory()->addItem(new Item(Item::BOW));
                $player->getInventory()->addItem(new Item(Item::ARROW, 0, 32));

                break;
            case 3:

                $player->getInventory()->setHelmet(new Item(Item::IRON_HELMET));
                $player->getInventory()->setChestplate(new Item(Item::IRON_CHESTPLATE));
                $player->getInventory()->setLeggings(new Item(Item::IRON_LEGGINGS));
                $player->getInventory()->setBoots(new Item(Item::IRON_BOOTS));

                $player->getInventory()->addItem(new Item(Item::STONE_SWORD));
                $player->getInventory()->addItem(new Item(Item::BOW));
                $player->getInventory()->addItem(new Item(Item::ARROW, 0, 32));

                break;
            default:

                $player->kitPvPKit = 1;
                $this->equipPlayer($player);

                break;
        }
        $player->getInventory()->sendArmorContents($player);
    }

    public function runKillStreak(Player $player)
    {
        switch($player->kitPvPData["kills"])
        {
            case 5:
            $this->broadcastMessage($player->getDisplayName()." hat eine 5er Killserie");
                break;
            case 10:
                $this->broadcastMessage($player->getDisplayName()." hat eine 10er Killserie");
                break;
            case 20:
                $this->broadcastMessage($player->getDisplayName()." hat eine 20er Killserie");
                $player->getInventory()->addItem(new Item(Item::DIAMOND_PICKAXE));
                $player->sendMessage("=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=");
                $player->sendMessage("Du hast eine Diamantspitzhacke bekommen!");
                $player->sendMessage("Baue einen Diamantblock ab,\num einen Lebens-Boost zu erhalten.");
                $player->sendMessage("=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=");
                break;
            case 25:
                $this->broadcastMessage($player->getDisplayName()." hat eine 25er Killserie");
                break;
            case 30:
                $this->broadcastMessage($player->getDisplayName()." hat eine 30er Killserie");
                break;
            case 50:
                $this->broadcastMessage($player->getDisplayName()." hat eine 50er Killserie");
                break;
            case 75:
                $this->broadcastMessage($player->getDisplayName()." hat eine 75er Killserie");
                break;
            case 100:
                $this->broadcastMessage($player->getDisplayName()." hat eine 100er Killserie");
                break;
            case 200:
                $this->broadcastMessage($player->getDisplayName()." hat eine 200er Killserie");
                break;
            case 300:
                $this->broadcastMessage($player->getDisplayName()." hackt.");
                break;
        }
    }

    /*
     * Listener
     */

    public function doRespawn(Player $p)
    {
        if($this->isPlayerOnline($p))
        {
            ++$p->kitPvPData["deaths"];
            $c = $p->getLastDamageCause();
            if($c instanceof EntityDamageByEntityEvent)
            {
                if($c->getDamager() instanceof Player)
                {
                    $p->extinguish();
                    $p->kitPvPData = $this->playerDefaults;
                    $p->sendMessage("Du wurdest von ".$c->getDamager()->getDisplayName()." getötet");
                    $c->getDamager()->sendMessage("Du hast ".$p->getDisplayName()." getötet");
                    ++$c->getDamager()->kitPvPData["kills"];
                    $this->runKillStreak($c->getDamager());
                    $p->sendMessage($c->getDamager()->getDisplayName()." hat noch ".($c->getDamager()->getHealth() / 2)." Herzen");
                }
                else
                {
                    $p->sendMessage("Du bist gestorben");
                }
            }
            else
            {
                $p->sendMessage("Du bist gestorben");
            }
            $p->kitPvPKit = mt_rand(1, 3);
            $this->equipPlayer($p);
            $p->teleport($this->getMapData("level")->getSafeSpawn());
        }
    }

    public function onDamage(EntityDamageEvent $e)
    {
        $p = $e->getEntity();
        if($p instanceof Player)
        {
            if($this->isPlayerOnline($p))
            {
                //Fire enchantment
                $c = $p->getLastDamageCause();
                if($c instanceof EntityDamageByEntityEvent)
                {
                    if($c->getDamager() instanceof Player)
                    {
                        if($c->getDamager()->kitPvPData["fire"])
                        {
                            $p->setOnFire(2);
                        }
                    }
                }
                if($p->getLevel()->getSpawnLocation()->distance($p->getPosition()) < 6)
                {
                    //Is in spawn protection
                    $e->setCancelled();
                }
                $newHealth = $p->getHealth() - $e->getFinalDamage();
                if($newHealth < 1)
                {
                    $e->setCancelled();
                    $p->setHealth(20);
                    $p->setMaxHealth(20);
                    $this->doRespawn($p);
                }
            }
        }
    }

    public function onInteract(PlayerInteractEvent $e)
    {
        if($this->isPlayerOnline($e->getPlayer()))
        {
            $id = $e->getBlock()->getId();
            if($id !== Item::DOOR_BLOCK)
            {
                $e->setCancelled();
            }
        }
    }

    public function onPlace(BlockPlaceEvent $e)
    {
        if($this->isPlayerOnline($e->getPlayer()))
        {
            $e->setCancelled();
        }
    }

    public function onBreak(BlockBreakEvent $e)
    {
        $p = $e->getPlayer();
        if($this->isPlayerOnline($p))
        {
            $e->setCancelled();
            switch($e->getBlock()->getId())
            {
                case Item::HAY_BALE:
                case Item::WHEAT_BLOCK:
                    ++$p->kitPvPData["wheat"];
                    if($p->kitPvPData["wheat"] === 3)
                    {
                        $p->getInventory()->addItem(new Item(Item::BREAD));
                        $p->sendMessage("=-=-=-=-=-=-=-=-=-=-=-=-=");
                        $p->sendMessage(" ");
                        $p->sendMessage("Du hast ein Brot erhalten");
                        $p->sendMessage(" ");
                        $p->sendMessage("=-=-=-=-=-=-=-=-=-=-=-=-=");
                        $p->kitPvPData["wheat"] = 0;
                    }
                    break;
                case Item::PUMPKIN:
                    $p->getInventory()->addItem(new Item(Item::PUMPKIN_PIE));
                    $p->sendMessage("=-=-=-=-=-=-=-=-=-=-=-=-=-=-=");
                    $p->sendMessage(" ");
                    $p->sendMessage("Du hast einen Kuchen erhalten");
                    $p->sendMessage(" ");
                    $p->sendMessage("=-=-=-=-=-=-=-=-=-=-=-=-=-=-=");
                    break;
                case Item::MELON_BLOCK:
                    $p->getInventory()->addItem(new Item(Item::MELON));
                    $p->sendMessage("=-=-=-=-=-=-=-=-=-=-=-=-=-=-");
                    $p->sendMessage(" ");
                    $p->sendMessage("Du hast eine Melone erhalten");
                    $p->sendMessage(" ");
                    $p->sendMessage("=-=-=-=-=-=-=-=-=-=-=-=-=-=-");
                    break;
                case Item::DIAMOND_BLOCK:
                    $item = $e->getItem();
                    if($item->getId() === Item::DIAMOND_PICKAXE)
                    {
                        $p->setMaxHealth(26);
                        $p->setHealth(26);
                        $p->getInventory()->remove($item);
                        $p->sendMessage("=-=-=-=-=-=-=-=-=-=-=-=-=-=-");
                        $p->sendMessage(" ");
                        $p->sendMessage("Du hast einen Herzens-Boost erhalten");
                        $p->sendMessage("Du hast jetzt 13 anstatt 10 Herzen");
                        $p->sendMessage("ACHTUNG: Die 3 Zusatz-Herzen sind unsichtbar");
                        $p->sendMessage(" ");
                        $p->sendMessage("=-=-=-=-=-=-=-=-=-=-=-=-=-=-");
                    }
                    break;
                case Item::BOOKSHELF:
                    if($p->kitPvPData["kills"] > 9)
                    {
                        if(!$p->kitPvPData["fire"])
                        {
                            $p->kitPvPData["fire"] = true;
                            $p->sendMessage("=-=-=-=-=-=-=-=-=-=-=-=-=-=-");
                            $p->sendMessage(" ");
                            $p->sendMessage("Dein Schwert zündet nun deine Gegner an");
                            $p->sendMessage(" ");
                            $p->sendMessage("=-=-=-=-=-=-=-=-=-=-=-=-=-=-");
                        }
                    }
                    break;
            }
        }
    }

} 