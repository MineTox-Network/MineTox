<?php

namespace BedWars\Arena;

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
use pocketmine\math\Vector3 as Vector3;
use pocketmine\network\protocol\UpdateBlockPacket;
use pocketmine\block\Block;
use pocketmine\network\protocol\Info;
use pocketmine\network\protocol\LoginPacket;
use pocketmine\command\defaults\TeleportCommand;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;
use pocketmine\entity\Effect;
use pocketmine\entity\instantEffect;
use pocketmine\entity\DroppedItem;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\Creature;
use pocketmine\inventory\BaseInventory;

/**
 * CTF Manager
 *
 * Copyright (C) 2015 minecraftgenius76
 *
 * @author MCG76
 * @link http://www.youtube.com/user/minecraftgenius76
 *        
 */
class CTFManager  extends MiniGameBase  {
	/*
	 * CTF Commands
	 */
	const CTF_COMMAND = "bw";
	const CTF_COMMAND_HOME = "home";
	const CTF_COMMAND_JOIN = "join";
	const CTF_COMMAND_JOIN_RED_TEAM = "joinred";
	const CTF_COMMAND_JOIN_BLUE_TEAM = "joinblue";
	const CTF_COMMAND_JOIN_YELLOW_TEAM = "joinyellow";
	const CTF_COMMAND_JOIN_GREEN_TEAM = "joingreen";
	const CTF_COMMAND_LEAVE = "leave";
	const CTF_COMMAND_START = "start";
	const CTF_COMMAND_STOP = "stop";
	const CTF_COMMAND_STATS = "stats";
	const CTF_COMMAND_CREATE_ARENA = "create";
	const CTF_COMMAND_RESET_ARENA = "reset";
	const CTF_COMMAND_BLOCK_DISPLAY_ON = "blockon";
	const CTF_COMMAND_BLOCK_DISPLAY_OFF = "blockoff";
	//change sign location
	const CTF_COMMAND_SET_SIGN_JOIN_BLUE_TEAM = "setsignblue";
	const CTF_COMMAND_SET_SIGN_JOIN_RED_TEAM = "setsignred";
	const CTF_COMMAND_SET_SIGN_JOIN_YELLOW_TEAM = "setsignyellow";
	const CTF_COMMAND_SET_SIGN_JOIN_GREEN_TEAM = "setsigngreen";
	const CTF_COMMAND_SET_SIGN_VIEW_STATS = "setsignstats";
	const CTF_COMMAND_SET_SIGN_NEW_GAME = "setsignnew";
	//change block type
	const CTF_COMMAND_SETBLOCK_ID_TEAM_BORDER = "setblockborder";
	const CTF_COMMAND_SETBLOCK_ID_DEFENCE_WALL_BLUE_TEAM = "setblockwallblue";
	const CTF_COMMAND_SETBLOCK_ID_DEFENCE_WALL_RED_TEAM = "setblockwallred";	
					
	/*
	 * CTF permissions 
	 */
	const CTF_PERMISSION_ROOT = "bw.command";
	const CTF_PERMISSION_COMMANDS = "bw.command";	
	const CTF_PERMISSION_HOME = "bw.command.home";
	const CTF_PERMISSION_START = "bw.command.start";
	const CTF_PERMISSION_STOP = "bw.command.stop";
	const CTF_PERMISSION_LEAVE = "bw.command.leave";
	const CTF_PERMISSION_CREATE_ARENA = "bw.command.create";
	const CTF_PERMISSION_RESET_ARENA = "bw.command.reset";
	const CTF_PERMISSION_STATS = "bw.command.stats";	
	const CTF_PERMISSION_JOIN_BLUE_TEAM = "bw.command.joinblue";						
	const CTF_PERMISSION_JOIN_RED_TEAM = "bw.command.joinred";
        const CTF_PERMISSION_JOIN_YELLOW_TEAM = "bw.command.joinyellow";
        const CTF_PERMISSION_JOIN_GREEN_TEAM = "bw.command.joingreen";
	const CTF_PERMISSION_BLOCK_DISPLAY_ON = "bw.command.blockon";
	const CTF_PERMISSION_BLOCK_DISPLAY_OFF = "bw.command.blockoff";
	
        
        protected $exemptedEntities = [];

	/**
	 *
	 * @param CTFPlugin $pg        	
	 */
	public function __construct(BedWars $plugin) {
		parent::__construct ( $plugin );
	}
	
	/**
	 * Handle CTF Commands
	 *
	 * @param CommandSender $sender        	
	 * @param Command $command        	
	 * @param unknown $label        	
	 * @param array $args        	
	 * @return boolean
	 */
	public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
		$player = null;
		if (! $sender instanceof PLayer) {
			$sender->sendMessage ( $this->getMsg ( "ctf.error.wrong-sender" ) );
			return;
		}
		$player = $sender->getPlayer ();
		if ((strtolower ( $command->getName () ) == self::CTF_COMMAND) && isset ( $args [0] )) {
			if (strtolower ( $args [0] ) == self::CTF_COMMAND_CREATE_ARENA || strtolower ( $args [0] ) == self::CTF_COMMAND_RESET_ARENA) {
				if (! $sender->isOp ()) {
					$sender->sendMessage ( $this->getMsg ( "ctf.error.no-permission" ) );
					return;
				}
				$this->buildGameArena ( $sender );
				
				$output .= TextFormat::BLUE . $this->getMsg ( "arena.created" ) . "\n";
				
				$sender->sendMessage ( $output );
				return true;
			} elseif (strtolower ( $args [0] ) == self::CTF_COMMAND_BLOCK_DISPLAY_ON) {
				if (! $sender->isOp ()) {
					$sender->sendMessage ( $this->getMsg ( "ctf.error.no-permission" ) );
					return;
				}
				$this->getPlugIn ()->pos_display_flag = 1;
				$sender->sendMessage ( $this->getMsg ( "block.display-on" ) );
			} elseif (strtolower ( $args [0] ) == self::CTF_COMMAND_BLOCK_DISPLAY_OFF) {
				if (! $sender->isOp ()) {
					$sender->sendMessage ( $this->getMsg ( "ctf.error.no-permission" ) );
					return;
				}
				$this->getPlugIn ()->pos_display_flag = 0;
				$sender->sendMessage ( $this->getMsg ( "block.display-off" ) );
			} elseif (strtolower ( $args [0] ) == self::CTF_COMMAND_JOIN_BLUE_TEAM) {
				$sender->sendMessage ( $this->getMsg ( "team.join-blue" ) );
				$this->handleJoinBlueTeam ( $player );
				return;
			} elseif (strtolower ( $args [0] ) == self::CTF_COMMAND_JOIN_RED_TEAM) {
				$sender->sendMessage ( $this->getMsg ( "team.join-red" ) );
				$this->handleJoinRedTeam ( $player );
				return;
			} 
                          elseif (strtolower ( $args [0] ) == self::CTF_COMMAND_JOIN_YELLOW_TEAM) {
				$sender->sendMessage ( $this->getMsg ( "team.join-yellow" ) );
				$this->handleJoinYellowTeam ( $player );
				return;
			} 
                          elseif (strtolower ( $args [0] ) == self::CTF_COMMAND_JOIN_GREEN_TEAM) {
				$sender->sendMessage ( $this->getMsg ( "team.join-green" ) );
				$this->handleJoinGreenTeam( $player );
				return;
			} 
                          elseif (strtolower ( $args [0] ) == self::CTF_COMMAND_LEAVE) {
				$this->handleLeaveTheGame ( $player );
			} elseif (strtolower ( $args [0] ) == self::CTF_COMMAND_STOP) {
				$this->stopGame($player );
			} elseif (strtolower ( $args [0] ) == self::CTF_COMMAND_START) {
				if(!$sender->isOp()){
                                    $sender->sendMessage(TextFormat::RED."You have not permissions to use this command");
                                }
                                else{
                                    $this->getPlugIn()->starting = true;
                                    $this->getPlugIn()->initScheduler();
                                }
			} elseif (strtolower ( $args [0] ) == self::CTF_COMMAND_STATS) {	
				$sender->sendMessage ( $this->getMsg ( "game.stats" ) );
				$this->displayTeamScores ( $sender );
			} elseif (strtolower ( $args [0] ) == self::CTF_COMMAND_SET_SIGN_JOIN_BLUE_TEAM) {
				if (! $sender->isOp ()) {
					$sender->sendMessage ( $this->getMsg ( "ctf.error.no-permission" ) );
					return;
				}
				$this->getPlugIn()->setupModeAction= self::CTF_COMMAND_SET_SIGN_JOIN_BLUE_TEAM;				
				$sender->sendMessage ($this->getMsg ( "ctf.setup.action" ).self::CTF_COMMAND_SET_SIGN_JOIN_BLUE_TEAM);
				$sender->sendMessage($this->getMsg ( "ctf.setup.select" ));				
			} elseif (strtolower ( $args [0] ) == self::CTF_COMMAND_SET_SIGN_JOIN_RED_TEAM) {
				if (! $sender->isOp ()) {
					$sender->sendMessage ( $this->getMsg ( "ctf.error.no-permission" ) );
					return;
				}
				$this->getPlugIn()->setupModeAction= self::CTF_COMMAND_SET_SIGN_JOIN_RED_TEAM;
				$sender->sendMessage ($this->getMsg ( "ctf.setup.action" ).self::CTF_COMMAND_SET_SIGN_JOIN_RED_TEAM);
				$sender->sendMessage($this->getMsg ( "ctf.setup.select" ));
			} 
                          elseif (strtolower ( $args [0] ) == self::CTF_COMMAND_SET_SIGN_JOIN_YELLOW_TEAM) {
				if (! $sender->isOp ()) {
					$sender->sendMessage ( $this->getMsg ( "ctf.error.no-permission" ) );
					return;
				}
				$this->getPlugIn()->setupModeAction= self::CTF_COMMAND_SET_SIGN_JOIN_YELLOW_TEAM;				
				$sender->sendMessage ($this->getMsg ( "ctf.setup.action" ).self::CTF_COMMAND_SET_SIGN_JOIN_YELLOW_TEAM);
				$sender->sendMessage($this->getMsg ( "ctf.setup.select" ));				
			} 
                          elseif (strtolower ( $args [0] ) == self::CTF_COMMAND_SET_SIGN_JOIN_GREEN_TEAM) {
				if (! $sender->isOp ()) {
					$sender->sendMessage ( $this->getMsg ( "ctf.error.no-permission" ) );
					return;
				}
				$this->getPlugIn()->setupModeAction= self::CTF_COMMAND_SET_SIGN_JOIN_GREEN_TEAM;				
				$sender->sendMessage ($this->getMsg ( "ctf.setup.action" ).self::CTF_COMMAND_SET_SIGN_JOIN_GREEN_TEAM);
				$sender->sendMessage($this->getMsg ( "ctf.setup.select" ));				
			} 
                          elseif (strtolower ( $args [0] ) == self::CTF_COMMAND_SET_SIGN_NEW_GAME) {
				if (! $sender->isOp ()) {
					$sender->sendMessage ( $this->getMsg ( "ctf.error.no-permission" ) );
					return;
				}
				$this->getPlugIn()->setupModeAction= self::CTF_COMMAND_SET_SIGN_NEW_GAME;
				$sender->sendMessage ($this->getMsg ( "ctf.setup.action" ).self::CTF_COMMAND_SET_SIGN_NEW_GAME);
				$sender->sendMessage($this->getMsg ( "ctf.setup.select" ));
			} elseif (strtolower ( $args [0] ) == self::CTF_COMMAND_SET_SIGN_VIEW_STATS) {
				if (! $sender->isOp ()) {
					$sender->sendMessage ( $this->getMsg ( "ctf.error.no-permission" ) );
					return;
				}
				$this->getPlugIn()->setupModeAction= self::CTF_COMMAND_SET_SIGN_VIEW_STATS;
				$sender->sendMessage ($this->getMsg ( "ctf.setup.action" ).self::CTF_COMMAND_SET_SIGN_VIEW_STATS);
				$sender->sendMessage($this->getMsg ( "ctf.setup.select" ));
				
			}elseif (strtolower ( $args [0] ) == self::CTF_COMMAND_SETBLOCK_ID_TEAM_BORDER) {
				if (! $sender->isOp ()) {
					$sender->sendMessage ( $this->getMsg ( "ctf.error.no-permission" ) );
					return;
				}
				$this->getPlugIn()->setupModeAction= self::CTF_COMMAND_SETBLOCK_ID_TEAM_BORDER;
				$sender->sendMessage ($this->getMsg ( "ctf.setup.action" ).self::CTF_COMMAND_SETBLOCK_ID_TEAM_BORDER);
				$sender->sendMessage($this->getMsg ( "ctf.setup.select" ));
				
			} elseif (strtolower ( $args [0] ) == self::CTF_COMMAND_SETBLOCK_ID_DEFENCE_WALL_BLUE_TEAM) {
				if (! $sender->isOp ()) {
					$sender->sendMessage ( $this->getMsg ( "ctf.error.no-permission" ) );
					return;
				}
				$this->getPlugIn()->setupModeAction= self::CTF_COMMAND_SETBLOCK_ID_DEFENCE_WALL_BLUE_TEAM;
				$sender->sendMessage ($this->getMsg ( "ctf.setup.action" ).self::CTF_COMMAND_SETBLOCK_ID_DEFENCE_WALL_BLUE_TEAM);
				$sender->sendMessage($this->getMsg ( "ctf.setup.select" ));
				
			} elseif (strtolower ( $args [0] ) == self::CTF_COMMAND_SETBLOCK_ID_DEFENCE_WALL_RED_TEAM) {
				if (! $sender->isOp ()) {
					$sender->sendMessage ( $this->getMsg ( "ctf.error.no-permission" ) );
					return;
				}
				$this->getPlugIn()->setupModeAction= self::CTF_COMMAND_SETBLOCK_ID_DEFENCE_WALL_RED_TEAM;
				$sender->sendMessage ($this->getMsg ( "ctf.setup.action" ).self::CTF_COMMAND_SETBLOCK_ID_DEFENCE_WALL_RED_TEAM);
				$sender->sendMessage($this->getMsg ( "ctf.setup.select" ));
			}
			
		}
	}

	/**
	 * Game Stop Clean Up
	 *
	 * @param Player $player        	
	 */
	public function stopGame(Player $player) {
		//to avoid interruption, only allow in-game player allow issue stop command
		$inGamePlayers = array_merge($this->getPlugIn()->blueTeamPlayers, $this->getPlugIn()->redTeamPlayers, $this->getPlugIn()->yellowTeamPlayers, $this->getPlugIn()->greenTeamPlayers);
		if (!isset($inGamePlayers[$player->getName()])) {
			$player->sendMessage (TextFormat::RED."Game in progress");
			return;
		}				
		// display
		$this->handleBroadcastFinalScore ( $player, false );
		// reset stats
		$this->getPlugIn ()->redTeamWins = 0;
		$this->getPlugIn ()->blueTeamWins = 0;
		$this->getPlugIn ()->yellowTeamWins = 0;
		$this->getPlugIn ()->greenTeamWins = 0;
		$this->getPlugIn ()->currentGameRound = 0;
		$this->getPlugIn ()->redTeamPlayers = [ ];
		$this->getPlugIn ()->blueTeamPlayers = [ ];
		$this->getPlugIn ()->yellowTeamPlayers = [ ];
		$this->getPlugIn ()->greenTeamPlayers = [ ];
		// remove players from arena
		$this->handleStopTheGame ();		
		//let player leave the game
		$this->handleLeaveTheGame($player);
		//close fence
		$arenaSize = $this->getSetup ()->getArenaSize ();
		$arenaPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION );		
		return;
	}
	
	/**
	 * Display Team Stats
	 *
	 * @param Player $player        	
	 */
	
	/**
	 * Handle Click New Game Sign
	 *
	 * @param Player $player        	
	 * @param unknown $blockTouched        	
	 */
	
	/**
	 * Handle New Game
	 *
	 * @param Player $player        	
	 */
	public function handleNewGame(Player $player) {
		// reset close gates
		$arenaSize = $this->getSetup ()->getArenaSize ();
		$arenaPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION );
		// close gate
		$level = $player->getLevel ();
		
		// reset fire
		// blue team flag
		$this->getBuilder ()->addBlueTeamFlag ( $level, 26, 0 );
		// add red team flag
		$this->getBuilder ()->addRedTeamFlag ( $level, 26, 0 );
		//add yellow team flag
		$this->getBuilder ()->addYellowTeamFlag ( $level, 26, 0 );
		//add green team flag
		$this->getBuilder ()->addGreenTeamFlag ( $level, 26, 0 );
		// reset stats
		}

		/**
	 * Prepare Next Round
	 *
	 * @param Level $level        	
	 */
	
	/**
	 *
	 * Handle Player Join Blue Team
	 *
	 * @param Player $player        	
	 */
	public function handleJoinBlueTeam(Player $player) {
		$blueTeamEntryPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION_ENTRY_BLUE_TEAM );		
		$this->getPlugIn ()->blueTeamPlayers [$player->getName ()] = $player;
		$player->setNameTag (TextFormat::DARK_BLUE . $player->getName () );
                $player->setDisplayName (TextFormat::DARK_BLUE . $player->getName().TextFormat::DARK_AQUA);

                if(isset($this->getPlugIn()->lobbyPlayers[$player->getName()])){
                    unset($this->getPlugIn()->lobbyPlayers[$player->getName()]);
                }
                foreach($this->getPlugIn()->blueTeamPlayers as $pg){
                    $maxTeamPlayers = $this->getSetup ()->getMaxPlayerPerTeam ();
		    $pg->sendMessage(TextFormat::DARK_BLUE.$player->getName().TextFormat::DARK_GRAY." joined to your team [".count($this->getPlugIn()->blueTeamPlayers)."/".$maxTeamPlayers."]");
                }
                if(count($this->getPlugIn()->blueTeamPlayers) == 3 && count($this->getPlugIn()->redTeamPlayers) >= 3 && count($this->getPlugIn()->yellowTeamPlayers) >= 3 && count($this->getPlugIn()->greenTeamPlayers) >= 3) {
                    $this->getPlugIn()->starting = true;
                }
                return;
	}
        
	
	/**
	 * Handle Player Join Red Team
	 *
	 * @param Player $player        	
	 */
	public function handleJoinRedTeam(Player $player) {
		$redTeamEntryPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION_ENTRY_RED_TEAM );		
		$this->getPlugIn ()->redTeamPlayers [$player->getName ()] = $player;
		$player->setNameTag (TextFormat::DARK_RED . $player->getName () );
                $player->setDisplayName (TextFormat::DARK_RED . $player->getName().TextFormat::DARK_AQUA);

                if(isset($this->getPlugIn()->lobbyPlayers[$player->getName()])){
                    unset($this->getPlugIn()->lobbyPlayers[$player->getName()]);
                }
                foreach($this->getPlugIn()->redTeamPlayers as $pg){
                    $maxTeamPlayers = $this->getSetup ()->getMaxPlayerPerTeam ();
		    $pg->sendMessage(TextFormat::DARK_RED.$player->getName().TextFormat::DARK_GRAY." joined to your team [".count($this->getPlugIn()->redTeamPlayers)."/".$maxTeamPlayers."]");
                }

                if(count($this->getPlugIn()->blueTeamPlayers) >= 3 && count($this->getPlugIn()->redTeamPlayers) == 3 && count($this->getPlugIn()->yellowTeamPlayers) >= 3 && count($this->getPlugIn()->greenTeamPlayers) >= 3) {
                    $this->getPlugIn()->starting = true;
                }
                return;
	}
        

//join player to yellow team
	public function handleJoinYellowTeam(Player $player) {
		$yellowTeamEntryPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION_ENTRY_YELLOW_TEAM );		
		
		$this->getPlugIn ()->yellowTeamPlayers [$player->getName ()] = $player;
		$player->setNameTag (TextFormat::YELLOW . $player->getName () );
                $player->setDisplayName (TextFormat::YELLOW . $player->getName().TextFormat::DARK_AQUA);
                
                if(isset($this->getPlugIn()->lobbyPlayers[$player->getName()])){
                    unset($this->getPlugIn()->lobbyPlayers[$player->getName()]);
                }
                foreach($this->getPlugIn()->yellowTeamPlayers as $pg){
                    $maxTeamPlayers = $this->getSetup ()->getMaxPlayerPerTeam ();
		    $pg->sendMessage(TextFormat::YELLOW.$player->getName().TextFormat::DARK_GRAY." joined to your team [".count($this->getPlugIn()->yellowTeamPlayers)."/".$maxTeamPlayers."]");
                }

                if(count($this->getPlugIn()->blueTeamPlayers) >= 3 && count($this->getPlugIn()->redTeamPlayers) >= 3 && count($this->getPlugIn()->yellowTeamPlayers) == 3 && count($this->getPlugIn()->greenTeamPlayers) >= 3) {
                    $this->getPlugIn()->starting = true;
                }
                
                return;
	}
        
	public function handleJoinGreenTeam(Player $player) {
				
		
		$this->getPlugIn ()->greenTeamPlayers [$player->getName ()] = $player;
		$player->setNameTag (TextFormat::DARK_GREEN . $player->getName () );
                $player->setDisplayName (TextFormat::DARK_GREEN . $player->getName().TextFormat::DARK_AQUA);
                if(isset($this->getPlugIn()->lobbyPlayers[$player->getName()])){
                    unset($this->getPlugIn()->lobbyPlayers[$player->getName()]);
                }

                foreach($this->getPlugIn()->greenTeamPlayers as $pg){
                    $maxTeamPlayers = $this->getSetup ()->getMaxPlayerPerTeam ();
		    $pg->sendMessage(TextFormat::DARK_GREEN.$player->getName().TextFormat::DARK_GRAY." joined to your team [".count($this->getPlugIn()->greenTeamPlayers)."/".$maxTeamPlayers."]");
                }

                if(count($this->getPlugIn()->blueTeamPlayers) >= 3 && count($this->getPlugIn()->redTeamPlayers) >= 3 && count($this->getPlugIn()->yellowTeamPlayers) >= 3 && count($this->getPlugIn()->greenTeamPlayers) == 3) {
                    $this->getPlugIn()->starting = true;
                }
                return;
	}
        

	/**
	 * Handle Player leave the game
	 *
	 * @param Player $player        	
	 */
	public function handleLeaveTheGame(Player $player) {
		// check if the player
		if (isset ( $this->getPlugIn ()->redTeamPlayers [$player->getName ()] )) {
			unset ( $this->getPlugIn ()->redTeamPlayers [$player->getName ()] );
			$player->setNameTag ( $player->getName () );
                        $player->setDisplayName ($player->getName());
			$player->updateMovement ();
                        $player->removeEffect(1);
                        $player->getInventory()->clearAll();
                        $player->setSpawn(new Vector3($player->getServer()->getDefaultLevel()->getSpawnLocation()));
		}
		if (isset ( $this->getPlugIn ()->blueTeamPlayers [$player->getName ()] )) {
			unset ( $this->getPlugIn ()->blueTeamPlayers [$player->getName ()] );
			$player->setNameTag ( $player->getName () );
                        $player->setDisplayName ($player->getName());
                        $player->removeEffect(1);
                        $player->getInventory()->clearAll();
                        $player->setSpawn(new Vector3($player->getServer()->getDefaultLevel()->getSpawnLocation()));
		}
		if (isset ( $this->getPlugIn ()->yellowTeamPlayers [$player->getName ()] )) {
			unset ( $this->getPlugIn ()->yellowTeamPlayers [$player->getName ()] );
			$player->setNameTag ( $player->getName () );
                        $player->setDisplayName ($player->getName());
                        $player->removeEffect(1);
                        $player->getInventory()->clearAll();
                        $player->setSpawn(new Vector3($player->getServer()->getDefaultLevel()->getSpawnLocation()));
		}
		if (isset ( $this->getPlugIn ()->greenTeamPlayers [$player->getName ()] )) {
			unset ( $this->getPlugIn ()->greenTeamPlayers [$player->getName ()] );
			$player->setNameTag ( $player->getName () );
                        $player->setDisplayName ($player->getName());
                        $player->removeEffect(1);
                        $player->getInventory()->clearAll();
                        $player->setSpawn(new Vector3($player->getServer()->getDefaultLevel()->getSpawnLocation()));
                }
                if (isset ( $this->getPlugIn ()->lobbyPlayers [$player->getName ()] )) {
			unset ( $this->getPlugIn ()->lobbyPlayers [$player->getName ()] );
			$player->setNameTag ( $player->getName () );
                        $player->setDisplayName ($player->getName());
                        $player->removeEffect(1);
                        $player->getInventory()->clearAll();
                        $player->setSpawn(new Vector3($player->getServer()->getDefaultLevel()->getSpawnLocation()));
		}
                
                

		$gameWaitingRoomPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION_WAITING_ROOM );
		$player->teleport ($player->getServer()->getDefaultLevel()->getSpawnLocation());
                $inGamePlayers = array_merge($this->getPlugIn()->blueTeamPlayers, $this->getPlugIn()->redTeamPlayers, $this->getPlugIn()->yellowTeamPlayers, $this->getPlugIn()->greenTeamPlayers);
                foreach($inGamePlayers as $p){
                    $p->sendMessage($player->getDisplayName().TextFormat::DARK_GRAY."left the game");
                }
                if($this->getPlugIn()->ingame == true){
                if (count($this->getPlugIn ()->redTeamPlayers) > 0 && count($this->getPlugIn ()->blueTeamPlayers) == 0 && count($this->getPlugIn ()->yellowTeamPlayers) == 0 && count($this->getPlugIn ()->greenTeamPlayers) == 0 ){
                    $others = array_merge($this->getPlugIn()->blueTeamPlayers, $this->getPlugIn()->yellowTeamPlayers, $this->getPlugIn()->greenTeamPlayers);
                    foreach($this->getPlugIn ()->redTeamPlayers as $p){
                        $this->getPlugIn()->addWin($p->getName());
                    }
                    foreach($others as $p){
                        $this->getPlugIn()->addLoss($p->getName());
                    }
                    $this->handleBroadcastFinalScore($player, true);
                    $this->handleStopTheGame();
		} elseif (count($this->getPlugIn ()->redTeamPlayers) == 0 && count($this->getPlugIn ()->blueTeamPlayers) > 0 && count($this->getPlugIn ()->yellowTeamPlayers) == 0 && count($this->getPlugIn ()->greenTeamPlayers) == 0 ) {
                    $others = array_merge($this->getPlugIn()->redTeamPlayers, $this->getPlugIn()->yellowTeamPlayers, $this->getPlugIn()->greenTeamPlayers);
                    foreach($this->getPlugIn ()->blueTeamPlayers as $p){
                        $this->getPlugIn()->addWin($p->getName());
                    }
                    foreach($others as $p){
                        $this->getPlugIn()->addLoss($p->getName());
                    }
                    $this->handleBroadcastFinalScore($player, true);
                    $this->handleStopTheGame();
		} elseif (count($this->getPlugIn ()->redTeamPlayers) == 0 && count($this->getPlugIn ()->blueTeamPlayers) == 0 && count($this->getPlugIn ()->yellowTeamPlayers) > 0 && count($this->getPlugIn ()->greenTeamPlayers) == 0 ) {
                    $others = array_merge($this->getPlugIn()->blueTeamPlayers, $this->getPlugIn()->redTeamPlayers, $this->getPlugIn()->greenTeamPlayers);
                    foreach($this->getPlugIn ()->yellowTeamPlayers as $p){
                        $this->getPlugIn()->addWin($p->getName());
                    }
                    foreach($others as $p){
                        $this->getPlugIn()->addLoss($p->getName());
                    }
                    $this->handleBroadcastFinalScore($player, true);
                    $this->handleStopTheGame();
		} elseif (count($this->getPlugIn ()->redTeamPlayers) == 0 && count($this->getPlugIn ()->blueTeamPlayers) == 0 && count($this->getPlugIn ()->yellowTeamPlayers) == 0 && count($this->getPlugIn ()->greenTeamPlayers) > 0 ) {
                    $others = array_merge($this->getPlugIn()->blueTeamPlayers, $this->getPlugIn()->yellowTeamPlayers, $this->getPlugIn()->redTeamPlayers);
                    foreach($this->getPlugIn ()->greenTeamPlayers as $p){
                        $this->getPlugIn()->addWin($p->getName());
                    }
                    foreach($others as $p){
                        $this->getPlugIn()->addLoss($p->getName());
                    }
                    $this->handleBroadcastFinalScore($player, true);
                    $this->handleStopTheGame();
                }
                  elseif (count($this->getPlugIn ()->redTeamPlayers) == 0 && count($this->getPlugIn ()->blueTeamPlayers) == 0 && count($this->getPlugIn ()->yellowTeamPlayers) == 0 && count($this->getPlugIn ()->greenTeamPlayers) == 0 ) {
                    $this->handleStopTheGame();
                }
                }
        }
	/**
	 * Game Stop Clean up
	 */
	public function handleStopTheGame() {
		// send all players to waiting room
		$waitingRoomPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION_WAITING_ROOM );
                $level = $this->getPlugIn()->bwLevel;
		foreach ( $this->getPlugIn ()->redTeamPlayers as $rp ) {
                        unset($this->getPlugIn()->redTeamPlayers[$rp]);
                        $rp->sendMessage (TextFormat::AQUA."End game");
			$rp->setNameTag ( $rp->getName () );
                        $rp->setDisplayName ($rp->getName());
			$rp->teleport(Server::getInstance()->getDefaultLevel()->getSpawnLocation());
                        $rp->removeEffect(1);
                        $rp->setSpawn(new Vector3(Server::getInstance()->getDefaultLevel()->getSpawnLocation()));
		}
		
		foreach ( $this->getPlugIn ()->blueTeamPlayers as $bp ) {
                        unset($this->getPlugIn()->blueTeamPlayers[$bp]);
			$bp->sendMessage (TextFormat::AQUA."End game");
			$bp->setNameTag ( $bp->getName () );
                        $bp->setDisplayName ($bp->getName());
			$bp->teleport(Server::getInstance()->getDefaultLevel()->getSpawnLocation());
                        $bp->removeEffect(1);
                        $bp->setSpawn(new Vector3(Server::getInstance()->getDefaultLevel()->getSpawnLocation()));
		}

                foreach ( $this->getPlugIn ()->yellowTeamPlayers as $yp ) {
                        unset($this->getPlugIn()->yellowTeamPlayers[$yp]);
			$yp->sendMessage (TextFormat::AQUA."End game");
			$yp->setNameTag ( $yp->getName () );
                        $yp->setDisplayName ($yp->getName());
			$yp->teleport(Server::getInstance()->getDefaultLevel()->getSpawnLocation());
                        $yp->removeEffect(1);
                        $yp->setSpawn(new Vector3(Server::getInstance()->getDefaultLevel()->getSpawnLocation()));
		}

		foreach ( $this->getPlugIn ()->greenTeamPlayers as $gp ) {
                        unset($this->getPlugIn()->greenTeamPlayers[$gp]);
			$gp->sendMessage (TextFormat::AQUA."End game");
			$gp->setNameTag ( $gp->getName () );
                        $gp->setDisplayName ($gp->getName());
			$gp->teleport(Server::getInstance()->getDefaultLevel()->getSpawnLocation());
                        $gp->removeEffect(1);
                        $gp->setSpawn(new Vector3(Server::getInstance()->getDefaultLevel()->getSpawnLocation()));
		}

               /* $this->getBuilder()->replaceBlockType($this->getPlugIn()->bwLevel, Block::get(24, 2), 0);
                $this->getBuilder()->replaceBlockType($this->getPlugIn()->bwLevel, Block::get(2, 0), 0);
                $this->getBuilder()->replaceBlockType($this->getPlugIn()->bwLevel, Block::get(30, 0), 0);
                $this->getBuilder()->replaceBlockType($this->getPlugIn()->bwLevel, Block::get(42, 0), 0);
                $this->getBuilder()->replaceBlockType($this->getPlugIn()->bwLevel, Block::get(54, 0), 0);
                $this->getBuilder()->replaceBlockType($this->getPlugIn()->bwLevel, Block::get(89, 0), 0);
                $this->getBuilder()->replaceBlockType($this->getPlugIn()->bwLevel, Block::get(121, 0), 0);
                $this->getBuilder()->replaceBlockType($this->getPlugIn()->bwLevel, Block::get(19, 0), 0);
                $this->getBuilder()->replaceBlockType($this->getPlugIn()->bwLevel, Block::get(20, 0), 0);
                 
                foreach($this->getPlugIn()->blockPos as $bp){
                $level->setBlockIdAt($bp, 0);
                }
                */

                //$this->removeEntities();
		$this->getPlugIn ()->blueTeamPlayers = [ ];
		$this->getPlugIn ()->redTeamPlayers = [ ];
		$this->getPlugIn ()->yellowTeamPlayers = [ ];
		$this->getPlugIn ()->greenTeamPlayers = [ ];
                $this->getPlugIn()->ingame = false;
                $this->getPlugIn()->restart = true;
                $this->getPlugIn()->starting = false;
                
	}
	
	/**
	 * Send Team Back to Spawn Point
	 */
	
	/**
	 *
	 * Touched Join Red Team Sign
	 *
	 * @param PlayerInteractEvent $event        	
	 */
	public function onClickJoinRedTeamSign(Player $player, $blockTouched) {
		$maxTeamPlayers = $this->getSetup ()->getMaxPlayerPerTeam ();
		$joinRedPos = $this->getSetup ()->getSignPos ( CTFSetup::CLICK_SIGN_JOIN_RED_TEAM );
		// Join RED Team SIGN
		if (round ( $blockTouched->x ) == round ( $joinRedPos->x ) && round ( $blockTouched->y ) == round ( $joinRedPos->y ) && round ( $blockTouched->z ) == round ( $joinRedPos->z )) {
			if (count ( $this->getPlugIn ()->redTeamPlayers ) >= $maxTeamPlayers) {
				$player->sendMessage (TextFormat::DARK_GRAY."This team is full");
				return;
			}
                        if(isset($this->getPlugIn()->redTeamPlayers[$player->getName()])){
                            $player->sendMessage(TextFormat::DARK_GRAY."you are already in ".TextFormat::DARK_RED."red".TextFormat::DARK_GRAY." team");
                        }
                        if(isset($this->getPlugIn()->blueTeamPlayers[$player->getName()])){
                            $player->sendMessage(TextFormat::RED."you can not switch teams");
                        }
                        if(isset($this->getPlugIn()->greenTeamPlayers[$player->getName()])){
                            $player->sendMessage(TextFormat::RED."you can not switch teams");
                        }
                        if(isset($this->getPlugIn()->yellowTeamPlayers[$player->getName()])){
                            $player->sendMessage(TextFormat::RED."you can not switch teams");
                        }
			if ($this->getPlugIn ()->ingame == false && isset($this->getPlugIn()->lobbyPlayers[$player->getName()])) {
				// auto start change if not started yet
				$player->level->getBlockLightAt($blockTouched->x, $blockTouched->y, $blockTouched->z);
				$this->handleNewGame ( $player );
                                $this->handleJoinRedTeam ( $player );
			}
			// $player->sendMessage ( "------------------------------" );			
			return;
		}
	}
	
	/**
	 *
	 * Touched Join Blue Team Sign
	 *
	 * @param PlayerInteractEvent $event        	
	 */
	public function onClickJoinBlueTeamSign(Player $player, $blockTouched) {
		$maxTeamPlayers = $this->getSetup ()->getMaxPlayerPerTeam ();
		$joinBluePos = $this->getSetup ()->getSignPos ( CTFSetup::CLICK_SIGN_JOIN_BLUE_TEAM );
		// Join BLUE Team SIGN
		if (round ( $blockTouched->x ) == round ( $joinBluePos->x ) && round ( $blockTouched->y ) == round ( $joinBluePos->y ) && round ( $blockTouched->z ) == round ( $joinBluePos->z )) {
			if (count ( $this->getPlugIn ()->blueTeamPlayers ) >= $maxTeamPlayers) {
				$player->sendMessage (TextFormat::DARK_GRAY."This team is full");
				return;
			}
			if(isset($this->getPlugIn()->blueTeamPlayers[$player->getName()])){
                            $player->sendMessage(TextFormat::DARK_GRAY."you are already in ".TextFormat::DARK_BLUE."blue".TextFormat::DARK_GRAY." team");
                        }
                        if(isset($this->getPlugIn()->greenTeamPlayers[$player->getName()])){
                            $player->sendMessage(TextFormat::RED."you can not switch teams");
                        }
                        if(isset($this->getPlugIn()->redTeamPlayers[$player->getName()])){
                            $player->sendMessage(TextFormat::RED."you can not switch teams");
                        }
                        if(isset($this->getPlugIn()->yellowTeamPlayers[$player->getName()])){
                            $player->sendMessage(TextFormat::RED."you can not switch teams");
                        }
			if ($this->getPlugIn ()->ingame == false && isset($this->getPlugIn()->lobbyPlayers[$player->getName()])) {
				// auto start change if not started yet
				$player->level->getBlockLightAt($blockTouched->x, $blockTouched->y, $blockTouched->z);
				$this->handleNewGame ( $player );
                                $this->handleJoinBlueTeam ( $player );
			}
			
			return;
		}
	}
	public function onClickJoinYellowTeamSign(Player $player, $blockTouched) {
		$maxTeamPlayers = $this->getSetup ()->getMaxPlayerPerTeam ();
		$joinYellowPos = $this->getSetup ()->getSignPos ( CTFSetup::CLICK_SIGN_JOIN_YELLOW_TEAM );
		// Join YELLOW Team SIGN
		if (round ( $blockTouched->x ) == round ( $joinYellowPos->x ) && round ( $blockTouched->y ) == round ( $joinYellowPos->y ) && round ( $blockTouched->z ) == round ( $joinYellowPos->z )) {
			if (count ( $this->getPlugIn ()->yellowTeamPlayers ) >= $maxTeamPlayers) {
				$player->sendMessage (TextFormat::DARK_GRAY."This team is full");
				return;
			}
			if(isset($this->getPlugIn()->yellowTeamPlayers[$player->getName()])){
                            $player->sendMessage(TextFormat::DARK_GRAY."you are already in ".TextFormat::YELLOW."yellow".TextFormat::DARK_GRAY." team");
                        }
                        if(isset($this->getPlugIn()->blueTeamPlayers[$player->getName()])){
                            $player->sendMessage(TextFormat::RED."you can not switch teams");
                        }
                        if(isset($this->getPlugIn()->redTeamPlayers[$player->getName()])){
                            $player->sendMessage(TextFormat::RED."you can not switch teams");
                        }
                        if(isset($this->getPlugIn()->greenTeamPlayers[$player->getName()])){
                            $player->sendMessage(TextFormat::RED."you can not switch teams");
                        }
			if ($this->getPlugIn ()->ingame == false && isset($this->getPlugIn()->lobbyPlayers[$player->getName()])) {
				// auto start change if not started yet
				$player->level->getBlockLightAt($blockTouched->x, $blockTouched->y, $blockTouched->z);
				$this->handleNewGame ( $player );
                                $this->handleJoinYellowTeam ( $player );
			}
			
			// $player->sendMessage ( "------------------------------" );
			
			return;
		}
	}

	public function onClickJoinGreenTeamSign(Player $player, $blockTouched) {
		$maxTeamPlayers = $this->getSetup ()->getMaxPlayerPerTeam ();
		$joinGreenPos = $this->getSetup ()->getSignPos ( CTFSetup::CLICK_SIGN_JOIN_GREEN_TEAM );
		// Join GREEN Team SIGN
		if (round ( $blockTouched->x ) == round ( $joinGreenPos->x ) && round ( $blockTouched->y ) == round ( $joinGreenPos->y ) && round ( $blockTouched->z ) == round ( $joinGreenPos->z )) {
			if (count ( $this->getPlugIn ()->greenTeamPlayers ) >= $maxTeamPlayers) {
				$player->sendMessage (TextFormat::DARK_GRAY."This team is full");
				return;
			}
			if(isset($this->getPlugIn()->greenTeamPlayers[$player->getName()])){
                            $player->sendMessage(TextFormat::DARK_GRAY."you are already in ".TextFormat::DARK_GREEN."green".TextFormat::DARK_GRAY." team");
                        }
                        if(isset($this->getPlugIn()->blueTeamPlayers[$player->getName()])){
                            $player->sendMessage(TextFormat::RED."you can not switch teams");
                        }
                        if(isset($this->getPlugIn()->redTeamPlayers[$player->getName()])){
                            $player->sendMessage(TextFormat::RED."you can not switch teams");
                        }
                        if(isset($this->getPlugIn()->yellowTeamPlayers[$player->getName()])){
                            $player->sendMessage(TextFormat::RED."you can not switch teams");
                        }
			if ($this->getPlugIn ()->ingame == false && isset($this->getPlugIn()->lobbyPlayers[$player->getName()])) {
				// auto start change if not started yet
				$player->level->getBlockLightAt($blockTouched->x, $blockTouched->y, $blockTouched->z);
				$this->handleNewGame ( $player );
                                $this->handleJoinGreenTeam ( $player );
			}
			
			// $player->sendMessage ( "------------------------------" );
			
			return;
		}
	}


	/**
	 * Clicked View Game Statistic Sign
	 *
	 * @param Player $player        	
	 * @param unknown $blockTouched        	
	 */
	
        public function onClickMainSign(Player $player, $blockTouched){
            $level;
            $statusSignPos = $this->getSetup ()->getSignPos ( CTFSetup::CLICK_SIGN_STATUS );
            if (round ( $blockTouched->x ) == round ( $statusSignPos->x ) && round ( $blockTouched->y ) == round ( $statusSignPos->y ) && round ( $blockTouched->z ) == round ( $statusSignPos->z ) /*&& $blockTouched->getLevel() == $player->getServer()->getLevelByName("lobby")*/) {                
                if ($this->getPlugIn ()->ingame == false) {
                    $player->getServer()->loadLevel("bw1");
                    if (! $player->getServer ()->isLevelLoaded ( "bw1" )) {
			$player->getServer ()->loadLevel ( "bw1" );
                    }
                    if ($player->getServer ()->isLevelLoaded ("bw1")) {
                        $level = $player->getServer ()->getLevelByName ("bw1");
                        if ($level == null) {
                            return;
                        }
                    $level->getChunk ($this->getSetup()->getConfig ( "lobby_x" ), $this->getSetup()->getConfig ( "lobby_z" ));
                    $player->teleport(new Position($this->getSetup()->getConfig ( "lobby_x" ), $this->getSetup()->getConfig ( "lobby_y" ), $this->getSetup()->getConfig ( "lobby_z" ), $level));
                    $this->getPlugIn()->lobbyPlayers[$player->getName()] = $player;
                    $player->setDisplayName(TextFormat::DARK_PURPLE."[lobby] ".TextFormat::WHITE.$player->getName().TextFormat::DARK_AQUA);
                    $player->setSpawn(new Vector3($this->getConfig ( "lobby_x" ), $this->getConfig ( "lobby_y" ), $this->getConfig ( "lobby_z")));
                    
                        }

                    }
                    if($this->getPlugIn()->ingame == true){
                        $player->sendMessage(TextFormat::RED."Game in progress");
                    }
                    if($this->getPlugIn()->restart == true){
                        $player->sendMessage(TextFormat::RED."game is restarting");
                    }
            }
        }
        
	/**
	 * re-build game arena
	 *
	 * @param CommandSender $sender        	
	 */
	
	/**
	 * Send player to Lobby
	 *
	 * @param Player $player        	
	 */
	
        
	/**
	 * send player home
	 *
	 * @param Player $player        	
	 */
	
	/**
	 * Build New Game
	 *
	 * @param CommandSender $sender        	
	 */
	
	
	/**
	 *
	 * Touched Start Button
	 *
	 * @param PlayerInteractEvent $event        	
	 *
	
	/**
	 * handle start of new game
	 *
	 * @param Level $level        	
	 */
	public function handleStartTheGame(Level $level) {
		// change gamemode
		$this->getPlugIn ()->setGameMode ( 1 );
		// $builder = new CTFArenaBuilder ( $this->getPlugIn() );
		// add blue team flag
		$this->getBuilder ()->addBlueTeamFlag ( $level, 26, 0 );
		// add red team flag
		$this->getBuilder ()->addRedTeamFlag ( $level, 26, 0 );
		// add yellow team flag
		$this->getBuilder ()->addYellowTeamFlag ( $level, 26, 0 );
		// add green team flag
		$this->getBuilder ()->addGreenTeamFlag ( $level, 26, 0 );
		// add fire and remove start button
		// remove any blue/red flag from players inventory
		// avoid cheating
	/*	if ($this->getPlugIn ()->blueTeamPlayers != null && count ( $this->getPlugIn ()->blueTeamPlayers ) > 0) {
			foreach ( $this->getPlugIn ()->blueTeamPlayers as $p ) {
				// remove any flag item from player inventory
				$p->getInventory ()->remove ( new Item ( 171 ) );
			}
		}
		
		if ($this->getPlugIn ()->redTeamPlayers != null && count ( $this->getPlugIn ()->redTeamPlayers ) > 0) {
			foreach ( $this->getPlugIn ()->redTeamPlayers as $p ) {
				// remove any flag item from player inventory
				$p->getInventory ()->remove ( new Item ( 171 ) );
			}
		}*/
		$arenaSize = $this->getSetup ()->getArenaSize ();
		$arenaPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION );
		
		
		// announce game
		
		
		
		// change gamemode
		$this->getPlugIn ()->setGameMode ( 1 );
	}
	
	/**
	 *
	 * Touched Stop Button
	 *
	 * @param PlayerInteractEvent $event        	
	 */
	public function onClickStopGameButton($level, Player $player, $blockTouched) {
		$stopButtonPos = $this->getSetup ()->getButtonPos ( CTFSetup::CLICK_BUTTON_STOP_GAME );
		// STOP BUTTON
		if ((round ( $blockTouched->x ) == round ( $stopButtonPos->x ) && round ( $blockTouched->y ) == round ( $stopButtonPos->y ) && round ( $blockTouched->z ) == round ( $stopButtonPos->z ))) {
			// set the floor to be breakable
			// blue team flag
			$this->getBuilder ()->addBlueTeamFlag ( $level, 26, 0 );
			// add red team flag
			$this->getBuilder ()->addRedTeamFlag ( $level, 26, 0 );
			// add yellow flag
			$this->getBuilder ()->addYellowTeamFlag ( $level, 26, 0 );
			// add green team flag
			$this->getBuilder ()->addGreenTeamFlag ( $level, 26, 0 );

			// add fire
			// brodcast
			$this->handleBroadcastFinalScore ( $player, true );
			// reset stats
			
			// remove players
			$this->handleStopTheGame ();
			$arenaSize = $this->getSetup ()->getArenaSize ();
			$arenaPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION );
			// close gates
			
		}
	}
	public function handleBroadcastFinalScore(Player $player, $toEveryone = false) {
            $inGamePlayers = array_merge($this->getPlugIn()->blueTeamPlayers, $this->getPlugIn()->redTeamPlayers, $this->getPlugIn()->yellowTeamPlayers, $this->getPlugIn()->greenTeamPlayers);
		// brodcast		
		// same score then it's a tie
		if (count($this->getPlugIn ()->redTeamPlayers) > 0 && count($this->getPlugIn ()->blueTeamPlayers) == 0 && count($this->getPlugIn ()->yellowTeamPlayers) == 0 && count($this->getPlugIn ()->greenTeamPlayers) == 0 ){
			foreach($inGamePlayers as $p){
                            $p->sendMessage(TextFormat::DARK_PURPLE."--------------------------------");
                            $p->sendMessage(TextFormat::RED."        *CONGRATULATIONS*");
                            $p->sendMessage(TextFormat::DARK_RED."          red ".TextFormat::DARK_GRAY."team wins");
                            $p->sendMessage(TextFormat::DARK_PURPLE."--------------------------------");
                        }
		} elseif (count($this->getPlugIn ()->redTeamPlayers) == 0 && count($this->getPlugIn ()->blueTeamPlayers) > 0 && count($this->getPlugIn ()->yellowTeamPlayers) == 0 && count($this->getPlugIn ()->greenTeamPlayers) == 0 ) {
			foreach($inGamePlayers as $p){
                            $p->sendMessage(TextFormat::DARK_PURPLE."--------------------------------");
                            $p->sendMessage(TextFormat::RED."        *CONGRATULATIONS*");
                            $p->sendMessage(TextFormat::DARK_BLUE."          blue ".TextFormat::DARK_GRAY."team wins");
                            $p->sendMessage(TextFormat::DARK_PURPLE."--------------------------------");
                        }
		} elseif (count($this->getPlugIn ()->redTeamPlayers) == 0 && count($this->getPlugIn ()->blueTeamPlayers) == 0 && count($this->getPlugIn ()->yellowTeamPlayers) > 0 && count($this->getPlugIn ()->greenTeamPlayers) == 0 ) {
			foreach($inGamePlayers as $p){
                            $p->sendMessage(TextFormat::DARK_PURPLE."--------------------------------");
                            $p->sendMessage(TextFormat::RED."        *CONGRATULATIONS*");
                            $p->sendMessage(TextFormat::YELLOW."          yellow ".TextFormat::DARK_GRAY."team wins");
                            $p->sendMessage(TextFormat::DARK_PURPLE."--------------------------------");
                        }
		} elseif (count($this->getPlugIn ()->redTeamPlayers) == 0 && count($this->getPlugIn ()->blueTeamPlayers) == 0 && count($this->getPlugIn ()->yellowTeamPlayers) == 0 && count($this->getPlugIn ()->greenTeamPlayers) > 0 )
                        foreach($inGamePlayers as $p){
                            $p->sendMessage(TextFormat::DARK_PURPLE."--------------------------------");
                            $p->sendMessage(TextFormat::RED."        *CONGRATULATIONS*");
                            $p->sendMessage(TextFormat::DARK_GREEN."          green ".TextFormat::DARK_GRAY."team wins");
                            $p->sendMessage(TextFormat::DARK_PURPLE."--------------------------------");
                        }
	}
                
                
                
	
        public function startGame() {       
            $inGamePlayers = array_merge($this->getPlugIn()->blueTeamPlayers, $this->getPlugIn()->redTeamPlayers, $this->getPlugIn()->yellowTeamPlayers, $this->getPlugIn()->greenTeamPlayers);    
            if(count($inGamePlayers) == 0){
                return;
            }
            if(count($inGamePlayers) > 0){
            foreach($inGamePlayers as $p){
                        $p->sendMessage(TextFormat::RED."------------------------------");
                        $p->sendMessage(TextFormat::YELLOW."Bedwars started!");
                        $p->sendMessage(TextFormat::RED."------------------------------");
                        $p->setHealth(20);
                        $p->getInventory()->clearAll();
            }
            foreach($this->getPlugIn()->blueTeamPlayers as $p){
                $blueTeamEntryPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION_ENTRY_BLUE_TEAM );
                $p->level->getChunk($blueTeamEntryPos->x, $blueTeamEntryPos->z);
                $p->teleport(new position($this->getConfig ( "ctf_blue_team_spawn_x" ), $this->getConfig ( "ctf_blue_team_spawn_y" ), $this->getConfig ( "ctf_blue_team_spawn_z" )));
                $this->getPlugIn()->bwLevel = $p->getLevel(); 
                $p->setSpawn(new Vector3($this->getConfig ( "ctf_blue_team_spawn_x" ), $this->getConfig ( "ctf_blue_team_spawn_y" ), $this->getConfig ( "ctf_blue_team_spawn_z")));
            }
            foreach($this->getPlugIn()->redTeamPlayers as $p){
                $redTeamEntryPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION_ENTRY_RED_TEAM );
                $p->level->getChunk($redTeamEntryPos->x, $redTeamEntryPos->z);
                $p->teleport(new position($this->getConfig ( "ctf_red_team_spawn_x" ), $this->getConfig ( "ctf_red_team_spawn_y" ), $this->getConfig ( "ctf_red_team_spawn_z" )));
                $this->getPlugIn()->bwLevel = $p->getLevel();
                $p->setSpawn(new Vector3($this->getConfig ( "ctf_red_team_spawn_x" ), $this->getConfig ( "ctf_red_team_spawn_y" ), $this->getConfig ( "ctf_red_team_spawn_z")));
            }
            foreach($this->getPlugIn()->yellowTeamPlayers as $p){
                $yellowTeamEntryPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION_ENTRY_YELLOW_TEAM );
                $p->level->getChunk($yellowTeamEntryPos->x, $yellowTeamEntryPos->z);
                $p->teleport(new position($this->getConfig ( "ctf_yellow_team_spawn_x" ), $this->getConfig ( "ctf_yellow_team_spawn_y" ), $this->getConfig ( "ctf_yellow_team_spawn_z" )));
                $this->getPlugIn()->bwLevel = $p->getLevel();
                $p->setSpawn(new Vector3($this->getConfig ( "ctf_yellow_team_spawn_x" ), $this->getConfig ( "ctf_yellow_team_spawn_y" ), $this->getConfig ( "ctf_yellow_team_spawn_z")));
            }
            foreach($this->getPlugIn()->greenTeamPlayers as $p){
                $greenTeamEntryPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ARENA_POSITION_ENTRY_GREEN_TEAM );
                $p->level->getChunk($greenTeamEntryPos->x, $greenTeamEntryPos->z);
                $p->teleport(new position($this->getConfig ( "ctf_green_team_spawn_x" ), $this->getConfig ( "ctf_green_team_spawn_y" ), $this->getConfig ( "ctf_green_team_spawn_z" )));
                $this->getPlugIn()->bwLevel = $p->getLevel();
                $p->setSpawn(new Vector3($this->getConfig ( "ctf_green_team_spawn_x" ), $this->getConfig ( "ctf_green_team_spawn_y" ), $this->getConfig ( "ctf_green_team_spawn_z")));
            }
            foreach($this->getPlugIn()->lobbyPlayers as $p){
                $p->teleport(Server::getInstance()->getDefaultLevel()->getSpawnLocation());
            }
                        $this->getPlugIn()->ingame = true;                        
                        $this->removeEntities();
                        $this->handleStartTheGame($this->getPlugIn()->bwLevel);
                        $this->getPlugin()->redBed = 0;
                        $this->getPlugin()->blueBed = 0;
                        $this->getPlugin()->yellowBed = 0;
                        $this->getPlugin()->greenBed = 0;
        }
        }
        
	/**
	 *
	 * Clicked Stop Button
	 *
	 * @param PlayerInteractEvent $event        	
	 */
	public function onClickLeaveGameButton($level, $player, $blockTouched) {
		$leaveButtonPos = $this->getSetup ()->getButtonPos ( CTFSetup::CLICK_BUTTON_LEAVE_GAME );
		// LEAVE BUTTON
		if ((round ( $blockTouched->x ) == $leaveButtonPos->x && round ( $blockTouched->y ) == $leaveButtonPos->y && round ( $blockTouched->z ) == $leaveButtonPos->z)) {
			// send all players to waiting room
			$this->handleLeaveTheGame ( $player );
		}
	}
	
	/**
	 * Handle Player Disconnect, Death or Kicked
	 *
	 * @param Player $player        	
	 */
	public function handlePlayerQuit(Player $player) {
            $inGamePlayers = array_merge($this->getPlugIn()->blueTeamPlayers, $this->getPlugIn()->redTeamPlayers, $this->getPlugIn()->yellowTeamPlayers, $this->getPlugIn()->greenTeamPlayers, $this->getPlugIn()->lobbyPlayers);
		// @fix1
		// check if the player
		if (isset ( $this->getPlugIn ()->redTeamPlayers [$player->getName ()] )) {
			foreach($inGamePlayers as $p){
                            $p->sendMessage($player->getDisplayName().TextFormat::DARK_GRAY."left the game");
                        }
			unset ( $this->getPlugIn ()->redTeamPlayers [$player->getName ()] );
			$player->setNameTag ( $player->getName () );
                        $player->setDisplayName ($player->getName());
			$player->removeEffect(1);
		}
		
		if (isset ( $this->getPlugIn ()->blueTeamPlayers [$player->getName ()] )) {
			foreach($inGamePlayers as $p){
                            $p->sendMessage($player->getDisplayName().TextFormat::DARK_GRAY."left the game");
                        }
			unset ( $this->getPlugIn ()->blueTeamPlayers [$player->getName ()] );
			$player->setNameTag ( $player->getName () );
                        $player->setDisplayName ($player->getName());
			$player->removeEffect(1);
		}

                if (isset ( $this->getPlugIn ()->yellowTeamPlayers [$player->getName ()] )) {
			foreach($inGamePlayers as $p){
                            $p->sendMessage($player->getDisplayName().TextFormat::DARK_GRAY."left the game");
                        }
			unset ( $this->getPlugIn ()->yellowTeamPlayers [$player->getName ()] );
			$player->setNameTag ( $player->getName () );
                        $player->setDisplayName ($player->getName());
			$player->removeEffect(1);
		}
		if (isset ( $this->getPlugIn ()->greenTeamPlayers [$player->getName ()] )) {
			foreach($inGamePlayers as $p){
                            $p->sendMessage($player->getDisplayName().TextFormat::DARK_GRAY."left the game");
                        }
			unset ( $this->getPlugIn ()->greenTeamPlayers [$player->getName ()] );
			$player->setNameTag ( $player->getName () );
                        $player->setDisplayName ($player->getName());
			$player->removeEffect(1);
		}
                if (isset ( $this->getPlugIn ()->lobbyPlayers [$player->getName ()] )) {
			unset ( $this->getPlugIn ()->lobbyPlayers [$player->getName ()] );
			$player->setNameTag ( $player->getName () );
                        $player->setDisplayName ($player->getName());
			$player->removeEffect(1);
		}

                $player->teleport($player->getServer()->getDefaultLevel()->getSpawnLocation());
                        
		if ($this->getPlugIn ()->ingame == true) {
			// auto stop the game and declare winner if no team member left in anyone team
			if (count ( $this->getPlugIn ()->redTeamPlayers ) > 0 && count ( $this->getPlugIn ()->blueTeamPlayers ) == 0 && count ( $this->getPlugIn ()->yellowTeamPlayers ) == 0 && count ( $this->getPlugIn ()->greenTeamPlayers ) == 0) {
				// red team win				
				
				$this->handleBroadcastFinalScore ( $player, true );
                                $this->handleStopTheGame ();
			} elseif (count ( $this->getPlugIn ()->redTeamPlayers ) == 0 && count ( $this->getPlugIn ()->blueTeamPlayers ) > 0 && count ( $this->getPlugIn ()->yellowTeamPlayers ) == 0 && count ( $this->getPlugIn ()->greenTeamPlayers ) == 0) {

				// blue team win
				
				$this->handleBroadcastFinalScore ( $player, true );
                                $this->handleStopTheGame ();
			} elseif (count ( $this->getPlugIn ()->redTeamPlayers ) == 0 && count ( $this->getPlugIn ()->blueTeamPlayers ) == 0 && count ( $this->getPlugIn ()->yellowTeamPlayers ) > 0 && count ( $this->getPlugIn ()->greenTeamPlayers ) == 0) {

				// yellow team win
				
				$this->handleBroadcastFinalScore ( $player, true );
                                $this->handleStopTheGame ();
			} elseif (count ( $this->getPlugIn ()->redTeamPlayers ) == 0 && count ( $this->getPlugIn ()->blueTeamPlayers ) == 0 && count ( $this->getPlugIn ()->yellowTeamPlayers ) == 0 && count ( $this->getPlugIn ()->greenTeamPlayers ) > 0) {

				// green team win
				
				$this->handleBroadcastFinalScore ( $player, true );
                                $this->handleStopTheGame ();
			} 
			elseif (count ( $this->getPlugIn ()->redTeamPlayers ) == 0 && count ( $this->getPlugIn ()->blueTeamPlayers ) == 0 && count ( $this->getPlugIn ()->yellowTeamPlayers ) == 0 && count ( $this->getPlugIn ()->greenTeamPlayers ) == 0) {

				// draw
				
				$this->handleBroadcastFinalScore ( $player, true );
                                $this->handleStopTheGame ();
			}
                        else{
                            return;
                        }
		}
	}
        
        
	/**
	 * Handle player entry to CTF game world
	 *
	 * @param Player $player        	
	 */
	public function handlePlayerEntry(Player $player) {
		// send player to lobby if specify
		
                $player->teleport($player->getServer()->getDefaultLevel()->getSpawnLocation());

		
		// player should be outside of arena
		$gameWorld = $this->getSetup ()->getCTFWorldName ();
		if (strtolower ( $player->level->getName () ) == strtolower ( $gameWorld )) {
			// send entry point
			$gameWorldPos = $this->getSetup ()->getGamePos ( CTFSetup::CTF_GAME_ENTRY );
			$player->level->getChunk($gameWorldPos->x, $gameWorldPos->z);
			$player->level->getBlockLightAt($gameWorldPos->x, $gameWorldPos->y, $gameWorldPos->z);
			$player->teleport ( new Vector3 ( $gameWorldPos->x, $gameWorldPos->y, $gameWorldPos->z ) );
                        $player->setSpawn(new Vector3(Server::getInstance()->getDefaultLevel()->getSpawnLocation()));
			return;
		}
		
		//grant player permissions
		$this->grantPlayerDefaultPermissions($player);
	}
        
        public function getTeam($player){
            if(isset($this->getPlugIn()->blueTeamPlayers[$player->getName()])){
                return "blue";
            }
            if(isset($this->getPlugIn()->redTeamPlayers[$player->getName()])){
                return "red";
            }
            if(isset($this->getPlugIn()->yellowTeamPlayers[$player->getName()])){
                return "yellow";
            }
            if(isset($this->getPlugIn()->greenTeamPlayers[$player->getName()])){
                return "green";
            }
            if(isset($this->getPlugIn()->lobbyPlayers[$player->getName()])){
                return "lobby";
            }
            else{
                return null;
            }
        }
        
    public function removeEntities() {
        $level = $this->getPlugIn()->bwLevel;
        if($level->getEntities() != null){
            foreach($level->getEntities() as $entity) {
                if(!$this->isEntityExempted($entity) && !($entity instanceof Creature)) {
                    $entity->close();
                }
            }
        }
    }
	
    
    public function isEntityExempted(Entity $entity) {
    return isset($this->exemptedEntities[$entity->getID()]);
  }

  public function exemptEntity(Entity $entity) {
    $this->exemptedEntities[$entity->getID()] = $entity;
  }

  public function addWorld($worldname) {
		// make a copy of the skyblock template
		$fileutil = new FileUtil ();
				
			$base = "bw1_base";
		$source = Server::getInstance ()->getDataPath () . "worlds/" . $base . "/";
		// $source = $sender->getServer ()->getDataPath () . "worlds/skyblockbase/";
		$dest = Server::getInstance ()->getDataPath () . "worlds/" . $worldname . "/";		
		
		$count = 0;
		
		if ($fileutil->xcopy ( $source, $dest )) {
			try {
				Server::getInstance ()->loadLevel ( $worldname );
			} catch ( \Exception $e ) {
				$this->log ( "level loading error: " . $e->getMessage () );
			}
			
			$this->log ( "loaded map: " . $worldname );			
			Server::getInstance ()->loadLevel ( $worldname );
			$level = Server::getInstance ()->getLevelByName ( $worldname );
			//$map->save ();
			//$this->pgin->maps [$worldname] = $map;
		}
	}

        public function deleteWorld($worldname) {
		/*$level = Server::getInstance ()->getLevelByName ($worldname);
		Server::getInstance ()->unloadLevel ( $level, true );
		*/
		// delete folder
		$levelpath = Server::getInstance ()->getDataPath () . "worlds/" . $worldname . "/";
		// @unlink($levelpath);
		// rmdir($levelpath);
		$fileutil = new FileUtil ();
		$fileutil->unlinkRecursive ( $levelpath, true );
		
			$this->log ( "delete map: " . $worldname );
			//$map = $this->pgin->maps [$worldname];			
			//$map->delete ();
		
	}

        
        
        
	/**
	 * Give default permissions to players
	 * @param Player $player
	 */
	private function grantPlayerDefaultPermissions(Player $player) {
		$player->addAttachment($this->getPlugIn(),self::CTF_PERMISSION_HOME, TRUE);
		$player->addAttachment($this->getPlugIn(),self::CTF_PERMISSION_JOIN_BLUE_TEAM, TRUE);		
		$player->addAttachment($this->getPlugIn(),self::CTF_PERMISSION_JOIN_RED_TEAM, TRUE);	
		$player->addAttachment($this->getPlugIn(),self::CTF_PERMISSION_JOIN_YELLOW_TEAM, TRUE);
		$player->addAttachment($this->getPlugIn(),self::CTF_PERMISSION_JOIN_GREEN_TEAM, TRUE);	
		$player->addAttachment($this->getPlugIn(),self::CTF_PERMISSION_STATS, TRUE);
		$player->addAttachment($this->getPlugIn(),self::CTF_PERMISSION_LEAVE, TRUE);
		$player->addAttachment($this->getPlugIn(),self::CTF_PERMISSION_START, TRUE);
		$player->addAttachment($this->getPlugIn(),self::CTF_PERMISSION_STOP, TRUE);
		if ($player->isOp()) {
			$player->addAttachment($this->getPlugIn(),self::CTF_PERMISSION_CREATE_ARENA, TRUE);
			$player->addAttachment($this->getPlugIn(),self::CTF_PERMISSION_RESET_ARENA, TRUE);
			$player->addAttachment($this->getPlugIn(),self::CTF_PERMISSION_BLOCK_DISPLAY_ON, TRUE);
			$player->addAttachment($this->getPlugIn(),self::CTF_PERMISSION_BLOCK_DISPLAY_OFF, TRUE);
		}
	}

}