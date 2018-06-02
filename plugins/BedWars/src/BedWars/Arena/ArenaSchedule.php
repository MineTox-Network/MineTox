<?php

namespace BedWars\Arena;

use pocketmine\scheduler\PluginTask;
use pocketmine\scheduler\Task;
use pocketmine\scheduler\ServerScheduler;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\level\Explosion;
use pocketmine\level\Position;
use mcg76\game\ctf\CTFPlugin;
use pocketmine\utils\TextFormat;
use pocketmine\level\Level; 
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\tile\Sign;

class ArenaSchedule extends PluginTask {
	private $plugin;
	private $cas0 = 0;
        private $cas1 = 0;
        private $cas2 = 0;
        private $cas3 = 0;
        
	public function __construct(BedWars $plugin) {
		$this->plugin = $plugin;
		parent::__construct ( $plugin );
	}
	
	public function onRun($ticks) {   
        $inGamePlayers = array_merge($this->getPlugIn()->blueTeamPlayers, $this->getPlugIn()->redTeamPlayers, $this->getPlugIn()->yellowTeamPlayers, $this->getPlugIn()->greenTeamPlayers, $this->getPlugIn()->lobbyPlayers); 
        $inGamePlayers1 = array_merge($this->getPlugIn()->blueTeamPlayers, $this->getPlugIn()->redTeamPlayers, $this->getPlugIn()->yellowTeamPlayers, $this->getPlugIn()->greenTeamPlayers);
        if(count($inGamePlayers) >= 12 && $this->getPlugIn()->ingame == false){
            $this->getPlugIn()->starting = true;
        }
        if(count($inGamePlayers) <= 0 && $this->getPlugIn()->ingame == false){
            $this->getPlugIn()->starting = false;
        }
            if ($this->getPlugIn()->starting == true) {   
                $this->cas0++;
                if($this->cas0 == 4){
                    foreach($inGamePlayers as $p){
                        $p->sendMessage(TextFormat::AQUA."Bedwars starting in 50 seconds");
                    }
                }

                if($this->cas0 == 5){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 49 seconds");
                    }
                }
                if($this->cas0 == 6){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 48 seconds");
                    }
                }
                if($this->cas0 == 7){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 47 seconds");
                    }
                }
                if($this->cas0 == 8){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 46 seconds");
                    }
                }
                if($this->cas0 == 9){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 45 seconds");
                    }
                }
                if($this->cas0 == 10){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 44 seconds");
                    }
                }
                if($this->cas0 == 11){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 43 seconds");
                    }
                }
                if($this->cas0 == 12){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 42 seconds");
                    }
                }
                if($this->cas0 == 13){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 41 seconds");
                    }
                }
                if($this->cas0 == 14){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 40 seconds");
                    }
                }
                if($this->cas0 == 15){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 39 seconds");
                    }
                }
                if($this->cas0 == 16){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 38 seconds");
                    }
                }
                if($this->cas0 == 17){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 37 seconds");
                    }
                }
                if($this->cas0 == 18){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 36 seconds");
                    }
                }
                if($this->cas0 == 19){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 35 seconds");
                    }
                }
                if($this->cas0 == 20){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 34 seconds");
                    }
                }
                if($this->cas0 == 21){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 33 seconds");
                    }
                }
                if($this->cas0 == 22){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 32 seconds");
                    }
                }
                if($this->cas0 == 23){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 31 seconds");
                    }
                }
                if($this->cas0 == 24){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 30 seconds");
                    }
                }
                if($this->cas0 == 25){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 29 seconds");
                    }
                }
                if($this->cas0 == 26){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 28 seconds");
                    }
                }
                if($this->cas0 == 27){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 27 seconds");
                    }
                }
                if($this->cas0 == 28){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 26 seconds");
                    }
                }
                if($this->cas0 == 29){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 25 seconds");
                    }
                }
                if($this->cas0 == 30){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 24 seconds");
                    }
                }
                if($this->cas0 == 31){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 23 seconds");
                    }
                }
                if($this->cas0 == 32){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 22 seconds");
                    }
                }
                if($this->cas0 == 33){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 21 seconds");
                    }
                }
                if($this->cas0 == 34){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 20 seconds");
                    }
                }
                if($this->cas0 == 35){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 19 seconds");
                    }
                }
                if($this->cas0 == 36){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 18 seconds");
                    }
                }
                if($this->cas0 == 37){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 17 seconds");
                    }
                }
                if($this->cas0 == 38){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 16 seconds");
                    }
                }
                if($this->cas0 == 39){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 15 seconds");
                    }
                }
                if($this->cas0 == 40){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 14 seconds");
                    }
                }
                if($this->cas0 == 41){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 13 seconds");
                    }
                }
                if($this->cas0 == 42){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 12 seconds");
                    }
                }
                if($this->cas0 == 43){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 11 seconds");
                    }
                }
                if($this->cas0 == 44){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 10 seconds");
                    }
                }
                if($this->cas0 == 45){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 9 seconds");
                    }
                }
                if($this->cas0 == 46){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 8 seconds");
                    }
                }
                if($this->cas0 == 47){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 7 seconds");
                    }
                }
                if($this->cas0 == 48){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 6 seconds");
                    }
                }
                if($this->cas0 == 50){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 5 seconds");
                    }
                }
                if($this->cas0 == 51){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 4 seconds");
                    }
                }
                if($this->cas0 == 52){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 3 seconds");
                    }
                }
                if($this->cas0 == 53){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 2 seconds");
                    }
                }
                if($this->cas0 == 54){
                    foreach($inGamePlayers as $p){
                        $p->sendPopup(TextFormat::AQUA."Bedwars starting in 1 seconds");
                    }
                }
                if($this->cas0 == 55){
                    if(count($inGamePlayers1) >= 8){
                    $this->getManager()->startGame();
                    $this->getPlugIn()->starting = false;
                    $this->cas0 = 0;
                    }
                    else{
                        $this->getPlugIn()->starting = false;
                        $this->cas0 = 0;
                        foreach($inGamePlayers as $p){
                            $p->sendMessage(TextFormat::RED."Not enough players");
                        }
                    }
                }
            }
        if($this->getPlugIn()->ingame == true) {    
            if($this->cas2 == 0){
                $this->getBuilder()->dropBrickItems($this->getPlugIn()->bwLevel);               
            }
            $this->cas2++;
                if($this->cas2 == 2){
                    $this->cas2 = 0;
                }

        if($this->cas0 == 0){
            $this->getBuilder()->dropGoldItems($this->getPlugIn()->bwLevel);
            $this->getBuilder()->dropIronItems($this->getPlugIn()->bwLevel);
        }
        $this->cas0++;
        
        if ($this->cas0 == 15){
            $this->getBuilder()->dropIronItems($this->getPlugIn()->bwLevel);
        }
        
        if ($this->cas0 == 45){
            $this->cas0 = 0;
        }
        }
        $gameStat;
        if($this->getPlugIn()->ingame == true){
            $gameStat = TextFormat::RED."in-game";
        }
        if($this->getPlugIn()->ingame == false && $this->getPlugIn()->restart == false){
            $gameStat = TextFormat::GREEN."lobby";
        }
        if($this->getPlugIn()->restart == true){
            $gameStat = TextFormat::RED."restarting";
        }
        $level0 = Server::getInstance()->getLevelByName("lobby");
        $level = Server::getInstance()->getLevelByName("bw1");
        $sign = $level0->getTile(new Vector3(122, 5, 127));

        if($sign instanceof Sign){
            $sign->setText(TextFormat::DARK_AQUA."[BW1]", $gameStat, TextFormat::AQUA.count($inGamePlayers).TextFormat::BLACK."/".TextFormat::AQUA."16", TextFormat::AQUA."map: ".TextFormat::ITALIC.TextFormat::WHITE."kingdoms");
        }
        if($this->getPlugIn()->restart == false){
            $signb = $level->getTile(new Vector3(133, 36, 921));
            $signr = $level->getTile(new Vector3(131, 36, 944));
            $signg = $level->getTile(new Vector3(121, 36, 931));
            $signy = $level->getTile(new Vector3(143, 36, 933));
            
            if($signb instanceof Sign){
                
                $signb->setText(TextFormat::DARK_BLUE."[BLUE]","",TextFormat::GRAY.count($this->getPlugIn()->blueTeamPlayers)."/4");
            }
            if($signr instanceof Sign){
                
                $signr->setText(TextFormat::DARK_RED."[RED]","",TextFormat::GRAY.count($this->getPlugIn()->redTeamPlayers)."/4");
            }
            if($signg instanceof Sign){
                
                $signy->setText(TextFormat::DARK_GREEN."[GREEN]","",TextFormat::GRAY.count($this->getPlugIn()->greenTeamPlayers)."/4");
            }
            if($signy instanceof Sign){
                
                $signg->setText(TextFormat::YELLOW."[YELLOW]","",TextFormat::GRAY.count($this->getPlugIn()->yellowTeamPlayers)."/4");
            }
        }
        if($this->getPlugIn()->restart == true){
            if($this->cas3 == 0){
                    $this->getManager()->deleteWorld("bw1");
            }
            $this->cas3++;
            if($this->cas3 == 5){
                $this->getManager()->addWorld("bw1");
                $this->cas3 = 0;
                $this->getPlugIn()->restart = false;
            }
            
            
        }
        }
        
	

    
	
	public function onCancel() {
	}
	
	protected function getManager() {
		return $this->getPlugIn ()->ctfManager;
	}
	protected function getPlugIn() {
		return $this->plugin;
	}
	protected function getSetup() {
		return $this->getPlugIn ()->ctfSetup;
	}
	protected function getBuilder() {
		return $this->getPlugIn ()->ctfBuilder;
	}	
	protected function log($msg) {
		return $this->getPlugIn()->getLogger()->info($msg);
	}
}