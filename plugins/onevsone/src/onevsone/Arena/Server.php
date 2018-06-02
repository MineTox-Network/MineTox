<?php

namespace onevsone\Arena;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\minetox\MTServer;
use pocketmine\event\Listener;
use pocketmine\Player;
use onevsone\onevsone;

class Server extends MTServer implements Listener
{
    public $seconds = 210; //3 Minuten 30 Sekunden

    public function __construct($id, onevsone $plugin, $type = 0)
    {
        $this->setGameName("1vs1");
        $this->setMaxPlayers(20);
        $this->setName("-1vs1-");
        $this->arenatype = $type;
        $this->api = $plugin;
        $this->setType(2);
        $this->setHub($this->api->getName());
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        if($this->isPlayerOnline($player))
        {
            //Interaction to Dummy Players.... [Rankend or not]
        }
    }

    public function onDamage(EntityDamageEvent $event)
    {
        $player = $event->getEntity();
        if($player instanceof player)
        {
            if($this->isPlayerOnline($player))
            {
                if(!$player->pvp)
                {
                    $event->setCancelled(true);
                }
            }
        }
    }

}