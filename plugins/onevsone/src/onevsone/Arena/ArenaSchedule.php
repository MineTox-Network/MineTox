<?php

namespace onevsone\Arena;

use pocketmine\scheduler\PluginTask;

class ArenaSchedule extends PluginTask 
{
	public function onRun($currentTick)
	{
		foreach($this->getOwner()->getArenas() as $arena)
		{
			--$arena->seconds;
		}
	}
}