<?php

namespace onevsone\Arena;

use pocketmine\minetox\MTServer;
use onevsone\onevsone;

class Arena extends MTServer
{
    public $seconds = 210; //3 Minuten 30 Sekunden
    
    public function __construct($id, onevsone $plugin, $ranked = false)
    {
        $this->setGameName("1vs1");
        $this->setName("-1vs1-");
    }



}