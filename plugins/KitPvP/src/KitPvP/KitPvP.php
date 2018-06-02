<?php

namespace kitpvp;

use kitpvp\arena\Arena;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\minetox\ServerManager;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class KitPvP extends PluginBase
{

    private $arenas = [];

    public function onEnable()
    {

        $this->arenas[1] = new Arena(1);
        //We're still working on the Map
        $this->getServer()->loadLevel("KitPvP1");
        $level = $this->getServer()->getLevelByName("KitPvP1");
        $this->arenas[1]->setArenaData("x", $level->getSpawnLocation()->getX());
        $this->arenas[1]->setArenaData("y", ($level->getSpawnLocation()->getY() + 1));
        $this->arenas[1]->setArenaData("z", $level->getSpawnLocation()->getZ());
        $this->arenas[1]->setArenaData("level", $level);

        $this->arenas[1]->setArenaData("name", "KitPvP1");
        $this->arenas[1]->setArenaData("display_name", "Deserto"); //Ital. = Wüste

        foreach($this->arenas as $arena)
        {
            $core = $this->getServerManager();
            $core->addServer($arena);
            $this->getServer()->getPluginManager()->registerEvents($arena, $this);
        }

    }

    /**
     * @return ServerManager
     */
    public function getServerManager()
    {
        $core = $this->getServer()->getPluginManager()->getPlugin("MTCore");
        return $core->getServerManager();
    }

    /**
     * @param CommandSender $sender
     * @param Command $command
     * @param string $label
     * @param array $args
     * @return bool
     */
    public function onCommand(CommandSender $sender, Command $command, $label, array $args)
    {
        if($sender instanceof Player)
        {
            $server = $sender->minetoxServer;
            $server = $this->getServerManager()->getServerByID($server);
            if($server->getGameName() === "KitPvP")
            {
                switch($args[0])
                {
                    case 1:
                    case 2:
                    case 3:
                        $sender->kitPvPKit = $args[0];
                        break;
                    default:
                        $sender->kitPvPKit = 1;
                        break;
                }
                if($server instanceof Arena)
                {
                    $server->equipPlayer($sender);
                    $sender->sendMessage("Dein Kit wurde geändert");
                }
                return true;
            }
            else
            {
                return true;
            }
        }
    }

} 