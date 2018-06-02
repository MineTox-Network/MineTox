<?php

namespace TTT;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use TTT\ArenaScheduler;
use TTT\Arena\Arena;

class TTT extends PluginBase
{

    private $arenas = [];
    private $smanager = null;

    public function onEnable()
    {
        $servermanager = $this->getServer()->getPluginManager()->getPlugin("MTCore")->getServerManager();
        $this->smanager = $servermanager;

        $this->arenas[1] = new Arena(1, $this);

        $this->arenas[1]->setArenaData("x", 0);
        $this->arenas[1]->setArenaData("y", 0);
        $this->arenas[1]->setArenaData("z", 0);
        $this->arenas[1]->setArenaData("level", $this->getServer()->getDefaultLevel());

        $this->arenas[1]->setArenaData("name", "TTT1");
        $this->arenas[1]->setArenaData("required_Players", 1);
        $this->arenas[1]->setArenaData("creator", "TeamMinetox");
        $this->arenas[1]->setArenaData("displayname", "TTTMinecraft");

        $servermanager->addServer($this->arenas[1]);

        foreach($this->arenas as $arena)
        {
            $this->getServer()->getPluginManager()->registerEvents($arena, $this);
        }

    }

    public function getArenas()
    {
        return $this->arenas;
    }


    public function hasTraitorPoints(Player $player, $points)
    {
        if(isset($player->tpoints) and $player->tpoints >= $points)
        {
            return true;
        }
        return false;
    }

    public function traitorBuy($item, Player $player)
    {
        if($player->ttt_role === TTTData::$traitor)
        {
            switch($item)
            {
                case "list":
                    $player->sendMessage("€ ===-=== Traitor-Shop ===-===");
                    $player->sendMessage("€ 1 => Tester-Spoofer");
                    $player->sendMessage("€ 2 => Medipack");
                    break;
                case 1:
                    if($this->hasTraitorPoints($player, 4))
                    {
                        $player->tpoints -= 4;
                        $player->getInventory()->addItem(Item::get(Item::NETHER_QUARTZ, 0, 1));
                        $player->sendMessage("[TTT] Du hast erfolgreich 'Tester-Spoofer' erworben");
                    }
                    else
                    {
                        $player->sendMessage("[TTT] Du hast nicht genügend Traitor-Punkte");
                    }
                    break;
                case 2:
                    if ($this->hasTraitorPoints($player, 1))
                    {
                        $player->tpoints -= 1;
                        $player->getInventory()->addItem(Item::get(Item::DYE, 14, 1));
                        $player->sendMessage("[TTT] Du hast erfolgreich 'Medipack' erworben");
                    }
                    else
                    {
                        $player->sendMessage("[TTT] Du hast nicht genügend Traitor-Punkte");
                    }
                    break;
            }
        }
        else
        {
            $player->sendMessage("Du bist kein Traitor");
        }
    }

    public function onCommand(CommandSender $sender, Command $command, $label, array $args)
    {
        switch($command->getName())
        {
            case 'b':
                if($sender instanceof Player and $this->smanager->getServerByID($sender->minetoxServer) instanceof Arena)
                {
                    $this->traitorBuy($args[0], $sender);
                    return true;
                }
                else
                {
                    $sender->sendMessage("Dieses Kommando existiert nicht");
                }
                break;
        }
    }

} 