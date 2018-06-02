<?php

namespace TTT\Arena\ArenaTasks;

use pocketmine\scheduler\Task;

class GetOutOfTesterTask extends Task
{

    public function __construct(Tester $tester)
    {
        $this->tester = $tester;
    }

    public function onRun($currentTick)
    {
        $player = $this->tester->getTestingPlayer();
        if($player->ttt_role == TTTData::$innocent or $player->ttt_role == TTTData::$detective)
        {
            $this->tester->setColorOfLights(0);
        }
        else
        {
            $this->tester->setColorOfLights(1);
        }
        $this->tester->disableBarriers();
        $this->tester->removeTestingPlayer();
    }

} 