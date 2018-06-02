<?php

/*
*  _____                              __  __   _                  _                  
* |_   _|   ___    __ _   _ __ ___   |  \/  | (_)  _ __     ___  | |_    ___   __  __
*   | |    / _ \  / _` | | '_ ` _ \  | |\/| | | | | '_ \   / _ \ | __|  / _ \  \ \/ /
*   | |   |  __/ | (_| | | | | | | | | |  | | | | | | | | |  __/ | |_  | (_) |  >  < 
*   |_|    \___|  \__,_| |_| |_| |_| |_|  |_| |_| |_| |_|  \___|  \__|  \___/  /_/\_\
* 
*/
namespace MTCore\Task;

use pocketmine\scheduler\PluginTask;
use pocketmine\minetox\MTServer;
use pocketmine\tile\Sign;
use MTCore\MTCore;

class MTSignManager extends PluginTask 
{
    public function onRun($currentTick)
    {
        
        foreach($this->getOwner()->getServer()->getLevels() as $l)
        {
            foreach($l->getTiles() as $tile)
            {
                if($tile instanceof Sign)
                {
                    $text = $tile->getText();
                    $owner = $this->getOwner();
                    if($owner instanceof MTCore)
                    {
                        $server = $owner->getServerManager()->getServerByID($text[0]);
                        if($server instanceof MTServer)
                        {
                            if($server->getState() === 0)
                            {
                                $state = "§2[Betreten]";
                            }
                            elseif($server->getState() === 0 and $server->getPlayerCount() >= $server->getMaxPlayers())
                            {
                                $state = "§6[Premium]";
                            }
                            else
                            {
                                $state = "[Unbekannt]";
                            }
                            $players = $server->getPlayerCount()."/".$server->getMaxPlayers();
                            if($server->getState() === 0)
                            {
                                $tile->setText($server->getName(), $state, $server->getMapData("displayname"), $players);
                            }
                            else
                            {
                                $tile->setText($server->getName(), "-=-=-=-=-=-", "Server", "lädt...");
                            }
                        }
                    }
                }
            }
        }
    }
} 