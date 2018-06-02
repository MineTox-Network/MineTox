<?php

namespace TTT\Arena;

use pocketmine\block\Block;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\Server;
use TTT\Arena\ArenaTasks\GetOutOfTesterTask;

class Tester
{

    //Static values for configuration
    public static $tester_time = 5;

    //Values per tester
    private $lights = [];
    private $barriers = [];
    private $arena;

    //The currently testing player
    private $testing = null;

    //Functions for each tester
    public function __construct(Arena $arena)
    {
        $this->arena = $arena;
    }

    public function getTestingPlayer()
    {
        return $this->testing;
    }

    public function removeTestingPlayer()
    {
        $this->testing = null;
    }

    public function addLight(Block $block)
    {
        $this->lights[] = $block;
    }

    public function addBarrier(Position $block)
    {
        $this->barriers[] = $block;
    }

    public function testPlayer(Player $player)
    {
        if($this->testing == null or !isset($player->ttt_role))
        {
            $this->testing = $player;
            $this->setColorOfLights(2);
            $this->enableBarriers();
            Server::getInstance()->getScheduler()->scheduleDelayedTask(new GetOutOfTesterTask($this), Tester::$tester_time);
            $this->arena->broadcastMessage("[TTT] ".$player->getDisplayName()." hat den Tester betreten");
        }
    }


    //Function controlling the lights
    public function setColorOfLights($color = 0)
    {
        //Colors:
        //0 = Green (Innocent, Detective, Spoofing Traitor)
        //1 = Red (Traitor)9
        //2 = Yellow (Normal)

        foreach($this->lights as $light)
        {
            if($light instanceof Block and $light->getId() === Block::WOOL)
            {
                if($color == 0)
                {
                    $light->setDamage(5); //Make it green
                }
                elseif($color == 1)
                {
                    $light->setDamage(14); //Make it red
                }
                else
                {
                    $light->setDamage(4); //Make it yellow
                }
            }
        }
    }

    //Functions controlling the barriers
    public function enableBarriers()
    {
        foreach($this->barriers as $barrier)
        {
            if($barrier instanceof Position)
            {
                $pos = new Vector3($barrier->getX(), ($barrier->getY() + 1), $barrier->getZ());
                $barrier->getLevel()->setBlock($pos, Block::get(Block::GLASS), true);
            }
        }
    }

    public function disableBarriers()
    {
        foreach($this->barriers as $barrier)
        {
            if($barrier instanceof Position)
            {
                $pos = new Vector3($barrier->getX(), ($barrier->getY() + 1), $barrier->getZ());
                $barrier->getLevel()->setBlock($pos, Block::get(Block::AIR), true);
            }
        }
    }


} 