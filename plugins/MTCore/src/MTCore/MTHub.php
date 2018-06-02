<?php

namespace MTCore;

use pocketmine\minetox\MTServer;

class MTHub extends MTServer
{
    private $api;

    public function __construct($id, MTCore $plugin, $type)
    {
        $this->setState(0);
        $this->api = $plugin;
        $this->setType($type);
        $this->setMaxPlayers(50);
        if($type === 0)
        {
            $this->setGameName("§aHub");
            $this->setName("-Hub".$id."-");	
        }
        elseif($type === 1)
        {
            $this->setGameName("§6PremiumHub");
            $this->setName("-PremiumHub".$id."-");
        }
    }
}