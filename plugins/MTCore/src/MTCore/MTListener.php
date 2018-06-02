<?php

namespace MTCore;

use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\minetox\MTUtility;
use pocketmine\minetox\MTServer;
use pocketmine\event\Listener;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;

class MTListener implements Listener
{
    private $api;


    public function __construct(MTCore $plugin)
    {
        $this->api = $plugin;
    }

    public function getAccountManager()
    {
        return $this->getOwner()->getAccountManager();
    }

    public function getOwner()
    {
        return $this->api;
    }
    /**
     * @param Player $player
     * @param $level
     */
    public function hidePlayer(Player $player, $level)
    {
        if(!$player->isHider)
        {
            $player->isHider = true;
            foreach(Server::getInstance()->getLevelByName($level)->getPlayers() as $p)
            {
                $player->hidePlayer($p);
            }
        }
        else
        {
            $player->isHider = false;
            foreach(Server::getInstance()->getLevelbyName($level)->getPlayers() as $p)
            {
                $player->showPlayer($p);
            }
        }
    }

    public function onMove(PlayerMoveEvent $event)
    {
        if(!$event->getPlayer()->move)
        {
            $to = clone $event->getFrom();
            $to->yaw = $event->getTo()->yaw;
            $to->pitch = $event->getTo()->pitch;
            $event->setTo($to);
        }
    }


    /**
     * @param PlayerPreLoginEvent $event
     *
     * @priority HIGHEST
     */
    public function onPlayerPreLogin(PlayerPreLoginEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $player->rank = $this->getAccountManager()->getRank($name);
        $player->prefix = $this->getAccountManager()->getPrefix($name);
        if(count(server::getInstance()->getOnlinePlayers()) <= 50)
        {
            foreach(server::getInstance()->getOnlinePlayers() as $p)
            {
                if($p !== $player and strtolower($name) === strtolower($p->getName()))
                {
                    if($p->auth)
                    {
                        $event->setCancelled(true);
                        $event->setKickMessage("§4Ein Spieler mit diesem Namen ist bereits auf dem Server");
                    }
                }
            }
            $player->isNicked = false;
        }
        else
        {
            if($player->rank >= 1)
            {
                foreach(server::getInstance()->getOnlinePlayers() as $p)
                {
                    if($p !== $player and strtolower($name) === strtolower($p->getName()))
                    {
                        if($p->auth)
                        {
                            $event->setCancelled(true);
                            $event->setKickMessage("§4Ein Spieler mit diesem Namen ist bereits auf dem Server");
                        }
                    }
                }
                $player->auth = false;
                $player->isNicked = false;
                $player->minetoxServer = false;
            }
            else
            {
                $event->setCancelled(true);
                $event->setKickMessage("§4Das Spielerlimit von ".$this->getOwner()->getServer()->getMaxPlayers()." Spielern ist erreicht.");
            }
        }
        /**
         * Checks if a Player is trying to join with one of our Nicknames
         */
        if(MTUtility::isNickName($player->getName()))
        {
            $event->setCancelled(true);
            $event->setKickMessage("§cDu wurdest §4PERMANENT §cvom Netzwerk gebannt\n         §3Grund:  §cUnerlaubter Username");
        }
        $player->popupMessage = "";
    }

    /**
     * @param Player $player
     */
    public function assignVariables(Player $player)
    {
        $player->pvp = false;
        $player->isHider = false;
        $player->items = time();
        $player->interval = time();
        $name = $player->getName();
        $player->autoNick= $this->getAccountManager()->autonick($name);
    }

    /**
     * @param PlayerJoinEvent $event
     *
     * @priority MONITOR
     */
    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $this->assignVariables($player);
        $name = $player->getName();
        $event->setJoinMessage("");
        $hub = $this->getOwner()->getServerManager()->getServerByID("-Hub1-");
        if($hub instanceof MTServer)
        {
            $hub->addPlayer($player);

        }
        else
            $this->getOwner()->sendError($player, 56515, 3);

        if(!$this->getAccountManager()->isPlayerRegistered($name))
        {
            $player->sendMessage("§7==========================================");
            $player->sendMessage("§e>> Willkommen auf §6Minetox§e, ".MTUtility::getColoredName($player));
            $player->sendMessage("§e>> Der Account wurde noch nicht registriert");
            $player->sendMessage("§e>> Du kannst ihn mit §c/register §eregistrieren");
            $player->sendMessage("§7==========================================");
            $this->getOwner()->giveSpawnItems($player, true);
            $player->auth = true;
        }
        else
        {
            $player->sendMessage("§7==========================================");
            $player->sendMessage("§e>> Willkommen auf §6Minetox§e, ".MTUtility::getColoredName($player));
            $player->sendMessage("§e>> Dieser Account ist bereits registriert");
            $player->sendMessage("§e>> Melde dich mit §c/login §ean oder ändere");
            $player->sendMessage("§e>> deinen Namen in den MCPE-Einstellungen.");
            $player->sendMessage("§7==========================================");
            $player->auth = false;
        }
        $player->move = true;
        $this->getOwner()->increasePlayerCount(1);
        $this->getOwner()->getServer()->getNetwork()->setName("§6Minetox: §ePE Server [".$this->getOwner()->getPlayerCount(1)."/500]");
    }

    /**
     * @param PlayerCommandPreprocessEvent $event
     *
     * @priority MONITOR
     */
    public function onPlayerCommand(PlayerCommandPreprocessEvent $event)
    {
        $player = $event->getPlayer();
        if(!$player->auth )
        {
            $message = $event->getMessage();
            if($message{0} === "/")
            {
                $event->setCancelled(true);
                $command = substr($message, 1);
                $args = explode(" ", $command);
                if($args[0] === "register" or $args[0] === "login")
                {
                    $this->getOwner()->getServer()->dispatchCommand($player, $command);
                }
                else
                {
                    $player->sendMessage("§4>> Bitte logge dich zuerst ein");
                }
            }
        }
    }

    /**
     * @param PlayerChatEvent $e
     *
     * @priority MONITOR
     */
    public function onChat(PlayerChatEvent $e)
    {
        $player = $e->getPlayer();
        if($player->auth)
        {
            $name = $player->getName();
            $nachricht = $e->getMessage();
            $nickname = $player->getDisplayName();
            $players = $player->getLevel()->getPlayers();
            $server = $this->getOwner()->getServerManager()->getServerByID($player->minetoxServer);
            if($server->getType() === 0)
            {
                if($player->rank >= 2)
                {
                    if((time() - $player->interval) < 1)
                    {
                        $player->sendMessage("§7[§6Minetox§7] §4Du chattest zu schnell!");
                    }
                    else
                    {
                        Server::getInstance()->getLogger()->info("§2[Chat] [" .$server->getName(). "] §3". $name ." §2als §3" . $nickname ." §2>> " . $nachricht);
                        foreach ($players as $player)
                        {
                            $player->sendMessage($nickname." §3» §f".$nachricht);
                        }
                    }
                }
                else
                {
                    $player->sendMessage("§c>> Um Chatten zu können benötigst du den §6Premium-Rang");
                }
            }
            else
            {
                if((time() - $player->interval) < 1)
                {
                    $player->sendMessage("§7[§6Minetox§7] §cDu chattest zu schnell!");
                }
                else
                {
                    Server::getInstance()->getLogger()->info("§2[Chat] [" .$server->getName(). "] §3". $name . " §2als §3". $nickname . " §3>> " . $nachricht);
                    foreach ($players as $player)
                    {
                        $player->sendMessage($nickname." §3» §f".$nachricht);
                    }
                }
            }
        }
        else
        {
            if(!(time() - $player->interval) < 1)
            {
                if($e->getMessage() === $this->getAccountManager()->getPasswort($player->getName()))
                {
                    $player->sendMessage("§7[§6Minetox§7] §2Du hast dich erfolgreich eingeloggt");
                    $this->getOwner()->giveSpawnItems($player, true);
                    $player->auth = true;
                }
                else
                    $player->sendMessage("§7[§6Minetox§7] §cDein Passwort ist falsch");
            }
            else
            {
                $player->sendMessage("§7[§6Minetox§7] §cPasswort Interval überschritten!");
            }
        }
        $player->interval = time();
        $e->setCancelled(true);
    }

    /**
     * @param PlayerInteractEvent $e
     */
    public function onInteract(PlayerInteractEvent $e)
    {
        $item = $e->getItem();
        $player = $e->getPlayer();
        $level = $player->getLevel()->getName();
        $server = $this->getOwner()->getServerManager()->getServerByID($player->minetoxServer);
        if($server instanceof MTServer)
        {
            if($server->getType() == 0)
            {
                switch($item->getID())
                {
                    case 347:
                        if($player->auth)
                        {
                            $player->items = time();
                            if(!$player->isHider)
                            {
                                $player->sendMessage("§7[§6Minetox§7] §eSpieler sind nun unsichtbar");
                                $this->hidePlayer($player, $level);
                            }
                            else
                            {
                                $player->sendMessage("§7[§6Minetox§7] §eSpieler sind nun wieder sichtbar");
                                $this->hidePlayer($player, $level);
                            }
                            $player->items = time();
                        }
                        else
                        {
                            $player->sendMessage("§7[§6Minetox§7] §cBitte logge dich zuerst ein");
                        }
                        $e->setCancelled(true);
                        break;
                    case 266:
                        if($player->auth)
                        {
                            if((time() - $player->items) < 2)
                                $player->sendMessage("§7[§6Minetox§7] §cDu kannst dieses Item in §e2 §cSekunden wieder benutzen");
                            else
                            {
                                $player->items = time();
                                $e->setCancelled(true);
                                $player->sendMessage("§7[§6Minetox§7] §eDu wurdest in die §6Premium Hub  §everschoben");
                                $player->teleport(new position($player->getX(), $player->getY(), $player->getZ(), server::getInstance()->getLevelByName("premiumhub1")));
                            }
                        }
                        else
                        {
                            $player->sendMessage("§7[§6Minetox§7] §cBitte logge dich zuerst ein");
                        }
                        break;
                    case 265:
                        if((time() - $player->items) < 2)
                            $player->sendMessage("§7[§6Minetox§7] §cDu kannst dieses Item in §e2 §cSekunden §cwieder benutzen");
                        else
                        {
                            if($player->auth )
                            {
                                $player->sendMessage("§7[§6Minetox§7] §cDu wurdest in die Hub verschoben");
                                $player->teleport(new position($player->getX(), $player->getY(), $player->getZ(), server::getInstance()->getLevelByName("hub1")));
                                $player->items = time();
                                $e->setCancelled(true);
                            }
                            else
                                $player->sendMessage("§7[§6Minetox§7] §cBitte logge dich zuerst ein");
                        }
                        break;
                }
            }
        }
    }

    /**
     * @param BlockPlaceEvent $e
     *
     * @priority HIGHEST
     */
    public function onPlace(BlockPlaceEvent $e)
    {
        $player = $e->getPlayer();
        $server = $this->getOwner()->getServerManager()->getServerByID($player->minetoxServer);
        if($server->getType() === 0)
        {
            $e->setCancelled(true);
        }
    }

    /**
     * @param BlockBreakEvent $e
     *
     * @priority HIGHEST
     */
    public function onBreak(BlockBreakEvent $e)
    {
        $player = $e->getPlayer();
        $server = $this->getOwner()->getServerManager()->getServerByID($player->minetoxServer);
        if($server->getType() === 0)
        {
            $e->setCancelled(true);
        }
    }

    /**
     * @param EntityLevelChangeEvent $e
     */
    public function onLevelChange(EntityLevelChangeEvent $e)
    {
        $player = $e->getEntity();
        if($player instanceof Player)
        {
            $server = $this->getOwner()->getServerManager()->getServerByID($player->minetoxServer);
            if($server->getType() === 0)
            {
                $target2 = $e->getTarget();
                foreach($target2->getPlayers() as $p)
                {
                    if($p->isHider)
                    {
                        $p->hidePlayer($player);
                    }
                }
            }
            else
            {
                if($player->isHider)
                {
                    $player->isHider = false;
                    foreach(server::getInstance()->getOnlinePlayers() as $p)
                    {
                        $player->showPlayer($p);
                    }
                }
            }
        }
    }

    /**
     * @param EntityDamageEvent $event
     * Disables PVP on Hubs
     */
    public function onDamage(EntityDamageEvent $event)
    {
        $entity = $event->getEntity();
        if($entity instanceof Player)
        {
            $server = $this->getOwner()->getServerManager()->getServerByID($entity->minetoxServer);
            if($server->getType() === 0)
            {
                $event->setCancelled(true);
            }

        }
    }

    /**
     * @param PlayerDeathEvent $event
     * Disables the Death Message
     */
    public function onDeath(PlayerDeathEvent $event)
    {
        $event->setDeathMessage("");
    }

    /**
     * @param PlayerQuitEvent $event
     * Disables the Quit Message
     */
    public function onQuit(PlayerQuitEvent $event)
    {
        $event->setQuitMessage("");
    }

    /**
     * @param PlayerDropItemEvent $event
     * Disables that the Player can drop items on Hubs
     */
    public function onItemDrop(PlayerDropItemEvent $event)
    {
        $player = $event->getPlayer();
        $server = $this->getOwner()->getServerManager()->getServerByID($player->minetoxServer);
        if($server->getType() === 0)
        {
            $event->setCancelled(true);
        }
    }

}