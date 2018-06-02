<?php

namespace SurvivalGames;

use pocketmine\scheduler\PluginTask;

class MySQLPingTask extends PluginTask
{
		
    public function __construct(SurvivalGames $plugin)
    {
        parent::__construct($plugin);
        $this->api = $plugin;
    }
	

    public function onRun($currentTick)
    {
        $this->api->getDatabase()->ping();
    }
}