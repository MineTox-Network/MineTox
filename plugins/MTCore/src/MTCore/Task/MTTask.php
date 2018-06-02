<?php

namespace MTCore\Task;

use MTCore\MTCore;
use pocketmine\scheduler\PluginTask;

class MTTask extends PluginTask
{
    private $api;

    public function __construct(MTCore $plugin)
    {
        parent::__construct($plugin);
        $this->api = $plugin;
    }

    public function onRun($currentTick)
    {
        $timer = $this->api->timer;
        ++$timer;
        if($timer <= 2)
        {
            $this->api->LogStatus();
        }
        else
        {
            $this->api->timer = 0;
        }
    }
}