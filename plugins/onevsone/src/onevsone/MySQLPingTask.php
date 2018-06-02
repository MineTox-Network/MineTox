<?php

namespace onevsone;

use pocketmine\scheduler\PluginTask;

class MySQLPingTask extends PluginTask
{

    public function __construct(onevsone $plugin)
    {
        parent::__construct($plugin);
        $this->api = $plugin;
    }


    public function onRun($currentTick)
    {
        $this->api->getDatabase()->ping();
    }
}