<?php

namespace BedWars\Arena;

use pocketmine\utils\Config;
use pocketmine\level\Position;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;
use pocketmine\block\Block;
use pocketmine\Player;

/**
 * MCG76 CTF Setup
 *
 * Copyright (C) 2014 minecraftgenius76
 *
 * @author MCG76
 * @link http://www.youtube.com/user/minecraftgenius76
 *      
 */
class CTFSetup extends MiniGameBase {
	const DIR_ARENA = "arena/";

	const LOBBY_POSITION = 4001;
	const CLICK_SIGN_JOIN_RED_TEAM = 1000;
	const CLICK_SIGN_JOIN_BLUE_TEAM = 1001;
	const CLICK_SIGN_JOIN_YELLOW_TEAM = 1002;
	const CLICK_SIGN_JOIN_GREEN_TEAM = 1003;
	const CLICK_SIGN_SHOW_GAME_STAT = 1004;
	const CLICK_SIGN_NEW_GAME = 1005;
	const CLICK_BUTTON_START_GAME = 2001;
	const CLICK_BUTTON_STOP_GAME = 2002;
	const CLICK_BUTTON_LEAVE_GAME = 2003;
        const CLICK_SIGN_LOBBY = 2004;
	const CTF_FLAG_RED_TEAM = 3000;
	const CTF_FLAG_BLUE_TEAM = 3001;
        const CTF_FLAG2_BLUE_TEAM = 3004;
	const CTF_FLAG_YELLOW_TEAM = 3002;
	const CTF_FLAG_GREEN_TEAM = 3003;
	const CTF_BLOCK_ID_BORDER_FENCE = 4001;
	const CTF_BLOCK_ID_DEFENCE_WALL_BLUE_TEAM = 4002;
	const CTF_BLOCK_ID_DEFENCE_WALL_RED_TEAM = 4003;
	const CTF_GAME_ENTRY = 5000;
	const CTF_GAME_ARENA_POSITION = 5001;
	const CTF_GAME_ARENA_POSITION_ENTRY_RED_TEAM = 5002;
	const CTF_GAME_ARENA_POSITION_ENTRY_BLUE_TEAM = 5003;
	const CTF_GAME_ARENA_POSITION_ENTRY_YELLOW_TEAM = 5004;
	const CTF_GAME_ARENA_POSITION_ENTRY_GREEN_TEAM = 5005;
	const CTF_GAME_ARENA_POSITION_WAITING_ROOM = 5006;
        const CLICK_SIGN_STATUS = 5007;
        
        const SHOP_YELLOW_SWORD1 = 6000;
        const SHOP_YELLOW_SWORD2 = 6001;
        const SHOP_YELLOW_SWORD3 = 6002;
        const SHOP_YELLOW_HELMET = 6003;
        const SHOP_YELLOW_LEGGINGS = 6004;
        const SHOP_YELLOW_BOOTS = 6005;
        const SHOP_YELLOW_CHESTPLATE1 = 6006;
        const SHOP_YELLOW_CHESTPLATE2 = 6007;
        const SHOP_YELLOW_CHESTPLATE3 = 6008;
        const SHOP_YELLOW_SANDSTONE = 6009;
        const SHOP_YELLOW_ENDSTONE = 6010; //7 bronze
        const SHOP_YELLOW_IRON = 6011; //1 iron
        const SHOP_YELLOW_GLOWSTONE = 6012; //15 bronze = 4x gs
        const SHOP_YELLOW_PICKAXE1 = 6013;
        const SHOP_YELLOW_PICKAXE2 = 6014;
        const SHOP_YELLOW_PICKAXE3 = 6015;
        const SHOP_YELLOW_CHEST = 6016; //1 iron
        const SHOP_YELLOW_COBWEB = 6017; //
        const SHOP_YELLOW_GLASS = 6018; //4 bronze
        const SHOP_YELLOW_BOW1 = 6019; //3 gold
        const SHOP_YELLOW_BOW2 = 6020; //7 gold
        const SHOP_YELLOW_BOW3 = 6021; //13 gold
        const SHOP_YELLOW_ARROW = 6022; //1 gold
        const SHOP_YELLOW_APPLE = 6023; //1 bronze
        const SHOP_YELLOW_PORKCHOP = 6024; //3 bronze
        const SHOP_YELLOW_CAKE = 6025; //1 iron
        const SHOP_YELLOW_INSTANTHEALTH = 6026; //3 iron
        const SHOP_YELLOW_INSTANTHEALTH2 = 6027; //5 iron
        const SHOP_YELLOW_SPEED = 6028; //7 iron
        const SHOP_YELLOW_STRENGHT = 6029; //8 gold
        const SHOP_YELLOW_FISHINGROD = 6030; //5 iron
        const SHOP_YELLOW_ENDERPEARL = 6031; //13 gold
        const SHOP_YELLOW_SPONGE = 6032; //5 iron
        const SHOP_YELLOW_WARPDUST = 6033; //5 gold
        const SHOP_YELLOW_STICK = 6034; //8 bronze
        
        const SHOP_BLUE_SWORD1 = 7000;
        const SHOP_BLUE_SWORD2 = 7001;
        const SHOP_BLUE_SWORD3 = 7002;
        const SHOP_BLUE_HELMET = 7003;
        const SHOP_BLUE_LEGGINGS = 7004;
        const SHOP_BLUE_BOOTS = 7005;
        const SHOP_BLUE_CHESTPLATE1 = 7006;
        const SHOP_BLUE_CHESTPLATE2 = 7007;
        const SHOP_BLUE_CHESTPLATE3 = 7008;
        const SHOP_BLUE_SANDSTONE = 7009;
        const SHOP_BLUE_ENDSTONE = 7010; //7 bronze
        const SHOP_BLUE_IRON = 7011; //1 iron
        const SHOP_BLUE_GLOWSTONE = 7012; //15 bronze = 4x gs
        const SHOP_BLUE_PICKAXE1 = 7013;
        const SHOP_BLUE_PICKAXE2 = 7014;
        const SHOP_BLUE_PICKAXE3 = 7015;
        const SHOP_BLUE_CHEST = 7016; //1 iron
        const SHOP_BLUE_COBWEB = 7017; //
        const SHOP_BLUE_GLASS = 7018; //4 bronze
        const SHOP_BLUE_BOW1 = 7019; //3 gold
        const SHOP_BLUE_BOW2 = 7020; //7 gold
        const SHOP_BLUE_BOW3 = 7021; //13 gold
        const SHOP_BLUE_ARROW = 7022; //1 gold
        const SHOP_BLUE_APPLE = 7023; //1 bronze
        const SHOP_BLUE_PORKCHOP = 7024; //3 bronze
        const SHOP_BLUE_CAKE = 7025; //1 iron
        const SHOP_BLUE_INSTANTHEALTH = 7026; //3 iron
        const SHOP_BLUE_INSTANTHEALTH2 = 7027; //5 iron
        const SHOP_BLUE_SPEED = 7028; //7 iron
        const SHOP_BLUE_STRENGHT = 7029; //8 gold
        const SHOP_BLUE_FISHINGROD = 7030; //5 iron
        const SHOP_BLUE_ENDERPEARL = 7031; //13 gold
        const SHOP_BLUE_SPONGE = 7032; //5 iron
        const SHOP_BLUE_WARPDUST = 7033; //5 gold
        const SHOP_BLUE_STICK = 7034;
        
        const SHOP_RED_SWORD1 = 8000;
        const SHOP_RED_SWORD2 = 8001;
        const SHOP_RED_SWORD3 = 8002;
        const SHOP_RED_HELMET = 8003;
        const SHOP_RED_LEGGINGS = 8004;
        const SHOP_RED_BOOTS = 8005;
        const SHOP_RED_CHESTPLATE1 = 8006;
        const SHOP_RED_CHESTPLATE2 = 8007;
        const SHOP_RED_CHESTPLATE3 = 8008;
        const SHOP_RED_SANDSTONE = 8009;
        const SHOP_RED_ENDSTONE = 8010; //7 bronze
        const SHOP_RED_IRON = 8011; //1 iron
        const SHOP_RED_GLOWSTONE = 8012; //15 bronze = 4x gs
        const SHOP_RED_PICKAXE1 = 8013;
        const SHOP_RED_PICKAXE2 = 8014;
        const SHOP_RED_PICKAXE3 = 8015;
        const SHOP_RED_CHEST = 8016; //1 iron
        const SHOP_RED_COBWEB = 8017; //
        const SHOP_RED_GLASS = 8018; //4 bronze
        const SHOP_RED_BOW1 = 8019; //3 gold
        const SHOP_RED_BOW2 = 8020; //7 gold
        const SHOP_RED_BOW3 = 8021; //13 gold
        const SHOP_RED_ARROW = 8022; //1 gold
        const SHOP_RED_APPLE = 8023; //1 bronze
        const SHOP_RED_PORKCHOP = 8024; //3 bronze
        const SHOP_RED_CAKE = 8025; //1 iron
        const SHOP_RED_INSTANTHEALTH = 8026; //3 iron
        const SHOP_RED_INSTANTHEALTH2 = 8027; //5 iron
        const SHOP_RED_SPEED = 8028; //7 iron
        const SHOP_RED_STRENGHT = 8029; //8 gold
        const SHOP_RED_FISHINGROD = 8030; //5 iron
        const SHOP_RED_ENDERPEARL = 8031; //13 gold
        const SHOP_RED_SPONGE = 8032; //5 iron
        const SHOP_RED_WARPDUST = 8033; //5 gold
        const SHOP_RED_STICK = 8034;
        
        const SHOP_GREEN_SWORD1 = 9000;
        const SHOP_GREEN_SWORD2 = 9001;
        const SHOP_GREEN_SWORD3 = 9002;
        const SHOP_GREEN_HELMET = 9003;
        const SHOP_GREEN_LEGGINGS = 9004;
        const SHOP_GREEN_BOOTS = 9005;
        const SHOP_GREEN_CHESTPLATE1 = 9006;
        const SHOP_GREEN_CHESTPLATE2 = 9007;
        const SHOP_GREEN_CHESTPLATE3 = 9008;
        const SHOP_GREEN_SANDSTONE = 9009;
        const SHOP_GREEN_ENDSTONE = 9010; //7 bronze
        const SHOP_GREEN_IRON = 9011; //1 iron
        const SHOP_GREEN_GLOWSTONE = 9012; //15 bronze = 4x gs
        const SHOP_GREEN_PICKAXE1 = 9013;
        const SHOP_GREEN_PICKAXE2 = 9014;
        const SHOP_GREEN_PICKAXE3 = 9015;
        const SHOP_GREEN_CHEST = 9016; //1 iron
        const SHOP_GREEN_COBWEB = 9017; //
        const SHOP_GREEN_GLASS = 9018; //4 bronze
        const SHOP_GREEN_BOW1 = 9019; //3 gold
        const SHOP_GREEN_BOW2 = 9020; //7 gold
        const SHOP_GREEN_BOW3 = 9021; //13 gold
        const SHOP_GREEN_ARROW = 9022; //1 gold
        const SHOP_GREEN_APPLE = 9023; //1 bronze
        const SHOP_GREEN_PORKCHOP = 9024; //3 bronze
        const SHOP_GREEN_CAKE = 9025; //1 iron
        const SHOP_GREEN_INSTANTHEALTH = 9026; //3 iron
        const SHOP_GREEN_INSTANTHEALTH2 = 9027; //5 iron
        const SHOP_GREEN_SPEED = 9028; //7 iron
        const SHOP_GREEN_STRENGHT = 9029; //8 gold
        const SHOP_GREEN_FISHINGROD = 9030; //5 iron
        const SHOP_GREEN_ENDERPEARL = 9031; //13 gold
        const SHOP_GREEN_SPONGE = 9032; //5 iron
        const SHOP_GREEN_WARPDUST = 9033; //5 gold
        const SHOP_GREEN_STICK = 9034;
        
	/**
	 * Constructor
	 *
	 * @param CTFPlugIn $plugin        	
	 */
	public function __construct(BedWars $plugin) {
		parent::__construct ( $plugin );
	}
	
	public function isCTFWorldBlockBreakDisable() {
		return $this->getConfig("disable_CTF_world_blockBreak", true );
	}
	
	public function isCTFWorldBlockPlaceDisable() {
		return $this->getConfig("disable_CTF_world_blockPlace", true );
	}
	
	public function getMessageLanguage() {
		$configlang = $this->getConfig ( "language" );
		if ($configlang == null) {
			$configlang = "EN";
		}
		return $configlang;
	}
	public function getMaxGameRounds() {
		$maxRounds = $this->getConfig ( "maximum_game_rounds" );
		if ($maxRounds != null && $maxRounds != $this->getPlugIn ()->maxGameRound) {
			$this->getPlugIn ()->maxGameRound = $maxRounds;
		}
		return $maxRounds;
	}
	public function getRoundWaitTime() {
		$resetValue = $this->getConfig ( "round_wait_time" );
		if ($resetValue == null) {
			$resetValue = 300;
		}
		return $resetValue;
	}
	public function getMaxPlayerPerTeam() {
		$maxTeamPlayers = $this->getConfig ( "maximum_team_players" );
		if ($maxTeamPlayers == null) {
			$maxTeamPlayers = 10;
		}
		return $maxTeamPlayers;
	}
	public function getArenaName() {
		$arenaName = $this->getConfig ( "ctf_arena_name" );
		return $arenaName;
	}
	public function getBlockId($typeId) {
		switch ($typeId) {
			case self::CTF_BLOCK_ID_BORDER_FENCE :
				$blockId = $this->getConfig ( "ctf_border_fence" );
				if ($blockId == null) {
					$blockId = Block::FENCE;
				}
				return $blockId;
				break;
			case self::CTF_BLOCK_ID_DEFENCE_WALL_BLUE_TEAM :
				$blockId = $this->getConfig ( "blue_team_defence_wall" );
				if ($blockId == null) {
					$blockId = Block::FENCE;
				}
				return $blockId;
				break;
			case self::CTF_BLOCK_ID_DEFENCE_WALL_RED_TEAM :
				$blockId = $this->getConfig ( "red_team_defence_wall" );
				if ($blockId == null) {
					$blockId = Block::FENCE;
				}
				return $blockId;
				break;
			default :
				return Block::AIR;
		}
	}
	public function getArenaSize() {
		$arenaSize = $this->getConfig ( "ctf_arena_size" );
		return $arenaSize;
	}
	public function getArenaPos() {
		$dataX = $this->getConfig ( "ctf_arena_x" );
		$dataY = $this->getConfig ( "ctf_arena_y" );
		$dataZ = $this->getConfig ( "ctf_arena_z" );
		return new Position ( $dataX, $dataY, $dataZ );
	}
	public function getCTFWorldName() {
		$gameworld = $this->getConfig ( "ctf_game_world" );
		return $gameworld;
	}
	public function isEnableSpanwToLobby() {
		$enableSpawnLobby = $this->getConfig ( "enable_spaw_lobby" );
		if ($enableSpawnLobby != null && $enableSpawnLobby == "yes") {
			return true;
		}
		return false;
	}
	public function getLobbyWorldName() {
		return $this->getConfig ( "lobby_world" );
	}
	public function getLobbyPos() {
		$lobbyX = $this->getConfig ( "lobby_x" );
		$lobbyY = $this->getConfig ( "lobby_y" );
		$lobbyZ = $this->getConfig ( "lobby_z" );
		return new Position ( $lobbyX, $lobbyY, $lobbyZ );
	}
	public function getGamePos($posTypeId) {
		switch ($posTypeId) {
			case self::CTF_GAME_ARENA_POSITION_ENTRY_RED_TEAM :
				$sx = $this->getConfig ( "ctf_red_team_spawn_x" );
				$sy = $this->getConfig ( "ctf_red_team_spawn_y" );
				$sz = $this->getConfig ( "ctf_red_team_spawn_z" );
				return new Position ( $sx, $sy, $sz );
				break;
			case self::CTF_GAME_ARENA_POSITION_ENTRY_BLUE_TEAM :
				$sx = $this->getConfig ( "ctf_blue_team_spawn_x" );
				$sy = $this->getConfig ( "ctf_blue_team_spawn_y" );
				$sz = $this->getConfig ( "ctf_blue_team_spawn_z" );
				return new Position ( $sx, $sy, $sz );
				break;
			case self::CTF_GAME_ARENA_POSITION_ENTRY_YELLOW_TEAM :
				$sx = $this->getConfig ( "ctf_yellow_team_spawn_x" );
				$sy = $this->getConfig ( "ctf_yellow_team_spawn_y" );
				$sz = $this->getConfig ( "ctf_yellow_team_spawn_z" );
				return new Position ( $sx, $sy, $sz );
				break;
			case self::CTF_GAME_ARENA_POSITION_ENTRY_GREEN_TEAM :
				$sx = $this->getConfig ( "ctf_green_team_spawn_x" );
				$sy = $this->getConfig ( "ctf_green_team_spawn_y" );
				$sz = $this->getConfig ( "ctf_green_team_spawn_z" );
				return new Position ( $sx, $sy, $sz );
				break;
			case self::CTF_GAME_ARENA_POSITION :
				$sx = $this->getConfig ( "ctf_arena_x" );
				$sy = $this->getConfig ( "ctf_arena_y" );
				$sz = $this->getConfig ( "ctf_arena_z" );
				return new Position ( $sx, $sy, $sz );
				break;
			case self::CTF_GAME_ARENA_POSITION_WAITING_ROOM :
				$sx = $this->getConfig ( "ctf_waiting_room_x" );
				$sy = $this->getConfig ( "ctf_waiting_room_y" );
				$sz = $this->getConfig ( "ctf_waiting_room_z" );
				return new Position ( $sx, $sy, $sz );
				break;
			case self::CTF_GAME_ENTRY :
				$gameX = $this->getConfig ( "ctf_game_x" );
				$gameY = $this->getConfig ( "ctf_game_y" );
				$gameZ = $this->getConfig ( "ctf_game_z" );
				return new Position ( $gameX, $gameY, $gameZ );
				break;
			default :
				return null;
		}
	}
	public function getButtonPos($buttonTypeId) {
		switch ($buttonTypeId) {
			case self::CLICK_BUTTON_STOP_GAME :
				$sx = $this->getConfig ( "ctf_stop_button_1_x" );
				$sy = $this->getConfig ( "ctf_stop_button_1_y" );
				$sz = $this->getConfig ( "ctf_stop_button_1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
			case self::CLICK_BUTTON_START_GAME :
				$sx = $this->getConfig ( "ctf_start_button_1_x" );
				$sy = $this->getConfig ( "ctf_start_button_1_y" );
				$sz = $this->getConfig ( "ctf_start_button_1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
			case self::CLICK_BUTTON_LEAVE_GAME :
				$sx = $this->getConfig ( "ctf_leave_button_1_x" );
				$sy = $this->getConfig ( "ctf_leave_button_1_y" );
				$sz = $this->getConfig ( "ctf_leave_button_1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
			default :
				return null;
		}
	}
	public function getFlagPos($flagTypeId) {
		switch ($flagTypeId) {
			case self::CTF_FLAG_RED_TEAM :
				$sx = $this->getConfig ( "ctf_red_team_flag_x" );
				$sy = $this->getConfig ( "ctf_red_team_flag_y" );
				$sz = $this->getConfig ( "ctf_red_team_flag_z" );
				return new Position ( $sx, $sy, $sz );
				break;
			case self::CTF_FLAG_BLUE_TEAM :
				$sx = $this->getConfig ( "ctf_blue_team_flag_x" );
				$sy = $this->getConfig ( "ctf_blue_team_flag_y" );
				$sz = $this->getConfig ( "ctf_blue_team_flag_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                        case self::CTF_FLAG2_BLUE_TEAM:
                                $sx = $this->getConfig ( "ctf_blue_team_flag2_x" );
				$sy = $this->getConfig ( "ctf_blue_team_flag2_y" );
				$sz = $this->getConfig ( "ctf_blue_team_flag2_z" );
				return new Position ( $sx, $sy, $sz );
				break;
			case self::CTF_FLAG_YELLOW_TEAM :
				$sx = $this->getConfig ( "ctf_yellow_team_flag_x" );
				$sy = $this->getConfig ( "ctf_yellow_team_flag_y" );
				$sz = $this->getConfig ( "ctf_yellow_team_flag_z" );
				return new Position ( $sx, $sy, $sz );
				break;
			case self::CTF_FLAG_GREEN_TEAM :
				$sx = $this->getConfig ( "ctf_green_team_flag_x" );
				$sy = $this->getConfig ( "ctf_green_team_flag_y" );
				$sz = $this->getConfig ( "ctf_green_team_flag_z" );
				return new Position ( $sx, $sy, $sz );
				break;
			default :
				return null;
		}
	}
	public function getSignPos($signTypeId) {
		switch ($signTypeId) {
			case self::CLICK_SIGN_SHOW_GAME_STAT :
				$sx = $this->getConfig ( "ctf_stat_sign_x" );
				$sy = $this->getConfig ( "ctf_stat_sign_y" );
				$sz = $this->getConfig ( "ctf_stat_sign_z" );
				return new Position ( $sx, $sy, $sz );
				break;
			case self::CLICK_SIGN_JOIN_BLUE_TEAM :
				$sx = $this->getConfig ( "ctf_blue_team_join_sign1_x" );
				$sy = $this->getConfig ( "ctf_blue_team_join_sign1_y" );
				$sz = $this->getConfig ( "ctf_blue_team_join_sign1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
			case self::CLICK_SIGN_JOIN_RED_TEAM :
				$sx = $this->getConfig ( "ctf_red_team_join_sign1_x" );
				$sy = $this->getConfig ( "ctf_red_team_join_sign1_y" );
				$sz = $this->getConfig ( "ctf_red_team_join_sign1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
			case self::CLICK_SIGN_JOIN_YELLOW_TEAM :
				$sx = $this->getConfig ( "ctf_yellow_team_join_sign1_x" );
				$sy = $this->getConfig ( "ctf_yellow_team_join_sign1_y" );
				$sz = $this->getConfig ( "ctf_yellow_team_join_sign1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
			case self::CLICK_SIGN_JOIN_GREEN_TEAM :
				$sx = $this->getConfig ( "ctf_green_team_join_sign1_x" );
				$sy = $this->getConfig ( "ctf_green_team_join_sign1_y" );
				$sz = $this->getConfig ( "ctf_green_team_join_sign1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
			case self::CLICK_SIGN_NEW_GAME :
				$sx = $this->getConfig ( "ctf_new_sign_x" );
				$sy = $this->getConfig ( "ctf_new_sign_y" );
				$sz = $this->getConfig ( "ctf_new_sign_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                        case self::CLICK_SIGN_STATUS :
				$sx = $this->getConfig ( "ctf_status_sign_x" );
				$sy = $this->getConfig ( "ctf_status_sign_y" );
				$sz = $this->getConfig ( "ctf_status_sign_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                        case self::SHOP_YELLOW_SWORD1:
                                $sx = $this->getConfig ( "ctf_shop_yellow_sword1_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_sword1_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_sword1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_SWORD2:
                                $sx = $this->getConfig ( "ctf_shop_yellow_sword2_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_sword2_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_sword2_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_SWORD3:
                                $sx = $this->getConfig ( "ctf_shop_yellow_sword3_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_sword3_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_sword3_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_HELMET:
                                $sx = $this->getConfig ( "ctf_shop_yellow_helmet_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_helmet_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_helmet_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_LEGGINGS:
                                $sx = $this->getConfig ( "ctf_shop_yellow_leggings_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_leggings_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_leggings_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_BOOTS:
                                $sx = $this->getConfig ( "ctf_shop_yellow_boots_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_boots_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_boots_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_CHESTPLATE1:
                                $sx = $this->getConfig ( "ctf_shop_yellow_chestplate1_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_chestplate1_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_chestplate1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_CHESTPLATE2:
                                $sx = $this->getConfig ( "ctf_shop_yellow_chestplate2_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_chestplate2_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_chestplate2_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_CHESTPLATE3:
                                $sx = $this->getConfig ( "ctf_shop_yellow_chestplate3_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_chestplate3_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_chestplate3_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_SANDSTONE:
                                $sx = $this->getConfig ( "ctf_shop_yellow_sandstone_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_sandstone_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_sandstone_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_ENDSTONE:
                                $sx = $this->getConfig ( "ctf_shop_yellow_endstone_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_endstone_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_endstone_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_IRON:
                                $sx = $this->getConfig ( "ctf_shop_yellow_iron_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_iron_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_iron_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_GLOWSTONE:
                                $sx = $this->getConfig ( "ctf_shop_yellow_glowstone_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_glowstone_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_glowstone_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_PICKAXE1:
                                $sx = $this->getConfig ( "ctf_shop_yellow_pickaxe1_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_pickaxe1_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_pickaxe1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_PICKAXE2:
                                $sx = $this->getConfig ( "ctf_shop_yellow_pickaxe2_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_pickaxe2_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_pickaxe2_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_PICKAXE3:
                                $sx = $this->getConfig ( "ctf_shop_yellow_pickaxe3_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_pickaxe3_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_pickaxe3_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_CHEST:
                                $sx = $this->getConfig ( "ctf_shop_yellow_chest_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_chest_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_chest_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_COBWEB:
                                $sx = $this->getConfig ( "ctf_shop_yellow_cobweb_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_cobweb_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_cobweb_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_GLASS:
                                $sx = $this->getConfig ( "ctf_shop_yellow_glass_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_glass_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_glass_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_BOW1:
                                $sx = $this->getConfig ( "ctf_shop_yellow_bow1_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_bow1_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_bow1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_BOW2:
                                $sx = $this->getConfig ( "ctf_shop_yellow_bow2_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_bow2_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_bow2_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_BOW3:
                                $sx = $this->getConfig ( "ctf_shop_yellow_bow3_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_bow3_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_bow3_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_ARROW:
                                $sx = $this->getConfig ( "ctf_shop_yellow_arrow_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_arrow_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_arrow_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_APPLE:
                                $sx = $this->getConfig ( "ctf_shop_yellow_apple_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_apple_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_apple_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_PORKCHOP:
                                $sx = $this->getConfig ( "ctf_shop_yellow_porkchop_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_porkchop_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_porkchop_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_CAKE:
                                $sx = $this->getConfig ( "ctf_shop_yellow_cake_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_cake_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_cake_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_STRENGHT:
                                $sx = $this->getConfig ( "ctf_shop_yellow_strenght_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_strenght_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_strenght_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_YELLOW_STICK:
                                $sx = $this->getConfig ( "ctf_shop_yellow_stick_x" );
				$sy = $this->getConfig ( "ctf_shop_yellow_stick_y" );
				$sz = $this->getConfig ( "ctf_shop_yellow_stick_z" );
				return new Position ( $sx, $sy, $sz );
				break;
//------------------------------------------------
//                                 BLUE
//------------------------------------------------
                            case self::SHOP_BLUE_SWORD1:
                                $sx = $this->getConfig ( "ctf_shop_blue_sword1_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_sword1_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_sword1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_SWORD2:
                                $sx = $this->getConfig ( "ctf_shop_blue_sword2_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_sword2_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_sword2_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_SWORD3:
                                $sx = $this->getConfig ( "ctf_shop_blue_sword3_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_sword3_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_sword3_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_HELMET:
                                $sx = $this->getConfig ( "ctf_shop_blue_helmet_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_helmet_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_helmet_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_LEGGINGS:
                                $sx = $this->getConfig ( "ctf_shop_blue_leggings_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_leggings_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_leggings_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_BOOTS:
                                $sx = $this->getConfig ( "ctf_shop_blue_boots_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_boots_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_boots_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_CHESTPLATE1:
                                $sx = $this->getConfig ( "ctf_shop_blue_chestplate1_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_chestplate1_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_chestplate1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_CHESTPLATE2:
                                $sx = $this->getConfig ( "ctf_shop_blue_chestplate2_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_chestplate2_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_chestplate2_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_CHESTPLATE3:
                                $sx = $this->getConfig ( "ctf_shop_blue_chestplate3_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_chestplate3_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_chestplate3_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_SANDSTONE:
                                $sx = $this->getConfig ( "ctf_shop_blue_sandstone_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_sandstone_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_sandstone_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_ENDSTONE:
                                $sx = $this->getConfig ( "ctf_shop_blue_endstone_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_endstone_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_endstone_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_IRON:
                                $sx = $this->getConfig ( "ctf_shop_blue_iron_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_iron_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_iron_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_GLOWSTONE:
                                $sx = $this->getConfig ( "ctf_shop_blue_glowstone_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_glowstone_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_glowstone_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_PICKAXE1:
                                $sx = $this->getConfig ( "ctf_shop_blue_pickaxe1_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_pickaxe1_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_pickaxe1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_PICKAXE2:
                                $sx = $this->getConfig ( "ctf_shop_blue_pickaxe2_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_pickaxe2_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_pickaxe2_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_PICKAXE3:
                                $sx = $this->getConfig ( "ctf_shop_blue_pickaxe3_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_pickaxe3_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_pickaxe3_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_CHEST:
                                $sx = $this->getConfig ( "ctf_shop_blue_chest_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_chest_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_chest_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_COBWEB:
                                $sx = $this->getConfig ( "ctf_shop_blue_cobweb_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_cobweb_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_cobweb_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_GLASS:
                                $sx = $this->getConfig ( "ctf_shop_blue_glass_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_glass_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_glass_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_BOW1:
                                $sx = $this->getConfig ( "ctf_shop_blue_bow1_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_bow1_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_bow1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_BOW2:
                                $sx = $this->getConfig ( "ctf_shop_blue_bow2_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_bow2_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_bow2_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_BOW3:
                                $sx = $this->getConfig ( "ctf_shop_blue_bow3_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_bow3_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_bow3_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_ARROW:
                                $sx = $this->getConfig ( "ctf_shop_blue_arrow_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_arrow_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_arrow_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_APPLE:
                                $sx = $this->getConfig ( "ctf_shop_blue_apple_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_apple_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_apple_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_PORKCHOP:
                                $sx = $this->getConfig ( "ctf_shop_blue_porkchop_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_porkchop_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_porkchop_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_CAKE:
                                $sx = $this->getConfig ( "ctf_shop_blue_cake_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_cake_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_cake_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_STRENGHT:
                                $sx = $this->getConfig ( "ctf_shop_blue_strenght_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_strenght_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_strenght_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_BLUE_STICK:
                                $sx = $this->getConfig ( "ctf_shop_blue_stick_x" );
				$sy = $this->getConfig ( "ctf_shop_blue_stick_y" );
				$sz = $this->getConfig ( "ctf_shop_blue_stick_z" );
				return new Position ( $sx, $sy, $sz );
				break;
//-----------------------------------------------------------
//                                  RED
//-----------------------------------------------------------
                            case self::SHOP_RED_SWORD1:
                                $sx = $this->getConfig ( "ctf_shop_red_sword1_x" );
				$sy = $this->getConfig ( "ctf_shop_red_sword1_y" );
				$sz = $this->getConfig ( "ctf_shop_red_sword1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_SWORD2:
                                $sx = $this->getConfig ( "ctf_shop_red_sword2_x" );
				$sy = $this->getConfig ( "ctf_shop_red_sword2_y" );
				$sz = $this->getConfig ( "ctf_shop_red_sword2_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_SWORD3:
                                $sx = $this->getConfig ( "ctf_shop_red_sword3_x" );
				$sy = $this->getConfig ( "ctf_shop_red_sword3_y" );
				$sz = $this->getConfig ( "ctf_shop_red_sword3_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_HELMET:
                                $sx = $this->getConfig ( "ctf_shop_red_helmet_x" );
				$sy = $this->getConfig ( "ctf_shop_red_helmet_y" );
				$sz = $this->getConfig ( "ctf_shop_red_helmet_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_LEGGINGS:
                                $sx = $this->getConfig ( "ctf_shop_red_leggings_x" );
				$sy = $this->getConfig ( "ctf_shop_red_leggings_y" );
				$sz = $this->getConfig ( "ctf_shop_red_leggings_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_BOOTS:
                                $sx = $this->getConfig ( "ctf_shop_red_boots_x" );
				$sy = $this->getConfig ( "ctf_shop_red_boots_y" );
				$sz = $this->getConfig ( "ctf_shop_red_boots_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_CHESTPLATE1:
                                $sx = $this->getConfig ( "ctf_shop_red_chestplate1_x" );
				$sy = $this->getConfig ( "ctf_shop_red_chestplate1_y" );
				$sz = $this->getConfig ( "ctf_shop_red_chestplate1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_CHESTPLATE2:
                                $sx = $this->getConfig ( "ctf_shop_red_chestplate2_x" );
				$sy = $this->getConfig ( "ctf_shop_red_chestplate2_y" );
				$sz = $this->getConfig ( "ctf_shop_red_chestplate2_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_CHESTPLATE3:
                                $sx = $this->getConfig ( "ctf_shop_red_chestplate3_x" );
				$sy = $this->getConfig ( "ctf_shop_red_chestplate3_y" );
				$sz = $this->getConfig ( "ctf_shop_red_chestplate3_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_SANDSTONE:
                                $sx = $this->getConfig ( "ctf_shop_red_sandstone_x" );
				$sy = $this->getConfig ( "ctf_shop_red_sandstone_y" );
				$sz = $this->getConfig ( "ctf_shop_red_sandstone_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_ENDSTONE:
                                $sx = $this->getConfig ( "ctf_shop_red_endstone_x" );
				$sy = $this->getConfig ( "ctf_shop_red_endstone_y" );
				$sz = $this->getConfig ( "ctf_shop_red_endstone_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_IRON:
                                $sx = $this->getConfig ( "ctf_shop_red_iron_x" );
				$sy = $this->getConfig ( "ctf_shop_red_iron_y" );
				$sz = $this->getConfig ( "ctf_shop_red_iron_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_GLOWSTONE:
                                $sx = $this->getConfig ( "ctf_shop_red_glowstone_x" );
				$sy = $this->getConfig ( "ctf_shop_red_glowstone_y" );
				$sz = $this->getConfig ( "ctf_shop_red_glowstone_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_PICKAXE1:
                                $sx = $this->getConfig ( "ctf_shop_red_pickaxe1_x" );
				$sy = $this->getConfig ( "ctf_shop_red_pickaxe1_y" );
				$sz = $this->getConfig ( "ctf_shop_red_pickaxe1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_PICKAXE2:
                                $sx = $this->getConfig ( "ctf_shop_red_pickaxe2_x" );
				$sy = $this->getConfig ( "ctf_shop_red_pickaxe2_y" );
				$sz = $this->getConfig ( "ctf_shop_red_pickaxe2_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_PICKAXE3:
                                $sx = $this->getConfig ( "ctf_shop_red_pickaxe3_x" );
				$sy = $this->getConfig ( "ctf_shop_red_pickaxe3_y" );
				$sz = $this->getConfig ( "ctf_shop_red_pickaxe3_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_CHEST:
                                $sx = $this->getConfig ( "ctf_shop_red_chest_x" );
				$sy = $this->getConfig ( "ctf_shop_red_chest_y" );
				$sz = $this->getConfig ( "ctf_shop_red_chest_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_COBWEB:
                                $sx = $this->getConfig ( "ctf_shop_red_cobweb_x" );
				$sy = $this->getConfig ( "ctf_shop_red_cobweb_y" );
				$sz = $this->getConfig ( "ctf_shop_red_cobweb_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_GLASS:
                                $sx = $this->getConfig ( "ctf_shop_red_glass_x" );
				$sy = $this->getConfig ( "ctf_shop_red_glass_y" );
				$sz = $this->getConfig ( "ctf_shop_red_glass_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_BOW1:
                                $sx = $this->getConfig ( "ctf_shop_red_bow1_x" );
				$sy = $this->getConfig ( "ctf_shop_red_bow1_y" );
				$sz = $this->getConfig ( "ctf_shop_red_bow1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_BOW2:
                                $sx = $this->getConfig ( "ctf_shop_red_bow2_x" );
				$sy = $this->getConfig ( "ctf_shop_red_bow2_y" );
				$sz = $this->getConfig ( "ctf_shop_red_bow2_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_BOW3:
                                $sx = $this->getConfig ( "ctf_shop_red_bow3_x" );
				$sy = $this->getConfig ( "ctf_shop_red_bow3_y" );
				$sz = $this->getConfig ( "ctf_shop_red_bow3_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_ARROW:
                                $sx = $this->getConfig ( "ctf_shop_red_arrow_x" );
				$sy = $this->getConfig ( "ctf_shop_red_arrow_y" );
				$sz = $this->getConfig ( "ctf_shop_red_arrow_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_APPLE:
                                $sx = $this->getConfig ( "ctf_shop_red_apple_x" );
				$sy = $this->getConfig ( "ctf_shop_red_apple_y" );
				$sz = $this->getConfig ( "ctf_shop_red_apple_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_PORKCHOP:
                                $sx = $this->getConfig ( "ctf_shop_red_porkchop_x" );
				$sy = $this->getConfig ( "ctf_shop_red_porkchop_y" );
				$sz = $this->getConfig ( "ctf_shop_red_porkchop_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_CAKE:
                                $sx = $this->getConfig ( "ctf_shop_red_cake_x" );
				$sy = $this->getConfig ( "ctf_shop_red_cake_y" );
				$sz = $this->getConfig ( "ctf_shop_red_cake_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_STRENGHT:
                                $sx = $this->getConfig ( "ctf_shop_red_strenght_x" );
				$sy = $this->getConfig ( "ctf_shop_red_strenght_y" );
				$sz = $this->getConfig ( "ctf_shop_red_strenght_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_RED_STICK:
                                $sx = $this->getConfig ( "ctf_shop_red_stick_x" );
				$sy = $this->getConfig ( "ctf_shop_red_stick_y" );
				$sz = $this->getConfig ( "ctf_shop_red_stick_z" );
				return new Position ( $sx, $sy, $sz );
				break;
//-----------------------------------------------------------
//                                  GREEN
//-----------------------------------------------------------
                            case self::SHOP_GREEN_SWORD1:
                                $sx = $this->getConfig ( "ctf_shop_green_sword1_x" );
				$sy = $this->getConfig ( "ctf_shop_green_sword1_y" );
				$sz = $this->getConfig ( "ctf_shop_green_sword1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_SWORD2:
                                $sx = $this->getConfig ( "ctf_shop_green_sword2_x" );
				$sy = $this->getConfig ( "ctf_shop_green_sword2_y" );
				$sz = $this->getConfig ( "ctf_shop_green_sword2_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_SWORD3:
                                $sx = $this->getConfig ( "ctf_shop_green_sword3_x" );
				$sy = $this->getConfig ( "ctf_shop_green_sword3_y" );
				$sz = $this->getConfig ( "ctf_shop_green_sword3_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_HELMET:
                                $sx = $this->getConfig ( "ctf_shop_green_helmet_x" );
				$sy = $this->getConfig ( "ctf_shop_green_helmet_y" );
				$sz = $this->getConfig ( "ctf_shop_green_helmet_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_LEGGINGS:
                                $sx = $this->getConfig ( "ctf_shop_green_leggings_x" );
				$sy = $this->getConfig ( "ctf_shop_green_leggings_y" );
				$sz = $this->getConfig ( "ctf_shop_green_leggings_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_BOOTS:
                                $sx = $this->getConfig ( "ctf_shop_green_boots_x" );
				$sy = $this->getConfig ( "ctf_shop_green_boots_y" );
				$sz = $this->getConfig ( "ctf_shop_green_boots_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_CHESTPLATE1:
                                $sx = $this->getConfig ( "ctf_shop_green_chestplate1_x" );
				$sy = $this->getConfig ( "ctf_shop_green_chestplate1_y" );
				$sz = $this->getConfig ( "ctf_shop_green_chestplate1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_CHESTPLATE2:
                                $sx = $this->getConfig ( "ctf_shop_green_chestplate2_x" );
				$sy = $this->getConfig ( "ctf_shop_green_chestplate2_y" );
				$sz = $this->getConfig ( "ctf_shop_green_chestplate2_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_CHESTPLATE3:
                                $sx = $this->getConfig ( "ctf_shop_green_chestplate3_x" );
				$sy = $this->getConfig ( "ctf_shop_green_chestplate3_y" );
				$sz = $this->getConfig ( "ctf_shop_green_chestplate3_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_SANDSTONE:
                                $sx = $this->getConfig ( "ctf_shop_green_sandstone_x" );
				$sy = $this->getConfig ( "ctf_shop_green_sandstone_y" );
				$sz = $this->getConfig ( "ctf_shop_green_sandstone_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_ENDSTONE:
                                $sx = $this->getConfig ( "ctf_shop_green_endstone_x" );
				$sy = $this->getConfig ( "ctf_shop_green_endstone_y" );
				$sz = $this->getConfig ( "ctf_shop_green_endstone_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_IRON:
                                $sx = $this->getConfig ( "ctf_shop_green_iron_x" );
				$sy = $this->getConfig ( "ctf_shop_green_iron_y" );
				$sz = $this->getConfig ( "ctf_shop_green_iron_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_GLOWSTONE:
                                $sx = $this->getConfig ( "ctf_shop_green_glowstone_x" );
				$sy = $this->getConfig ( "ctf_shop_green_glowstone_y" );
				$sz = $this->getConfig ( "ctf_shop_green_glowstone_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_PICKAXE1:
                                $sx = $this->getConfig ( "ctf_shop_green_pickaxe1_x" );
				$sy = $this->getConfig ( "ctf_shop_green_pickaxe1_y" );
				$sz = $this->getConfig ( "ctf_shop_green_pickaxe1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_PICKAXE2:
                                $sx = $this->getConfig ( "ctf_shop_green_pickaxe2_x" );
				$sy = $this->getConfig ( "ctf_shop_green_pickaxe2_y" );
				$sz = $this->getConfig ( "ctf_shop_green_pickaxe2_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_PICKAXE3:
                                $sx = $this->getConfig ( "ctf_shop_green_pickaxe3_x" );
				$sy = $this->getConfig ( "ctf_shop_green_pickaxe3_y" );
				$sz = $this->getConfig ( "ctf_shop_green_pickaxe3_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_CHEST:
                                $sx = $this->getConfig ( "ctf_shop_green_chest_x" );
				$sy = $this->getConfig ( "ctf_shop_green_chest_y" );
				$sz = $this->getConfig ( "ctf_shop_green_chest_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_COBWEB:
                                $sx = $this->getConfig ( "ctf_shop_green_cobweb_x" );
				$sy = $this->getConfig ( "ctf_shop_green_cobweb_y" );
				$sz = $this->getConfig ( "ctf_shop_green_cobweb_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_GLASS:
                                $sx = $this->getConfig ( "ctf_shop_green_glass_x" );
				$sy = $this->getConfig ( "ctf_shop_green_glass_y" );
				$sz = $this->getConfig ( "ctf_shop_green_glass_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_BOW1:
                                $sx = $this->getConfig ( "ctf_shop_green_bow1_x" );
				$sy = $this->getConfig ( "ctf_shop_green_bow1_y" );
				$sz = $this->getConfig ( "ctf_shop_green_bow1_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_BOW2:
                                $sx = $this->getConfig ( "ctf_shop_green_bow2_x" );
				$sy = $this->getConfig ( "ctf_shop_green_bow2_y" );
				$sz = $this->getConfig ( "ctf_shop_green_bow2_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_BOW3:
                                $sx = $this->getConfig ( "ctf_shop_green_bow3_x" );
				$sy = $this->getConfig ( "ctf_shop_green_bow3_y" );
				$sz = $this->getConfig ( "ctf_shop_green_bow3_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_ARROW:
                                $sx = $this->getConfig ( "ctf_shop_green_arrow_x" );
				$sy = $this->getConfig ( "ctf_shop_green_arrow_y" );
				$sz = $this->getConfig ( "ctf_shop_green_arrow_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_APPLE:
                                $sx = $this->getConfig ( "ctf_shop_green_apple_x" );
				$sy = $this->getConfig ( "ctf_shop_green_apple_y" );
				$sz = $this->getConfig ( "ctf_shop_green_apple_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_PORKCHOP:
                                $sx = $this->getConfig ( "ctf_shop_green_porkchop_x" );
				$sy = $this->getConfig ( "ctf_shop_green_porkchop_y" );
				$sz = $this->getConfig ( "ctf_shop_green_porkchop_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_CAKE:
                                $sx = $this->getConfig ( "ctf_shop_green_cake_x" );
				$sy = $this->getConfig ( "ctf_shop_green_cake_y" );
				$sz = $this->getConfig ( "ctf_shop_green_cake_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_STRENGHT:
                                $sx = $this->getConfig ( "ctf_shop_green_strenght_x" );
				$sy = $this->getConfig ( "ctf_shop_green_strenght_y" );
				$sz = $this->getConfig ( "ctf_shop_green_strenght_z" );
				return new Position ( $sx, $sy, $sz );
				break;
                            case self::SHOP_GREEN_STICK:
                                $sx = $this->getConfig ( "ctf_shop_green_stick_x" );
				$sy = $this->getConfig ( "ctf_shop_green_stick_y" );
				$sz = $this->getConfig ( "ctf_shop_green_stick_z" );
				return new Position ( $sx, $sy, $sz );
				break;
			default :
				return null;
		}
	}
	
	/**
	 * Handle Click Sign Setup Actions
	 *
	 * @param Player $player        	
	 * @param unknown $setupAction        	
	 * @param Position $pos        	
	 */
	public function handleClickSignSetup(Player $player, $setupAction, Position $pos) {
		// handle setup selection
		if ($setupAction == CTFManager::CTF_COMMAND_SET_SIGN_VIEW_STATS) {
			$this->getPlugIn ()->setupModeAction = "";
			if ($this->setSignPosViewStats ( $pos )) {
				$player->sendMessage ( $this->getMsg ( "ctf.setup.success" ) . "\n" . round ( $pos->x ) . " " . round ( $pos->y ) . " " . round ( $pos->z ) );
			} else {
				$player->sendMessage ( $this->getMsg ( "ctf.setup.failed" ) . "\n" . round ( $pos->x ) . " " . round ( $pos->y ) . " " . round ( $pos->z ) );
			}
			return;
		}
		if ($setupAction == CTFManager::CTF_COMMAND_SET_SIGN_NEW_GAME) {
			$this->getPlugIn ()->setupModeAction = "";
			if ($this->setSignPosNewGame ( $pos )) {
				$player->sendMessage ( $this->getMsg ( "ctf.setup.success" ) . "\n" . round ( $pos->x ) . " " . round ( $pos->y ) . " " . round ( $pos->z ) );
			} else {
				$player->sendMessage ( $this->getMsg ( "ctf.setup.failed" ) . "\n" . round ( $pos->x ) . " " . round ( $pos->y ) . " " . round ( $pos->z ) );
			}
			return;
		}
		if ($setupAction == CTFManager::CTF_COMMAND_SET_SIGN_JOIN_BLUE_TEAM) {
			$this->getPlugIn ()->setupModeAction = "";
			if ($this->setSignPosJoinBlue ( $pos )) {
				$player->sendMessage ( $this->getMsg ( "ctf.setup.success" ) . "\n" . round ( $pos->x ) . " " . round ( $pos->y ) . " " . round ( $pos->z ) );
			} else {
				$player->sendMessage ( $this->getMsg ( "ctf.setup.failed" ) . "\n" . round ( $pos->x ) . " " . round ( $pos->y ) . " " . round ( $pos->z ) );
			}
			return;
		}
		if ($setupAction == CTFManager::CTF_COMMAND_SET_SIGN_JOIN_RED_TEAM) {
			$this->getPlugIn ()->setupModeAction = "";
			if ($this->setSignPosJoinRed ( $pos )) {
				$player->sendMessage ( $this->getMsg ( "ctf.setup.success" ) . "\n" . round ( $pos->x ) . " " . round ( $pos->y ) . " " . round ( $pos->z ) );
			} else {
				$player->sendMessage ( $this->getMsg ( "ctf.setup.failed" ) . "\n" . round ( $pos->x ) . " " . round ( $pos->y ) . " " . round ( $pos->z ) );
			}
			return;
		}
		if ($setupAction == CTFManager::CTF_COMMAND_SET_SIGN_JOIN_YELLOW_TEAM) {
			$this->getPlugIn ()->setupModeAction = "";
			if ($this->setSignPosJoinYellow ( $pos )) {
				$player->sendMessage ( $this->getMsg ( "ctf.setup.success" ) . "\n" . round ( $pos->x ) . " " . round ( $pos->y ) . " " . round ( $pos->z ) );
			} else {
				$player->sendMessage ( $this->getMsg ( "ctf.setup.failed" ) . "\n" . round ( $pos->x ) . " " . round ( $pos->y ) . " " . round ( $pos->z ) );
			}
			return;
		}
		if ($setupAction == CTFManager::CTF_COMMAND_SET_SIGN_JOIN_GREEN_TEAM) {
			$this->getPlugIn ()->setupModeAction = "";
			if ($this->setSignPosJoinGreen ( $pos )) {
				$player->sendMessage ( $this->getMsg ( "ctf.setup.success" ) . "\n" . round ( $pos->x ) . " " . round ( $pos->y ) . " " . round ( $pos->z ) );
			} else {
				$player->sendMessage ( $this->getMsg ( "ctf.setup.failed" ) . "\n" . round ( $pos->x ) . " " . round ( $pos->y ) . " " . round ( $pos->z ) );
			}
			return;
		}
	}
	
	/**
	 * Setup Sign for View Stats
	 *
	 * @param Position $pos        	
	 * @return boolean
	 */
	public function setSignPosViewStats(Position $pos) {
		$success = false;
		try {
			$config = $this->getPlugIn ()->getConfig ();
			$config->set ( "ctf_stat_sign_x", round ( $pos->x ) );
			$config->set ( "ctf_stat_sign_y", round ( $pos->y ) );
			$config->set ( "ctf_stat_sign_z", round ( $pos->z ) );
			$config->save ();
			$success = true;
		} catch ( \Exception $e ) {
			$this->getPlugIn ()->getLogger ()->error ( $e->getMessage () );
		}
		return $success;
	}
	/**
	 * Setup Sign for Join Blue Team
	 *
	 * @param Position $pos        	
	 * @return boolean
	 */
	public function setSignPosJoinBlue(Position $pos) {
		$success = false;
		try {
			$config = $this->getPlugIn ()->getConfig ();
			$config->set ( "ctf_blue_team_join_sign1_x", round ( $pos->x ) );
			$config->set ( "ctf_blue_team_join_sign1_y", round ( $pos->y ) );
			$config->set ( "ctf_blue_team_join_sign1_z", round ( $pos->z ) );
			$config->save ();
			$success = true;
		} catch ( \Exception $e ) {
			$this->getPlugIn ()->getLogger ()->error ( $e->getMessage () );
		}
		return $success;
	}
	/**
	 * Setup Sign for Join Red Team
	 *
	 * @param Position $pos        	
	 * @return boolean
	 */
	public function setSignPosJoinRed(Position $pos) {
		$success = false;
		try {
			$config = $this->getPlugIn ()->getConfig ();
			$config->set ( "ctf_red_team_join_sign1_x", round ( $pos->x ) );
			$config->set ( "ctf_red_team_join_sign1_y", round ( $pos->y ) );
			$config->set ( "ctf_red_team_join_sign1_z", round ( $pos->z ) );
			$config->save ();
			$success = true;
		} catch ( \Exception $e ) {
			$this->getPlugIn ()->getLogger ()->error ( $e->getMessage () );
		}
		return $success;
	}

	public function setSignPosJoinYellow(Position $pos) {
		$success = false;
		try {
			$config = $this->getPlugIn ()->getConfig ();
			$config->set ( "ctf_yellow_team_join_sign1_x", round ( $pos->x ) );
			$config->set ( "ctf_yellow_team_join_sign1_y", round ( $pos->y ) );
			$config->set ( "ctf_yellow_team_join_sign1_z", round ( $pos->z ) );
			$config->save ();
			$success = true;
		} catch ( \Exception $e ) {
			$this->getPlugIn ()->getLogger ()->error ( $e->getMessage () );
		}
		return $success;
	}

	public function setSignPosJoinGreen(Position $pos) {
		$success = false;
		try {
			$config = $this->getPlugIn ()->getConfig ();
			$config->set ( "ctf_green_team_join_sign1_x", round ( $pos->x ) );
			$config->set ( "ctf_green_team_join_sign1_y", round ( $pos->y ) );
			$config->set ( "ctf_green_team_join_sign1_z", round ( $pos->z ) );
			$config->save ();
			$success = true;
		} catch ( \Exception $e ) {
			$this->getPlugIn ()->getLogger ()->error ( $e->getMessage () );
		}
		return $success;
	}

	/**
	 * Setup Sign for New Game
	 *
	 * @param Position $pos        	
	 * @return boolean
	 */
	public function setSignPosNewGame(Position $pos) {
		$success = false;
		try {
			$config = $this->getPlugIn ()->getConfig ();
			$config->set ( "ctf_new_sign_x", round ( $pos->x ) );
			$config->set ( "ctf_new_sign_y", round ( $pos->y ) );
			$config->set ( "ctf_new_sign_z", round ( $pos->z ) );
			$config->save ();
			$success = true;
		} catch ( \Exception $e ) {
			$this->getPlugIn ()->getLogger ()->error ( $e->getMessage () );
		}
		return $success;
	}
	
	/**
	 * Handle Set Block Setup Action
	 * 	  
	 * @param Player $player        	
	 * @param unknown $setupAction        	
	 * @param Position $pos        	
	 */
	public function handleSetBlockSetup(Player $player, $setupAction, $blockId) {
		// handle setup selection
		if ($setupAction == CTFManager::CTF_COMMAND_SETBLOCK_ID_TEAM_BORDER) {
			$this->getPlugIn ()->setupModeAction = "";
			if ($this->setBorderFenceBlock ( $blockId )) {
				$player->sendMessage ( $this->getMsg ( "ctf.setup.success" ) . "\n" . "set block id:" . $blockId );
			} else {
				$player->sendMessage ( $this->getMsg ( "ctf.setup.failed" ) . "\n" );
			}
			return;
		}
		
		if ($setupAction == CTFManager::CTF_COMMAND_SETBLOCK_ID_DEFENCE_WALL_BLUE_TEAM) {
			$this->getPlugIn ()->setupModeAction = "";
			if ($this->setBlueTeamDefenceWallBlock ( $blockId )) {
				$player->sendMessage ( $this->getMsg ( "ctf.setup.success" ) . "\n" . "set block id :" . $blockId );
			} else {
				$player->sendMessage ( $this->getMsg ( "ctf.setup.failed" ) . "\n" );
			}
			return;
		}
		
		if ($setupAction == CTFManager::CTF_COMMAND_SETBLOCK_ID_DEFENCE_WALL_RED_TEAM) {
			$this->getPlugIn ()->setupModeAction = "";
			if ($this->setRedTeamDefenceWallBlock ( $blockId )) {
				$player->sendMessage ( $this->getMsg ( "ctf.setup.success" ) . "\n" . "set block id :" . $blockId );
			} else {
				$player->sendMessage ( $this->getMsg ( "ctf.setup.failed" ) . "\n" );
			}
			return;
		}
	}
	
	/**
	 * Setup Border Fence Block Type
	 * # Block Id must be a valid PE block id
	 * # 85 - wood fence
	 * # 101 - iron fence
	 * # 98 - stone
	 * # 45 - brick
	 * # 48 - mossy stone
	 *
	 * @param Position $pos        	
	 * @return boolean
	 */
	public function setBorderFenceBlock($blockId) {
		$success = false;
		try {
			$config = $this->getPlugIn ()->getConfig ();
			$config->set ( "ctf_border_fence", $blockId );
			$config->save ();
			$success = true;
		} catch ( \Exception $e ) {
			$this->getPlugIn ()->getLogger ()->error ( $e->getMessage () );
		}
		return $success;
	}
	
	/**
	 * Setup Blue Team Defence Wall Block Type
	 *
	 * @param Position $pos        	
	 * @return boolean
	 */
	public function setBlueTeamDefenceWallBlock($blockId) {
		$success = false;
		try {
			$config = $this->getPlugIn ()->getConfig ();
			$config->set ( "blue_team_defence_wall", $blockId );
			$config->save ();
			$success = true;
		} catch ( \Exception $e ) {
			$this->getPlugIn ()->getLogger ()->error ( $e->getMessage () );
		}
		return $success;
	}
	
	/**
	 * Setup Red Team Defence Wall Block Type
	 *
	 * @param Position $pos        	
	 * @return boolean
	 */
	public function setRedTeamDefenceWallBlock($blockId) {
		$success = false;
		try {
			$config = $this->getPlugIn ()->getConfig ();
			$config->set ( "red_team_defence_wall", $blockId );
			$config->save ();
			$success = true;
		} catch ( \Exception $e ) {
			$this->getPlugIn ()->getLogger ()->error ( $e->getMessage () );
		}
		return $success;
	}
}