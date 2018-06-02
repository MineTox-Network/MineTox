<?php

namespace BedWars\Arena;

use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;
use pocketmine\inventory\BaseInventory;
use pocketmine\level\Position;
use pocketmine\level\Level;

class CTFShop  extends MiniGameBase  {
    public function __construct(BedWars $plugin) {
		parent::__construct ( $plugin );
	}
        
        
        public function onClickShopSign(Player $player, $blockTouched) {
            if($this->getPlugIn()->ingame == true){
            $sword1 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_SWORD1);
            $sword2 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_SWORD2 );
            $sword3 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_SWORD3 );
            $helmet = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_HELMET );
            $leggings = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_LEGGINGS );
            $boots = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_BOOTS );
            $chestplate1 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_CHESTPLATE1);
            $chestplate2 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_CHESTPLATE2);
            $chestplate3 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_CHESTPLATE3);
            $sandstone = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_SANDSTONE);
            $endstone = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_ENDSTONE);
            $iron = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_IRON);
            $glowstone = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_GLOWSTONE);
            $pickaxe1 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_PICKAXE1);
            $pickaxe2 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_PICKAXE2);
            $pickaxe3 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_PICKAXE3);
            $chest = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_CHEST);
            $cobweb = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_COBWEB);
            $glass = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_GLASS);
            $bow1 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_BOW1);
            $bow2 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_BOW2);
            $bow3 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_BOW3);
            $arrow = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_ARROW);
            $apple = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_APPLE);
            $porkchop = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_PORKCHOP);
            $cake = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_CAKE);
            $strenght = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_STRENGHT);
            $stick = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_YELLOW_STICK);
            if($player->getGamemode() == 0){
            if (round ( $blockTouched->x ) == round ( $sword1->x ) && round ( $blockTouched->y ) == round ( $sword1->y ) && round ( $blockTouched->z ) == round ( $sword1->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){                    
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(283));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $sword3->x ) && round ( $blockTouched->y ) == round ( $sword3->y ) && round ( $blockTouched->z ) == round ( $sword3->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 266){
                        if ($item->getCount() >= 5) {
				$item->setCount($item->getCount() - 5);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(267));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $helmet->x ) && round ( $blockTouched->y ) == round ( $helmet->y ) && round ( $blockTouched->z ) == round ( $helmet->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(298));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $leggings->x ) && round ( $blockTouched->y ) == round ( $leggings->y ) && round ( $blockTouched->z ) == round ( $leggings->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(300));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $boots->x ) && round ( $blockTouched->y ) == round ( $boots->y ) && round ( $blockTouched->z ) == round ( $boots->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(301));
                                break;
                        }
                }
        }
        } 
        if (round ( $blockTouched->x ) == round ( $chestplate1->x ) && round ( $blockTouched->y ) == round ($chestplate1->y ) && round ( $blockTouched->z ) == round ( $chestplate1->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(303));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $chestplate2->x ) && round ( $blockTouched->y ) == round ( $chestplate2->y ) && round ( $blockTouched->z ) == round ( $chestplate2->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 3) {
				$item->setCount($item->getCount() - 3);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(307));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $sandstone->x ) && round ( $blockTouched->y ) == round ( $sandstone->y ) && round ( $blockTouched->z ) == round ( $sandstone->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(24, 2, 2));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $endstone->x ) && round ( $blockTouched->y ) == round ( $endstone->y ) && round ( $blockTouched->z ) == round ( $endstone->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 7) {
				$item->setCount($item->getCount() - 7);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(121));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $iron->x ) && round ( $blockTouched->y ) == round ( $iron->y ) && round ( $blockTouched->z ) == round ( $iron->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(42));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $glowstone->x ) && round ( $blockTouched->y ) == round ( $glowstone->y ) && round ( $blockTouched->z ) == round ( $glowstone->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 15) {
				$item->setCount($item->getCount() - 15);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(89, 0, 4));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $pickaxe1->x ) && round ( $blockTouched->y ) == round ( $pickaxe1->y ) && round ( $blockTouched->z ) == round ( $pickaxe1->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 4) {
				$item->setCount($item->getCount() - 4);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(270));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $pickaxe2->x ) && round ( $blockTouched->y ) == round ( $pickaxe2->y ) && round ( $blockTouched->z ) == round ( $pickaxe2->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 2) {
				$item->setCount($item->getCount() - 2);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(274));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $pickaxe3->x ) && round ( $blockTouched->y ) == round ( $pickaxe3->y ) && round ( $blockTouched->z ) == round ( $pickaxe3->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 266){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(257));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $chest->x ) && round ( $blockTouched->y ) == round ( $chest->y ) && round ( $blockTouched->z ) == round ( $chest->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(54));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $glass->x ) && round ( $blockTouched->y ) == round ( $glass->y ) && round ( $blockTouched->z ) == round ( $glass->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 4) {
				$item->setCount($item->getCount() - 4);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(20));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $bow1->x ) && round ( $blockTouched->y ) == round ( $bow1->y ) && round ( $blockTouched->z ) == round ( $bow1->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 266){
                        if ($item->getCount() >= 3) {
				$item->setCount($item->getCount() - 3);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(261));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $arrow->x ) && round ( $blockTouched->y ) == round ( $arrow->y ) && round ( $blockTouched->z ) == round ( $arrow->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 266){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(262));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $apple->x ) && round ( $blockTouched->y ) == round ( $apple->y ) && round ( $blockTouched->z ) == round ( $apple->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(260));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $porkchop->x ) && round ( $blockTouched->y ) == round ( $porkchop->y ) && round ( $blockTouched->z ) == round ( $porkchop->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 3) {
				$item->setCount($item->getCount() - 3);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(320));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $cake->x ) && round ( $blockTouched->y ) == round ( $cake->y ) && round ( $blockTouched->z ) == round ( $cake->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(354));
                                break;
                        }
                }
                }
        }
        if (round ( $blockTouched->x ) == round ( $strenght->x ) && round ( $blockTouched->y ) == round ( $strenght->y ) && round ( $blockTouched->z ) == round ( $strenght->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 266){
                        if ($item->getCount() >= 8) {
				$item->setCount($item->getCount() - 8);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(325));
                                break;
                        }
                }
                }
        }
        if (round ( $blockTouched->x ) == round ( $stick->x ) && round ( $blockTouched->y ) == round ( $stick->y ) && round ( $blockTouched->z ) == round ( $stick->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 8) {
				$item->setCount($item->getCount() - 8);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(280));
                                break;
                        }
                }
                }
        }
            $bsword1 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_SWORD1);
            $Bsword2 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_SWORD2 );
            $Bsword3 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_SWORD3 );
            $Bhelmet = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_HELMET );
            $Bleggings = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_LEGGINGS );
            $Bboots = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_BOOTS );
            $Bchestplate1 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_CHESTPLATE1);
            $Bchestplate2 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_CHESTPLATE2);
            $Bchestplate3 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_CHESTPLATE3);
            $Bsandstone = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_SANDSTONE);
            $Bendstone = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_ENDSTONE);
            $Biron = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_IRON);
            $Bglowstone = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_GLOWSTONE);
            $Bpickaxe1 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_PICKAXE1);
            $Bpickaxe2 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_PICKAXE2);
            $Bpickaxe3 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_PICKAXE3);
            $Bchest = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_CHEST);
            $Bcobweb = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_COBWEB);
            $Bglass = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_GLASS);
            $Bbow1 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_BOW1);
            $Bbow2 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_BOW2);
            $Bbow3 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_BOW3);
            $Barrow = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_ARROW);
            $Bapple = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_APPLE);
            $Bporkchop = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_PORKCHOP);
            $Bcake = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_BLUE_CAKE);
            $Bstrenght = $this->getSetup()->getSignPos(CTFSetup::SHOP_BLUE_STRENGHT);
            $Bstick = $this->getSetup()->getSignPos(CTFSetup::SHOP_BLUE_STICK);
        
            if (round ( $blockTouched->x ) == round ( $bsword1->x ) && round ( $blockTouched->y ) == round ( $bsword1->y ) && round ( $blockTouched->z ) == round ( $bsword1->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(283));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $Bsword3->x ) && round ( $blockTouched->y ) == round ( $Bsword3->y ) && round ( $blockTouched->z ) == round ( $Bsword3->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 266){
                        if ($item->getCount() >= 5) {
				$item->setCount($item->getCount() - 5);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(267));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $Bhelmet->x ) && round ( $blockTouched->y ) == round ( $Bhelmet->y ) && round ( $blockTouched->z ) == round ( $Bhelmet->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(298));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $Bleggings->x ) && round ( $blockTouched->y ) == round ( $Bleggings->y ) && round ( $blockTouched->z ) == round ( $Bleggings->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(300));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $Bboots->x ) && round ( $blockTouched->y ) == round ( $Bboots->y ) && round ( $blockTouched->z ) == round ( $Bboots->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(301));
                                break;
                        }
                }
        }
        } 
        if (round ( $blockTouched->x ) == round ( $Bchestplate1->x ) && round ( $blockTouched->y ) == round ($Bchestplate1->y ) && round ( $blockTouched->z ) == round ( $Bchestplate1->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(303));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $Bchestplate2->x ) && round ( $blockTouched->y ) == round ( $Bchestplate2->y ) && round ( $blockTouched->z ) == round ( $Bchestplate2->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 3) {
				$item->setCount($item->getCount() - 3);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(307));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $Bsandstone->x ) && round ( $blockTouched->y ) == round ( $Bsandstone->y ) && round ( $blockTouched->z ) == round ( $Bsandstone->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(24, 2, 2));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $Bendstone->x ) && round ( $blockTouched->y ) == round ( $Bendstone->y ) && round ( $blockTouched->z ) == round ( $Bendstone->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 7) {
				$item->setCount($item->getCount() - 7);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(121));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $Biron->x ) && round ( $blockTouched->y ) == round ( $Biron->y ) && round ( $blockTouched->z ) == round ( $Biron->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(42));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $Bglowstone->x ) && round ( $blockTouched->y ) == round ( $Bglowstone->y ) && round ( $blockTouched->z ) == round ( $Bglowstone->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 15) {
				$item->setCount($item->getCount() - 15);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(89, 0, 4));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $Bpickaxe1->x ) && round ( $blockTouched->y ) == round ( $Bpickaxe1->y ) && round ( $blockTouched->z ) == round ( $Bpickaxe1->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 4) {
				$item->setCount($item->getCount() - 4);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(270));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $Bpickaxe2->x ) && round ( $blockTouched->y ) == round ( $Bpickaxe2->y ) && round ( $blockTouched->z ) == round ( $Bpickaxe2->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 2) {
				$item->setCount($item->getCount() - 2);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(274));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $Bpickaxe3->x ) && round ( $blockTouched->y ) == round ( $Bpickaxe3->y ) && round ( $blockTouched->z ) == round ( $Bpickaxe3->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 266){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(257));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $Bchest->x ) && round ( $blockTouched->y ) == round ( $Bchest->y ) && round ( $blockTouched->z ) == round ( $Bchest->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(54));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $Bglass->x ) && round ( $blockTouched->y ) == round ( $Bglass->y ) && round ( $blockTouched->z ) == round ( $Bglass->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 4) {
				$item->setCount($item->getCount() - 4);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(20));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $Bbow1->x ) && round ( $blockTouched->y ) == round ( $Bbow1->y ) && round ( $blockTouched->z ) == round ( $Bbow1->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 266){
                        if ($item->getCount() >= 3) {
				$item->setCount($item->getCount() - 3);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(261));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $Barrow->x ) && round ( $blockTouched->y ) == round ( $Barrow->y ) && round ( $blockTouched->z ) == round ( $Barrow->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 266){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(262));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $Bapple->x ) && round ( $blockTouched->y ) == round ( $Bapple->y ) && round ( $blockTouched->z ) == round ( $Bapple->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(260));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $Bporkchop->x ) && round ( $blockTouched->y ) == round ( $Bporkchop->y ) && round ( $blockTouched->z ) == round ( $Bporkchop->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 3) {
				$item->setCount($item->getCount() - 3);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(320));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $Bcake->x ) && round ( $blockTouched->y ) == round ( $Bcake->y ) && round ( $blockTouched->z ) == round ( $Bcake->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(354));
                                break;
                        }
                }
                }
        } 
        if (round ( $blockTouched->x ) == round ( $Bstrenght->x ) && round ( $blockTouched->y ) == round ( $Bstrenght->y ) && round ( $blockTouched->z ) == round ( $Bstrenght->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 266){
                        if ($item->getCount() >= 8) {
				$item->setCount($item->getCount() - 8);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(325));
                                break;
                        }
                }
                }
        }
        if (round ( $blockTouched->x ) == round ( $Bstick->x ) && round ( $blockTouched->y ) == round ( $Bstick->y ) && round ( $blockTouched->z ) == round ( $Bstick->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 8) {
				$item->setCount($item->getCount() - 8);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(280));
                                break;
                        }
                }
                }
        }
            
            $rsword1 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_SWORD1);
            $rsword2 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_SWORD2 );
            $rsword3 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_SWORD3 );
            $rhelmet = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_HELMET );
            $rleggings = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_LEGGINGS );
            $rboots = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_BOOTS );
            $rchestplate1 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_CHESTPLATE1);
            $rchestplate2 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_CHESTPLATE2);
            $rchestplate3 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_CHESTPLATE3);
            $rsandstone = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_SANDSTONE);
            $rendstone = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_ENDSTONE);
            $riron = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_IRON);
            $rglowstone = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_GLOWSTONE);
            $rpickaxe1 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_PICKAXE1);
            $rpickaxe2 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_PICKAXE2);
            $rpickaxe3 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_PICKAXE3);
            $rchest = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_CHEST);
            $rcobweb = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_COBWEB);
            $rglass = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_GLASS);
            $rbow1 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_BOW1);
            $rbow2 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_BOW2);
            $rbow3 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_BOW3);
            $rarrow = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_ARROW);
            $rapple = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_APPLE);
            $rporkchop = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_PORKCHOP);
            $rcake = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_RED_CAKE);
            $rstrenght = $this->getSetup()->getSignPos(CTFSetup::SHOP_RED_STRENGHT);
            $rstick = $this->getSetup()->getSignPos(CTFSetup::SHOP_RED_STICK);
            
            if (round ( $blockTouched->x ) == round ( $rsword1->x ) && round ( $blockTouched->y ) == round ( $rsword1->y ) && round ( $blockTouched->z ) == round ( $rsword1->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){                    
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(283));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $rsword3->x ) && round ( $blockTouched->y ) == round ( $rsword3->y ) && round ( $blockTouched->z ) == round ( $rsword3->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 266){
                        if ($item->getCount() >= 5) {
				$item->setCount($item->getCount() - 5);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(267));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $rhelmet->x ) && round ( $blockTouched->y ) == round ( $rhelmet->y ) && round ( $blockTouched->z ) == round ( $rhelmet->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(298));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $rleggings->x ) && round ( $blockTouched->y ) == round ( $rleggings->y ) && round ( $blockTouched->z ) == round ( $rleggings->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(300));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $rboots->x ) && round ( $blockTouched->y ) == round ( $rboots->y ) && round ( $blockTouched->z ) == round ( $rboots->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(301));
                                break;
                        }
                }
        }
        } 
        if (round ( $blockTouched->x ) == round ( $rchestplate1->x ) && round ( $blockTouched->y ) == round ($rchestplate1->y ) && round ( $blockTouched->z ) == round ( $rchestplate1->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(303));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $rchestplate2->x ) && round ( $blockTouched->y ) == round ( $rchestplate2->y ) && round ( $blockTouched->z ) == round ( $rchestplate2->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 3) {
				$item->setCount($item->getCount() - 3);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(307));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $rsandstone->x ) && round ( $blockTouched->y ) == round ( $rsandstone->y ) && round ( $blockTouched->z ) == round ( $rsandstone->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(24, 2, 2));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $rendstone->x ) && round ( $blockTouched->y ) == round ( $rendstone->y ) && round ( $blockTouched->z ) == round ( $rendstone->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 7) {
				$item->setCount($item->getCount() - 7);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(121));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $riron->x ) && round ( $blockTouched->y ) == round ( $riron->y ) && round ( $blockTouched->z ) == round ( $riron->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(42));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $rglowstone->x ) && round ( $blockTouched->y ) == round ( $rglowstone->y ) && round ( $blockTouched->z ) == round ( $rglowstone->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 15) {
				$item->setCount($item->getCount() - 15);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(89, 0, 4));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $rpickaxe1->x ) && round ( $blockTouched->y ) == round ( $rpickaxe1->y ) && round ( $blockTouched->z ) == round ( $rpickaxe1->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 4) {
				$item->setCount($item->getCount() - 4);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(270));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $rpickaxe2->x ) && round ( $blockTouched->y ) == round ( $rpickaxe2->y ) && round ( $blockTouched->z ) == round ( $rpickaxe2->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 2) {
				$item->setCount($item->getCount() - 2);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(274));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $rpickaxe3->x ) && round ( $blockTouched->y ) == round ( $rpickaxe3->y ) && round ( $blockTouched->z ) == round ( $rpickaxe3->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 266){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(257));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $rchest->x ) && round ( $blockTouched->y ) == round ( $rchest->y ) && round ( $blockTouched->z ) == round ( $rchest->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(54));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $rglass->x ) && round ( $blockTouched->y ) == round ( $rglass->y ) && round ( $blockTouched->z ) == round ( $rglass->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 4) {
				$item->setCount($item->getCount() - 4);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(20));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $rbow1->x ) && round ( $blockTouched->y ) == round ( $rbow1->y ) && round ( $blockTouched->z ) == round ( $rbow1->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 266){
                        if ($item->getCount() >= 3) {
				$item->setCount($item->getCount() - 3);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(261));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $rarrow->x ) && round ( $blockTouched->y ) == round ( $rarrow->y ) && round ( $blockTouched->z ) == round ( $rarrow->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 266){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(262));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $rapple->x ) && round ( $blockTouched->y ) == round ( $rapple->y ) && round ( $blockTouched->z ) == round ( $rapple->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(260));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $rporkchop->x ) && round ( $blockTouched->y ) == round ( $rporkchop->y ) && round ( $blockTouched->z ) == round ( $rporkchop->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 3) {
				$item->setCount($item->getCount() - 3);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(320));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $rcake->x ) && round ( $blockTouched->y ) == round ( $rcake->y ) && round ( $blockTouched->z ) == round ( $rcake->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(354));
                                break;
                        }
                }
                }
        }
        if (round ( $blockTouched->x ) == round ( $rstrenght->x ) && round ( $blockTouched->y ) == round ( $rstrenght->y ) && round ( $blockTouched->z ) == round ( $rstrenght->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 266){
                        if ($item->getCount() >= 8) {
				$item->setCount($item->getCount() - 8);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(325));
                                break;
                        }
                }
                }
        }
        if (round ( $blockTouched->x ) == round ( $rstick->x ) && round ( $blockTouched->y ) == round ( $rstick->y ) && round ( $blockTouched->z ) == round ( $rstick->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 8) {
				$item->setCount($item->getCount() - 8);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(280));
                                break;
                        }
                }
                }
        }
            $gsword1 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_SWORD1);
            $gsword2 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_SWORD2 );
            $gsword3 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_SWORD3 );
            $ghelmet = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_HELMET );
            $gleggings = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_LEGGINGS );
            $gboots = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_BOOTS );
            $gchestplate1 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_CHESTPLATE1);
            $gchestplate2 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_CHESTPLATE2);
            $gchestplate3 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_CHESTPLATE3);
            $gsandstone = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_SANDSTONE);
            $gendstone = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_ENDSTONE);
            $giron = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_IRON);
            $gglowstone = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_GLOWSTONE);
            $gpickaxe1 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_PICKAXE1);
            $gpickaxe2 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_PICKAXE2);
            $gpickaxe3 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_PICKAXE3);
            $gchest = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_CHEST);
            $gcobweb = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_COBWEB);
            $gglass = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_GLASS);
            $gbow1 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_BOW1);
            $gbow2 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_BOW2);
            $gbow3 = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_BOW3);
            $garrow = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_ARROW);
            $gapple = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_APPLE);
            $gporkchop = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_PORKCHOP);
            $gcake = $this->getSetup ()->getSignPos ( CTFSetup::SHOP_GREEN_CAKE);
            $gstrenght = $this->getSetup()->getSignPos(CTFSetup::SHOP_GREEN_STRENGHT);
            $gstick = $this->getSetup()->getSignPos(CTFSetup::SHOP_GREEN_STICK);
            
            if (round ( $blockTouched->x ) == round ( $gsword1->x ) && round ( $blockTouched->y ) == round ( $gsword1->y ) && round ( $blockTouched->z ) == round ( $gsword1->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){                    
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(283));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $gsword3->x ) && round ( $blockTouched->y ) == round ( $gsword3->y ) && round ( $blockTouched->z ) == round ( $gsword3->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 266){
                        if ($item->getCount() >= 5) {
				$item->setCount($item->getCount() - 5);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(267));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $ghelmet->x ) && round ( $blockTouched->y ) == round ( $ghelmet->y ) && round ( $blockTouched->z ) == round ( $ghelmet->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(298));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $gleggings->x ) && round ( $blockTouched->y ) == round ( $gleggings->y ) && round ( $blockTouched->z ) == round ( $gleggings->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(300));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $gboots->x ) && round ( $blockTouched->y ) == round ( $gboots->y ) && round ( $blockTouched->z ) == round ( $gboots->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(301));
                                break;
                        }
                }
        }
        } 
        if (round ( $blockTouched->x ) == round ( $gchestplate1->x ) && round ( $blockTouched->y ) == round ($gchestplate1->y ) && round ( $blockTouched->z ) == round ( $gchestplate1->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(303));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $gchestplate2->x ) && round ( $blockTouched->y ) == round ( $gchestplate2->y ) && round ( $blockTouched->z ) == round ( $gchestplate2->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 3) {
				$item->setCount($item->getCount() - 3);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(307));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $gsandstone->x ) && round ( $blockTouched->y ) == round ( $gsandstone->y ) && round ( $blockTouched->z ) == round ( $gsandstone->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(24, 2, 2));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $gendstone->x ) && round ( $blockTouched->y ) == round ( $gendstone->y ) && round ( $blockTouched->z ) == round ( $gendstone->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 7) {
				$item->setCount($item->getCount() - 7);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(121));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $giron->x ) && round ( $blockTouched->y ) == round ( $giron->y ) && round ( $blockTouched->z ) == round ( $giron->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(42));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $gglowstone->x ) && round ( $blockTouched->y ) == round ( $gglowstone->y ) && round ( $blockTouched->z ) == round ( $gglowstone->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 15) {
				$item->setCount($item->getCount() - 15);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(89, 0, 4));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $gpickaxe1->x ) && round ( $blockTouched->y ) == round ( $gpickaxe1->y ) && round ( $blockTouched->z ) == round ( $gpickaxe1->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 4) {
				$item->setCount($item->getCount() - 4);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(270));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $gpickaxe2->x ) && round ( $blockTouched->y ) == round ( $gpickaxe2->y ) && round ( $blockTouched->z ) == round ( $gpickaxe2->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 2) {
				$item->setCount($item->getCount() - 2);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(274));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $gpickaxe3->x ) && round ( $blockTouched->y ) == round ( $gpickaxe3->y ) && round ( $blockTouched->z ) == round ( $gpickaxe3->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 266){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(257));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $gchest->x ) && round ( $blockTouched->y ) == round ( $gchest->y ) && round ( $blockTouched->z ) == round ( $gchest->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(54));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $gglass->x ) && round ( $blockTouched->y ) == round ( $gglass->y ) && round ( $blockTouched->z ) == round ( $gglass->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 4) {
				$item->setCount($item->getCount() - 4);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(20));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $gbow1->x ) && round ( $blockTouched->y ) == round ( $gbow1->y ) && round ( $blockTouched->z ) == round ( $gbow1->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 266){
                        if ($item->getCount() >= 3) {
				$item->setCount($item->getCount() - 3);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(261));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $garrow->x ) && round ( $blockTouched->y ) == round ( $garrow->y ) && round ( $blockTouched->z ) == round ( $garrow->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 266){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(262));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $gapple->x ) && round ( $blockTouched->y ) == round ( $gapple->y ) && round ( $blockTouched->z ) == round ( $gapple->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(260));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $gporkchop->x ) && round ( $blockTouched->y ) == round ( $gporkchop->y ) && round ( $blockTouched->z ) == round ( $gporkchop->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 3) {
				$item->setCount($item->getCount() - 3);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(320));
                                break;
                        }
                }
        }
        }
        if (round ( $blockTouched->x ) == round ( $gcake->x ) && round ( $blockTouched->y ) == round ( $gcake->y ) && round ( $blockTouched->z ) == round ( $gcake->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 265){
                        if ($item->getCount() >= 1) {
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(354));
                                break;
                        }
                }
                }
        }
        if (round ( $blockTouched->x ) == round ( $gstrenght->x ) && round ( $blockTouched->y ) == round ( $gstrenght->y ) && round ( $blockTouched->z ) == round ( $gstrenght->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 266){
                        if ($item->getCount() >= 8) {
				$item->setCount($item->getCount() - 8);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(325));
                                break;
                        }
                }
                }
        }
        if (round ( $blockTouched->x ) == round ( $gstick->x ) && round ( $blockTouched->y ) == round ( $gstick->y ) && round ( $blockTouched->z ) == round ( $gstick->z )) {
                foreach ($player->getInventory()->getContents() as $slot => &$item){
                    if ($item->getId() == 336){
                        if ($item->getCount() >= 8) {
				$item->setCount($item->getCount() - 8);
				$player->getInventory()->setItem($slot,clone $item);
                                $player->getInventory()->addItem(Item::get(280));
                                break;
                        }
                }
                }
        }
            
                        }
        }
        
        }
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
}

