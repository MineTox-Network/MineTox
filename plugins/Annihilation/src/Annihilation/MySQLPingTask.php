<?php

namespace SurvivalGames;

use pocketmine\scheduler\PluginTask;

class MySQLPingTask extends PluginTask
{
		
    public function __construct(SurvivalGames $plugin)
    {
        parent::__construct($plugin);
        $this->plugin = $plugin;
    }
	

    public function onRun($currentTick)
    {
        $this->plugin->getDatabase()->ping();
    }
}