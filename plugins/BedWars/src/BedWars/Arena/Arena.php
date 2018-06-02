<?php

namespace BedWars\Arena;

use pocketmine\minetox\PlayerEvents;
use pocketmine\minetox\MTMinigame;
use pocketmine\minetox\MTServer;
use BedWars\BedWars;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\level\Explosion;
use pocketmine\event\block\BlockEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\math\Vector3 as Vector3;
use pocketmine\math\Vector2 as Vector2;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\network\protocol\UpdateBlockPacket;
use pocketmine\block\Block;
use pocketmine\network\protocol\Info;
use pocketmine\network\protocol\LoginPacket;
use pocketmine\command\defaults\TeleportCommand;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;
use pocketmine\scheduler\PluginTask;
use pocketmine\inventory\BaseInventory;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\entity\Effect;
use pocketmine\entity\InstantEffect;
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\level\particle\FlameParticle;
use pocketmine\level\sound\FizzSound;
use pocketmine\event\entity\EntityShootBowEvent;

/**
 * Class Arena
 * @package BedWars\Arena
 * Sulfatezz: This is our "Listener"
 */
class Arena extends MTServer implements Listener, MTMinigame, PlayerEvents
{

    	public function __construct(BedWars $plugin) {
		parent::__construct ( $plugin );
	}
        
            
	public function onBlockBreak(BlockBreakEvent $event) { 
                $inGamePlayers = array_merge($this->getPlugIn()->blueTeamPlayers, $this->getPlugIn()->redTeamPlayers, $this->getPlugIn()->yellowTeamPlayers, $this->getPlugIn()->greenTeamPlayers);
		$player = $event->getPlayer ();
		$b = $event->getBlock ();                
		if ($this->getPlugin ()->pos_display_flag == 1) {
			$event->getPlayer ()->sendMessage ( "BREAKED: [x=" . $b->x . " y=" . $b->y . " z=" . $b->z . "]" );
		}
		// @fix1- team can only break enermy flag and not own
		$redTeamFlagPos = $this->getSetup ()->getFlagPos ( CTFSetup::CTF_FLAG_RED_TEAM );
		if ((round ( $b->x ) == round ( $redTeamFlagPos->x ) && round ( $b->y ) == round ( $redTeamFlagPos->y ) && round ( $b->z ) == round ( $redTeamFlagPos->z ))) {
			if (isset ( $this->getPlugIn()->redTeamPlayers [$player->getName ()] )) {
				// update again to fix color issue
				$this->getBuilder ()->addRedTeamFlag ( $player->getLevel (), 26, 0 );
				$event->setCancelled ( true );
                                $player->sendMessage(TextFormat::RED."You can not destroy your own bed!");
			}
		}
		$blueTeamFlagPos = $this->getSetup ()->getFlagPos ( CTFSetup::CTF_FLAG_BLUE_TEAM );
                $blueTeamFlag2Pos = $this->getSetup ()->getFlagPos ( CTFSetup::CTF_FLAG2_BLUE_TEAM );
		if ((round ( $b->x ) == round ( $blueTeamFlagPos->x ) && round ( $b->y ) == round ( $blueTeamFlagPos->y ) && round ( $b->z ) == round ( $blueTeamFlagPos->z ))) {
			if (isset ( $this->getPlugIn()->blueTeamPlayers [$player->getName ()] )) {
				// update again to fix color issue
				$this->getBuilder ()->addBlueTeamFlag ( $player->getLevel (), 26, 0 );
				$event->setCancelled ( true );
                                $player->sendMessage(TextFormat::RED."You can not destroy your own bed!");
			}
		}	
		$yellowTeamFlagPos = $this->getSetup ()->getFlagPos ( CTFSetup::CTF_FLAG_YELLOW_TEAM );
		if ((round ( $b->x ) == round ( $yellowTeamFlagPos->x ) && round ( $b->y ) == round ( $yellowTeamFlagPos->y ) && round ( $b->z ) == round ( $yellowTeamFlagPos->z ))) {
			if (isset ( $this->getPlugIn()->yellowTeamPlayers [$player->getName ()] )) {
				// update again to fix color issue
				$this->getBuilder ()->addYellowTeamFlag ( $player->getLevel (), 26, 0 );
				$event->setCancelled ( true );
                                $player->sendMessage(TextFormat::RED."You can not destroy your own bed!");
			}
		}
		$greenTeamFlagPos = $this->getSetup ()->getFlagPos ( CTFSetup::CTF_FLAG_GREEN_TEAM );
		if ((round ( $b->x ) == round ( $greenTeamFlagPos->x ) && round ( $b->y ) == round ( $greenTeamFlagPos->y ) && round ( $b->z ) == round ( $greenTeamFlagPos->z ))) {
			if (isset ( $this->getPlugIn()->greenTeamPlayers [$player->getName ()] )) {
				// update again to fix color issue
				$this->getBuilder ()->addGreenTeamFlag ( $player->getLevel (), 26, 0 );
				$event->setCancelled ( true );
                                $player->sendMessage(TextFormat::RED."You can not destroy your own bed!");
			}
		}
                if (strtolower ( $player->level->getName () ) != strtolower ( $this->getSetup ()->getCTFWorldName () )) {
                    $event->setCancelled(true);
                }
                //kdyz hraci zbori cizi postel      
                
		if ((round ( $b->x ) == round ( $redTeamFlagPos->x ) && round ( $b->y ) == round ( $redTeamFlagPos->y ) && round ( $b->z ) == round ( $redTeamFlagPos->z ))) {
			if (!isset ( $this->getPlugIn()->redTeamPlayers [$player->getName ()] )) {		
				$player->getLevel ()->getServer ()->broadcastMessage ($player->getNameTag()." "."§3destroyed bed of §4red§3 team");
                                $this->getPlugIn()->redBed = 1;
                                $player->getLevel()->addParticle(new ExplodeParticle(new Vector3($b->x, $b->y, $b->z)), Server::getInstance()->getOnlinePlayers());
                                $player->getLevel()->addParticle(new FlameParticle(new Vector3($b->x, $b->y, $b->z)), Server::getInstance()->getOnlinePlayers());
                                $player->getLevel()->addSound(new FizzSound(new Vector3($b->x, $b->y, $b->z)), $inGamePlayers);
                                $this->getPlugIn()->addBed($player->getName());
			}
		}
		
		if ((round ( $b->x ) == round ( $blueTeamFlagPos->x ) && round ( $b->y ) == round ( $blueTeamFlagPos->y ) && round ( $b->z ) == round ( $blueTeamFlagPos->z ))) {
			if (!isset ( $this->getPlugIn()->blueTeamPlayers [$player->getName ()] )) {
				$player->getLevel ()->getServer ()->broadcastMessage($player->getNameTag()." "."§3destroyed bed of §1blue§3 team");
				$this->getPlugIn()->blueBed = 1;
                                $player->getLevel()->addParticle(new ExplodeParticle(new Vector3($b->x, $b->y, $b->z)), Server::getInstance()->getOnlinePlayers());
                                $player->getLevel()->addParticle(new FlameParticle(new Vector3($b->x, $b->y, $b->z)), Server::getInstance()->getOnlinePlayers());
                                $player->getLevel()->addSound(new FizzSound(new Vector3($b->x, $b->y, $b->z)), $inGamePlayers);
                                $this->getPlugIn()->addBed($player->getName());
                        }
		}	
		
		if ((round ( $b->x ) == round ( $yellowTeamFlagPos->x ) && round ( $b->y ) == round ( $yellowTeamFlagPos->y ) && round ( $b->z ) == round ( $yellowTeamFlagPos->z ))) {
			if (!isset ( $this->getPlugIn()->yellowTeamPlayers [$player->getName ()] )) {
				$player->getLevel ()->getServer ()->broadcastMessage($player->getNameTag()." "."§3destroyed bed of §eyellow§3 team");
				$this->getPlugIn()->yellowBed = 1;
                                $player->getLevel()->addParticle(new ExplodeParticle(new Vector3($b->x, $b->y, $b->z)), Server::getInstance()->getOnlinePlayers());
                                $player->getLevel()->addParticle(new FlameParticle(new Vector3($b->x, $b->y, $b->z)), Server::getInstance()->getOnlinePlayers());
                                $player->getLevel()->addSound(new FizzSound(new Vector3($b->x, $b->y, $b->z)), $inGamePlayers);
                                $this->getPlugIn()->addBed($player->getName());
			}
		}
		
		if ((round ( $b->x ) == round ( $greenTeamFlagPos->x ) && round ( $b->y ) == round ( $greenTeamFlagPos->y ) && round ( $b->z ) == round ( $greenTeamFlagPos->z ))) {
			if (!isset ( $this->getPlugIn()->greenTeamPlayers [$player->getName ()] )) {
				$player->getLevel ()->getServer ()->broadcastMessage($player->getNameTag()." "."§3destroyed bed of §2green§3 team");
                                $this->getPlugIn()->greenBed = 1;
                                $player->getLevel()->addParticle(new ExplodeParticle(new Vector3($b->x, $b->y, $b->z)), Server::getInstance()->getOnlinePlayers());
                                $player->getLevel()->addParticle(new FlameParticle(new Vector3($b->x, $b->y, $b->z)), Server::getInstance()->getOnlinePlayers());
                                $player->getLevel()->addSound(new FizzSound(new Vector3($b->x, $b->y, $b->z)), $inGamePlayers);
                                $this->getPlugIn()->addBed($player->getName());
			}
		}
	}
	public function onBlockPlace(BlockPlaceEvent $event) {
		$player = $event->getPlayer ();
		$b = $event->getBlock ();
		if ($this->getPlugin ()->pos_display_flag == 1) {
			$player->sendMessage ( "PLACED:*" . $b->getName () . " [x=" . $b->x . " y=" . $b->y . " z=" . $b->z . "]" );
		}
		
		// @fix #2 stop player place anything else other than the flags
		if (strtolower ( $player->level->getName () ) == strtolower ( $this->getSetup ()->getCTFWorldName () )) {
			if ($this->getSetup ()->isCTFWorldBlockPlaceDisable () || !$player->isOp()) {
				if ($b->getId()!=24 && $b->getDamage()!=2 && $b->getId()!=30 && $b->getId()!=42 && $b->getId()!=54 && $b->getId()!=89 && $b->getId()!=121 && $b->getId()!=19 && $b->getId()!=20 && $b->getId() != 92) {
					$event->setCancelled ( true );
				} 
			}
                        if($this->getPlugin()->ingame == false){
                            if($player->isOp()){
                                $event->setCancelled ( false );
                            }
                        }
		}	
	}
	
	/**
	 * OnPlayerJoin
	 *
	 * @param PlayerJoinEvent $event        	
	 */
	public function onPlayerJoin(PlayerJoinEvent $event) {
            $level = Server::getInstance()->getDefaultLevel();
            $event->getPlayer()->removeEffect(1);
            $event->setJoinMessage("");
            $event->getPlayer()->sendMessage(TextFormat::LIGHT_PURPLE."----------------------------");
            $event->getPlayer()->sendMessage(TextFormat::AQUA."      Welcome to ".TextFormat::RED.TextFormat::BOLD."Bed".TextFormat::WHITE."wars");
            $event->getPlayer()->sendMessage(TextFormat::LIGHT_PURPLE."----------------------------");
		if ($event->getPlayer () instanceof Player) {
                        $event->getPlayer()->teleport($event->getPlayer()->getServer()->getDefaultLevel()->getSpawnLocation());
                        $event->getPlayer()->getInventory()->clearAll();
			$event->getPlayer ()->addAttachment ( $this->getPlugin (), "mcg76.plugin.ctf", true );
			if ($this->getManager () == null) {
				$this->log ( " getManager is null!" );
			} else {
				$this->getManager ()->handlePlayerEntry ( $event->getPlayer () );
			}
		}
	}
        
	public function onPlayerRespawn(PlayerRespawnEvent $event) {
            $player = $event->getPlayer();
            
            if ($event->getPlayer () instanceof Player) {
                if($this->getPlugIn()->ingame == true){
			if ($this->getManager () == null) {
				$this->log ( " getManager is null!" );
			}                        
            /*if($this->getPlugIn()->redBed == 0) {
                       $redTeamSpawnPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION_ENTRY_RED_TEAM );		
			if(isset($this->getPlugIn()->redTeamPlayers[$player->getName()])){
                       $posr = explode(",", $redTeamSpawnPos);
                       $player->getServer()->getLevelByName("bw1")->loadChunk($this->getConfig( "ctf_red_team_spawn_x"), $this->getConfig( "ctf_red_team_spawn_z"));
                       $event->setRespawnPosition (new Position($posr[0], $posr[1], $posr[2]));
                        }
		}
            
            if($this->getPlugIn()->blueBed == 0) {
		$blueTeamSpawnPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION_ENTRY_BLUE_TEAM );
		if(isset($this->getPlugIn()->blueTeamPlayers[$player->getName()])){
                       $posb = explode(",", $blueTeamSpawnPos);
                       $event->setRespawnPosition (new Position($posb[0], $posb[1], $posb[2]));
                        }
            }
            if($this->getPlugIn()->yellowBed == 0) {
		$yellowTeamSpawnPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION_ENTRY_YELLOW_TEAM );
		if(isset($this->getPlugIn()->yellowTeamPlayers[$player->getName()])){
                       $posy = explode(",", $yellowTeamSpawnPos);
                       $event->setRespawnPosition (new Position($posy[0], $posy[1], $posy[2]));
                        }
            }
            if($this->getPlugIn()->greenBed == 0) {
		$greenTeamSpawnPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION_ENTRY_GREEN_TEAM );
		if(isset($this->getPlugIn()->greenTeamPlayers[$player->getName()])){
                       $posg = explode(",", $greenTeamSpawnPos);
                       $event->setRespawnPosition (new Position($posg[0], $posg[1], $posg[2]));
                        }
            }
            }
            if($this->getPlugIn()->ingame == false){
            if ($this->getManager () == null) {
				$this->log ( " getManager is null!" );
			}                        

                       		
			if(isset($this->getPlugIn()->redTeamPlayers[$player->getName()])){
                            $redTeamSpawnPos = $this->getSetup ()->getLobbyPos ();
                       $posr = explode(",", $redTeamSpawnPos);
                       $event->setRespawnPosition (new Position($posr[0], $posr[1], $posr[2]));
                        }

            

		
		if(isset($this->getPlugIn()->blueTeamPlayers[$player->getName()])){
                    $blueTeamSpawnPos = $this->getSetup ()->getLobbyPos ();
                    $level = $player->getServer ()->getLevelByName ("bw1");
                       $posb = explode(",", $blueTeamSpawnPos);
                       $event->setRespawnPosition (new Position($posb[0], $posb[1], $posb[2]));
                        }


		
		if(isset($this->getPlugIn()->yellowTeamPlayers[$player->getName()])){
                    $yellowTeamSpawnPos = $this->getSetup ()->getLobbyPos ();
                       $posy = explode(",", $yellowTeamSpawnPos);
                       $event->setRespawnPosition (new Position($posy[0], $posy[1], $posy[2]));
                        }


		
		if(isset($this->getPlugIn()->greenTeamPlayers[$player->getName()])){
                    $greenTeamSpawnPos = $this->getSetup ()->getLobbyPos ();
                       $posg = explode(",", $greenTeamSpawnPos);
                       $event->setRespawnPosition (new Position($posg[0], $posg[1], $posg[2]));
                        }

		
		if(isset($this->getPlugIn()->lobbyPlayers[$player->getName()])){
                    $lobbySpawnPos = $this->getSetup ()->getLobbyPos ();
                       $posl = explode(",", $lobbySpawnPos);
                       $event->setRespawnPosition (new Position($posl[0], $posl[1], $posl[2]));
                        }
        }*/
        if($this->getPlugIn()->ingame == true){
            if($this->getPlugIn()->blueBed == 1) {
                $blueTeamSpawnPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ENTRY );
		if(isset($this->getPlugIn()->blueTeamPlayers[$player->getName()])){
                       $event->setRespawnPosition (Server::getInstance()->getDefaultLevel()->getSpawnLocation());
                       $this->getManager()->handlePlayerQuit($player);
                        }
            }
            if($this->getPlugIn()->redBed == 1) {
                $blueTeamSpawnPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ENTRY );
		if(isset($this->getPlugIn()->redTeamPlayers[$player->getName()])){
                       $event->setRespawnPosition (Server::getInstance()->getDefaultLevel()->getSpawnLocation());
                       $this->getManager()->handlePlayerQuit($player);
                        }
            }
            if($this->getPlugIn()->yellowBed == 1) {
                $yellowTeamSpawnPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ENTRY );
		if(isset($this->getPlugIn()->yellowTeamPlayers[$player->getName()])){
                       $event->setRespawnPosition (Server::getInstance()->getDefaultLevel()->getSpawnLocation());
                       $this->getManager()->handlePlayerQuit($player);
                        }
            }
            if($this->getPlugIn()->greenBed == 1) {
                $greenTeamSpawnPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ENTRY );
		if(isset($this->getPlugIn()->greenTeamPlayers[$player->getName()])){
                       $event->setRespawnPosition (Server::getInstance()->getDefaultLevel()->getSpawnLocation());
                       $this->getManager()->handlePlayerQuit($player);
                        }
                        
                }
                
	}
            }
        }
        }
        
            
        
        
	/**
	 *
	 * @param PlayerQuitEvent $event        	
	 */
	public function onQuit(PlayerQuitEvent $event) {
		$event->setQuitMessage("");
		if ($event->getPlayer () instanceof Player) {
			$this->getManager ()->handlePlayerQuit ( $event->getPlayer () );
		}
	}
	
	/**
	 * OnPlayerInteract
	 *
	 * @param PlayerInteractEvent $event        	
	 */
	public function onPlayerInteract(PlayerInteractEvent $event) {
		$blockTouched = $event->getBlock ();
		$player = $event->getPlayer ();
		$level = $event->getPlayer ()->getLevel ();
		$b = $event->getBlock ();
                $id = $b->getId();
                $damage = $b->getDamage(); 
		if ($this->getPlugin ()->pos_display_flag == 1) {
			$event->getPlayer ()->sendMessage ( "TOUCHED: [x=" . $b->x . " y=" . $b->y . " z=" . $b->z . "] with ID:".$id." Damage:".$damage);
		}
		
		if($b->getId() == 68 || $b->getId() == 63){
		// process clickable signs
		$this->getManager ()->onClickJoinRedTeamSign ( $player, $blockTouched );
		$this->getManager ()->onClickJoinBlueTeamSign ( $player, $blockTouched );
                $this->getManager ()->onClickJoinYellowTeamSign ( $player, $blockTouched );
                $this->getManager ()->onClickJoinGreenTeamSign ( $player, $blockTouched );
                $this->getShop()->onClickShopSign($player, $blockTouched);
                $this->getManager()->onClickMainSign($player, $blockTouched);
                //$this->getManager ()->onClickJoinSign($player, $blockTouched);
                
		// process sign setup actions
		if ($this->getPlugin ()->setupModeAction != "") {
			$this->getSetup ()->handleClickSignSetup ( $player, $this->getPlugin ()->setupModeAction, new Position ( $b->x, $b->y, $b->z ) );
			$this->getSetup ()->handleSetBlockSetup ( $player, $this->getPlugin ()->setupModeAction, $b->getId () );
		}
                }
                if($b->getId()==0){
                    if($player->getInventory()->getItemInHand()->getId() == 325){
                        $player->getInventory()->setItemInHand(Item::get(0));
                        $effect = Effect::getEffect(5);
                        $effect->setVisible(true);
                        $effect->setDuration(2400);
                        $effect->setAmplifier(1);
                        $player->addEffect($effect);
                    }
                }
	}
	public function onPlayerDeath(PlayerDeathEvent $event) {
            $blueTeamSpawnPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION_ENTRY_BLUE_TEAM );
            $redTeamSpawnPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION_ENTRY_RED_TEAM );
            $yellowTeamSpawnPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION_ENTRY_YELLOW_TEAM );
            $greenTeamSpawnPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION_ENTRY_GREEN_TEAM );
            $player = $event->getEntity();
            $cause = $player->getLastDamageCause();
		if ($player instanceof Player) {
                    $event->setDrops(array());
                    $player->getInventory()->clearAll();
                }
                if($cause instanceof EntityDamageByEntityEvent){
                    $killer = $cause->getDamager();
                    if($killer instanceof Player){
                        $this->getPlugIn()->addKill($killer->getName());
                        $this->getPlugIn()->addDeath($player->getName());
                    }
                }
                
	}
	public function onPlayerKick(PlayerKickEvent $event) {
            $event->setQuitMessage("");
		if ($event->getPlayer () instanceof Player) {
			$this->getManager ()->handlePlayerQuit ( $event->getPlayer () );
		}
	}
	
	/**
	 * Watch sign change
	 * @fix01
	 *
	 * @param SignChangeEvent $event        	
	 */
	public function onSignChange(SignChangeEvent $event) {
		$player = $event->getPlayer ();
		$block = $event->getBlock ();
		$line1 = $event->getLine ( 0 );
		$line2 = $event->getLine ( 1 );
		$line3 = $event->getLine ( 2 );
		$line4 = $event->getLine ( 3 );
		
		if ($line1 != null && $line1 == CTFManager::CTF_COMMAND) {
			if ($line2 != null && $line2 == CTFManager::CTF_COMMAND_HOME) {
				
				$gameworld = $this->getSetup ()->getCTFWorldName ();
				$gamePos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ENTRY );
				$gameX = $gamePos->x;
				$gameY = $gamePos->y;
				$gameZ = $gamePos->z;
				
				$levelhome = $gameworld;
				$level = null;
				if (! $player->getServer ()->isLevelGenerated ( $levelhome )) {
					$player->sendMessage ( $this->getMsg ( "sign.world-not-found" ) . " [" . $levelhome . "]" );
					return;
				}
				
				if (! $player->getServer ()->isLevelLoaded ( $levelhome )) {
					$player->getServer ()->loadLevel ( $levelhome );
				}
				
				if ($player->getServer ()->isLevelLoaded ( $levelhome )) {
					$level = $player->getServer ()->getLevelByName ( $levelhome );
					if ($level == null) {
						$this->log ( "level not found: " . $levelhome );
						return;
					}
					$message = $this->getMsg ( "sign.teleport.world" ) . " [" . $level->getName () . "]";
					$player->sendMessage ( $message );
					$player->teleport ( $level->getSpawnLocation () );
					if ($gameX != null && $gameY != null && $gameZ != null) {
						$player->teleport ( new Vector3 ( $gameX, $gameY, $gameZ ) );
						$message = $this->getMsg ( "sign.teleport.game" );
						$player->sendMessage ( $message );
					}
					$message = $this->getMsg ( "sign.done" );
					$player->sendMessage ( $message );
				}
				return;
			}
			
			if ($line2 != null && $line2 == CTFManager::CTF_COMMAND_JOIN_BLUE_TEAM) {
				$this->getManager ()->handleJoinBlueTeam ( $player );
				return;
			}
			if ($line2 != null && $line2 == CTFManager::CTF_COMMAND_JOIN_RED_TEAM) {
				$this->getManager ()->handleJoinRedTeam ( $player );
				return;
			}
                        if ($line2 != null && $line2 == CTFManager::CTF_COMMAND_JOIN_YELLOW_TEAM) {
				$this->getManager ()->handleJoinYellowTeam ( $player );
				return;
			}
                        if ($line2 != null && $line2 == CTFManager::CTF_COMMAND_JOIN_GREEN_TEAM) {
				$this->getManager ()->handleJoinGreenTeam ( $player );
				return;
			}
			if ($line2 != null && $line2 == CTFManager::CTF_COMMAND_LEAVE) {
				$this->getManager ()->handleLeaveTheGame ( $player );
				return;
			}
		}
	}
        
        //turn off PVP in team
        public function onHurt(EntityDamageEvent $event){
            $blueTeamSpawnPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION_ENTRY_BLUE_TEAM );
            $redTeamSpawnPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION_ENTRY_RED_TEAM );
            $yellowTeamSpawnPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION_ENTRY_YELLOW_TEAM );
            $greenTeamSpawnPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION_ENTRY_GREEN_TEAM );
            if($event instanceof EntityDamageByEntityEvent) {
                $attacker = $event->getDamager();
                $victim = $event->getEntity();
            
            if ($attacker instanceof Player && $victim instanceof Player) {
                if($this->getPlugIn()->ingame == false){
                $event->setCancelled(true);
            }
                if($this->getManager()->getTeam($attacker) == $this->getManager()->getTeam($victim)){
                    $event->setCancelled(true);
                } 
/*
                    //teleport players on kill
                    if (!(isset ( $this->getPlugin()->blueTeamPlayers [$attacker->getName ()] )) && isset( $this->getPlugIn()->blueTeamPlayers [$victim->getName()])) {                        
                        if($event->getFinalDamage() >= $victim->getHealth()){
                            $event->setCancelled(true);
                            $victim->getInventory()->clearAll();
                            $victim->teleport($blueTeamSpawnPos);
                            $player->setHealth(20);
                        }
                    }  
                    if (!(isset ( $this->getPlugin()->redTeamPlayers [$attacker->getName ()] )) && isset( $this->getPlugIn()->redTeamPlayers [$victim->getName()])) {                        
                        if($event->getFinalDamage() >= $victim->getHealth()){
                            $event->setCancelled(true);
                            $victim->getInventory()->clearAll();
                            $victim->teleport($redTeamSpawnPos);
                            $player->setHealth(20);
                        }
                    }
                    if (!(isset ( $this->getPlugin()->yellowTeamPlayers [$attacker->getName ()] )) && isset( $this->getPlugIn()->yellowTeamPlayers [$victim->getName()])) {                        
                        if($event->getFinalDamage() >= $victim->getHealth()){
                            $event->setCancelled(true);
                            $victim->getInventory()->clearAll();
                            $victim->teleport($yellowTeamSpawnPos);
                            $player->setHealth(20);
                        }
                    }
                    if (!(isset ( $this->getPlugin()->greenTeamPlayers [$attacker->getName ()] )) && isset( $this->getPlugIn()->greenTeamPlayers [$victim->getName()])) {                        
                        if($event->getFinalDamage() >= $victim->getHealth()){
                            $event->setCancelled(true);
                            $victim->getInventory()->clearAll();
                            $victim->teleport($greenTeamSpawnPos);
                            $player->setHealth(20);
                        }
                    }*/
            }

        }
        }
        
        public function onCraft(CraftItemEvent $event){
            $event->setCancelled(true);
        }
        
        public function onSendMessage(PlayerChatEvent $event){
            $player = $event->getPlayer();
            if(isset($this->getPlugIn()->blueTeamPlayers[$player->getName()])){
                $event->setRecipients($this->getPlugIn()->blueTeamPlayers);
            }
            if(isset($this->getPlugIn()->redTeamPlayers[$player->getName()])){
                $event->setRecipients($this->getPlugIn()->redTeamPlayers);
            }
            if(isset($this->getPlugIn()->redTeamPlayers[$player->getName()])){
                $event->setRecipients($this->getPlugIn()->redTeamPlayers);
            }
            if(isset($this->getPlugIn()->redTeamPlayers[$player->getName()])){
                $event->setRecipients($this->getPlugIn()->redTeamPlayers);
            }
        }
        
        public function onShoot(EntityShootBowEvent $e){
            $inGamePlayers = array_merge($this->getPlugIn()->blueTeamPlayers, $this->getPlugIn()->redTeamPlayers, $this->getPlugIn()->yellowTeamPlayers, $this->getPlugIn()->greenTeamPlayers);
            if($e->getEntity() instanceof Player && isset($inGamePlayers[$e->getEntity()->getName()])){
                $e->getEntity()->getInventory()->addItem(Item::get(262));
            }
        }
}