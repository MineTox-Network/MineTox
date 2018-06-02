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
use pocketmine\utils\Cache;
use pocketmine\level\Explosion;
use pocketmine\event\block\BlockEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\math\Vector3 as Vector3;
use pocketmine\math\Vector2 as Vector2;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\network\protocol\AddMobPacket;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\network\protocol\UpdateBlockPacket;
use pocketmine\block\Block;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\protocol\DataPacket;
use pocketmine\network\protocol\Info;
use pocketmine\network\protocol\LoginPacket;
use pocketmine\network\protocol\AddItemEntityPacket;
use pocketmine\entity\FallingBlock;
use pocketmine\nbt\NBT;
use pocketmine\item\ItemBlock;
use pocketmine\block\SignPost;
use pocketmine\item\Sign;
use pocketmine\item\Item;
use pocketmine\block\Liquid;
use pocketmine\inventory\ChestInventory;
use pocketmine\tile\Chest;

/**
 * CTF Arena Builder
 *
 * Copyright (C) 2014 minecraftgenius76
 *
 * @author MCG76
 * @link http://www.youtube.com/user/minecraftgenius76
 *      
 */
class CTFBlockBuilder extends MiniGameBase  {
	public $boardsize = 16;
	public $wallBlocksTypes = [ ];
	public $floorBlocksTypes = [ ];
	public $blueTeamFloorBlocks = [ ];
	public $redTeamFloorBlocks = [ ];
        
	/**
	 * Constructor
	 *
	 * @param CTFPlugIn $pg        	
	 */
	public function __construct(CTFPlugIn $plugin) {
		parent::__construct ( $plugin );
	}
	
	/**
	 * Add Game Buttons
	 *
	 * @param Level $level        	
	 */

	
	/**
	 * Add Fires
	 *
	 * @param Level $level        	
	 */
	
	/**
	 * Add Blue Flag
	 *
	 * @param Level $level        	
	 * @param unknown $blockType        	
	 * @param unknown $meta        	
	 */
	public function addBlueTeamFlag(Level $level, $blockType, $meta) {
		$blueTeamFlagPos = $this->getSetup ()->getFlagPos ( CTFSetup::CTF_FLAG_BLUE_TEAM );
                $blueTeamFlag2Pos = $this->getSetup()->getFlagPos(CTFSetup::CTF_FLAG2_BLUE_TEAM);
		$cx = $blueTeamFlag2Pos->x;
                $cy = $blueTeamFlag2Pos->y;
                $cz = $blueTeamFlag2Pos->z;
                $sx = $blueTeamFlagPos->x;
		$sy = $blueTeamFlagPos->y;
		$sz = $blueTeamFlagPos->z;
		$rb = $level->getBlock ( new Vector3 ( $sx, $sy, $sz ) );
                //$level->setBlock(new Vector3($cx, $cy, $cz), Block::get(26, 10));;

                $this->resetBlock ( $rb, $level, $blockType, $meta );
	}
	public function addRedTeamFlag(Level $level, $blockType, $meta) {
		$redTeamFlagPos = $this->getSetup ()->getFlagPos ( CTFSetup::CTF_FLAG_RED_TEAM );
		$sx = $redTeamFlagPos->x;
		$sy = $redTeamFlagPos->y;
		$sz = $redTeamFlagPos->z;
		$rb = $level->getBlock ( new Vector3 ( $sx, $sy, $sz ) );
		$this->resetBlock ( $rb, $level, $blockType, $meta );
	}
        public function addYellowTeamFlag(Level $level, $blockType, $meta) {
		$yellowTeamFlagPos = $this->getSetup ()->getFlagPos ( CTFSetup::CTF_FLAG_YELLOW_TEAM );
		$sx = $yellowTeamFlagPos->x;
		$sy = $yellowTeamFlagPos->y;
		$sz = $yellowTeamFlagPos->z;
		$rb = $level->getBlock ( new Vector3 ( $sx, $sy, $sz ) );
		$this->resetBlock ( $rb, $level, $blockType, $meta );
	}
        public function addGreenTeamFlag(Level $level, $blockType, $meta) {
		$greenTeamFlagPos = $this->getSetup ()->getFlagPos ( CTFSetup::CTF_FLAG_GREEN_TEAM );
		$sx = $greenTeamFlagPos->x;
		$sy = $greenTeamFlagPos->y;
		$sz = $greenTeamFlagPos->z;
		$rb = $level->getBlock ( new Vector3 ( $sx, $sy, $sz ) );
		$this->resetBlock ( $rb, $level, $blockType, $meta );
	}

	
	/**
	 * remove blocks
	 *
	 * @param array $blocks        	
	 * @param Player $p        	
	 */
	public function removeBlocks(Block $block, Player $xp) {
		$this->updateBlock ( $block, $xp, 0 );
	}
	public function removeUpdateBlock($topblock, $tntblock) {
		foreach ( $this->getPlugin ()->livePlayers as $livep ) {
			if ($livep instanceof MGArenaPlayer) {
				$this->removeBlocks ( $topblock, $livep->player );
				$this->removeBlocks ( $tntblock, $livep->player );
			} else {
				$this->removeBlocks ( $topblock, $livep );
				$this->removeBlocks ( $tntblock, $livep );
			}
		}
	}
	
	/**
	 * Render and Update Block
	 *
	 * @param Block $block
	 * @param Level $level
	 * @param unknown $blockType
	 * @param number $meta
	 */
	public function resetBlock(Block $block, Level $level, $blockType, $meta) {
		$this->updateBlock2 ( $block, $level, $blockType, $meta);
	}
	/**
	 *
	 * @param Block $block
	 * @param Player $p
	 * @param unknown $blockType
	 */
	public function replaceBlockType(Level $level, Block $block, $blockType) {
		$this->updateBlock2 ( $block, $level, $blockType );
	}
	
	/**
	 * Update block
	 *
	 * @param Block $block        	
	 * @param Player $xp        	
	 * @param unknown $blockType        	
	 */
	public function updateBlock(Block $block, Player $xp, $blockType) {
		$this->updateBlock2 ( $block, $xp->level, $blockType );
	}
	
	
	public function updateBlock2(Block $block, Level $level, $blockType, $meta) {
		$players = $level->getPlayers ();
		foreach ( $players as $p ) {
			$pk = new UpdateBlockPacket ();
			$pk->x = $block->getX ();
			$pk->y = $block->getY ();
			$pk->z = $block->getZ ();
			$pk->block = $blockType;
			$pk->meta = $meta;
			$p->dataPacket ( $pk );
			$level->setBlockIdAt ( $block->getX (), $block->getY (), $block->getZ (), $blockType );
			$pos = new Position ( $block->x, $block->y, $block->z );
			$block = $level->getBlock ( $pos, true );
			$direct = true;
			$update = true;
			$level->setBlock ( $pos, $block, $direct, $update );
		}
	}
	
	/**
	 * render random blocks
	 *
	 * @param Block $block        	
	 * @param Player $p        	
	 */
	public function renderRandomBlocks(Block $block, Player $p) {
		$b = array_rand ( $this->boardBlocksTypes );
		$blockType = $this->boardBlocksTypes [$b];
		// randomly place a mine
		$this->updateBlock ( $block, $p, $blockType );
	}
	
	/**
	 *
	 * @param Block $block        	
	 * @param Player $p        	
	 * @param unknown $blockType        	
	 */
	public function renderBlockByType(Block $block, Player $p, $blockType) {
		// randomly place a mine
		$this->updateBlock ( $block, $p, $blockType );
	}
	
	/**
	 * replace random blocks
	 *
	 * @param Block $block        	
	 * @param Player $p        	
	 */
	public function replaceRandomBlocks(Level $level, Block $block) {
		$b = array_rand ( $this->boardBlocksTypes );
		$blockType = $this->boardBlocksTypes [$b];
		// randomly place a mine
		$this->replaceBlockType ( $level, $block, $blockType );
	}
	

	/**
	 * remove arena
	 *
	 * @param unknown $player        	
	 * @param unknown $xx        	
	 * @param unknown $yy        	
	 * @param unknown $zz        	
	 */
	public function removeArena($player, $xx, $yy, $zz) {
		$wallheighSize = 70;
		$bsize = $this->boardsize;
		$xmax = $this->boardsize + 3;
		$ymax = $this->boardsize;
		
		For($z = 0; $z <= $xmax; $z ++) {
			For($x = 0; $x <= $xmax; $x ++) {
				For($y = 0; $y <= $wallheighSize; $y ++) {
					$mx = $xx + $x;
					$my = $yy + $y;
					$mz = $zz + $z;
					$bk = $player->getLevel ()->getBlock ( new Vector3 ( $mx, $my, $mz ) );
					$this->removeBlocks ( $bk, $player );
				}
			}
		}
	}
        
public function dropBrickItems($level){
    $level = Server::getInstance()->getLevelByName("bw1");
        $chestb = $level->getTile(new Vector3(-6, 8, 400));
        $chestr = $level->getTile(new Vector3(224, 8, 380));
        $chesty = $level->getTile(new Vector3(119, 8, 505));
        $chestg = $level->getTile(new Vector3(99, 8, 275));
        
        if($chestb instanceof Chest){
            $chestb->getInventory()->addItem(Item::get(336));
        }
        if($chestr instanceof Chest){
            $chestr->getInventory()->addItem(Item::get(336));
        }
        if($chesty instanceof Chest){
            $chesty->getInventory()->addItem(Item::get(336));
        }
        if($chestg instanceof Chest){
            $chestg->getInventory()->addItem(Item::get(336));
        }
}

public function dropIronItems($level){
                        $posb = new Vector3(70, 9, 390);
                        $posr = new Vector3(148, 9, 390);
                        $posy = new Vector3(109, 9, 429);
                        $posg = new Vector3(109, 9, 351);
                        
                        $level->dropItem($posb, Item::get(265));
                        $level->dropItem($posr, Item::get(265));
                        $level->dropItem($posy, Item::get(265));
                        $level->dropItem($posg, Item::get(265));
}

public function dropGoldItems($level){
                        $posb = new Vector3(106, 9, 390);
                        $posr = new Vector3(112, 9, 390);
                        $posy = new Vector3(109, 9, 393);
                        $posg = new Vector3(109, 9, 387);
                        
                        $level->dropItem($posb, Item::get(266));
                        $level->dropItem($posr, Item::get(266));
                        $level->dropItem($posy, Item::get(266));
                        $level->dropItem($posg, Item::get(266));
}

public function removeBwBlocks(Level $level){
    $blockAt = $level->getBlock();
    if($blockAt->getId() == 2){
        $this->updateBlock2($blockAt, $level, 0);
    }
    if($id == 24 || $id == 2 || $id == 30 || $id == 42 || $id == 54 || $id == 89 || $id == 121 || $id == 19 || $id == 20){
    }
}

}