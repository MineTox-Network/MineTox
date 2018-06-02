<?php

namespace BedWars\Arena;

use pocketmine\item\Item;
use pocketmine\Player;

class Shop
{

    public $shop;

    private $trading;

    public function __construct(ArenaTrading $t)
    {
        $this->trading = $t;

        $this->shop = [Item::SANDSTONE => [new Item(Item::SANDSTONE, 2, 2), 2, 1],
                       Item:: LEATHER_CAP => [new Item(Item::LEATHER_CAP), 1, 1],
                       Item::LEATHER_PANTS => [new Item(Item::LEATHER_PANTS), 1, 1],
                       Item::LEATHER_BOOTS => [new Item(Item::LEATHER_BOOTS), 1, 1],
                       Item::END_STONE => [new Item(Item::END_STONE), 7, 1],
                       Item::STEAK => [new Item(Item::STEAK), 2, 1],
                       Item::WOODEN_PICKAXE => [new Item(Item::WOODEN_PICKAXE), 4, 1],
                       Item::DIAMOND_BLOCK => [new Item(Item::DIAMOND_BLOCK), 3, 2],
                       Item::GOLDEN_SWORD => [new Item(Item::GOLDEN_SWORD), 3, 2],
                       Item::IRON_PICKAXE => [new Item(Item::IRON_PICKAXE), 2, 2],
                       Item::CHEST => [new Item(Item::CHEST), 1, 2],
                       Item::CHAIN_CHESTPLATE => [new Item(Item::CHAIN_CHESTPLATE), 1, 2],
                       Item::ARROW => [new Item(Item::ARROW, 3), 1, 2],
                       Item::IRON_SWORD => [new Item(Item::IRON_SWORD), 5, 3],
                       Item::BOW => [new Item(Item::BOW), 4, 3],
                       Item::DIAMOND_PICKAXE => [new Item(Item::DIAMOND_PICKAXE), 1, 3]];

    }

    public function getPrice($itemId)
    {
        if(isset($this->shop[$itemId]))
        {
            return new Item(self::getPaymentItem($this->shop[$itemId][2]), 0, $this->shop[$itemId][1]);
        }
        return false;
    }

    public function doTransaction($item, Player $player)
    {

    }

    public static function getPaymentItem($payment)
    {
        switch($payment)
        {
            case 1:
                return Item::BRICK;
                break;
            case 2:
                return Item::IRON_INGOT;
                break;
            case 3:
                return Item::GOLD_INGOT;
                break;
        }
        return false;
    }

    public static function isPaymentItem($itemId)
    {
        if($itemId === Item::BRICK or $itemId === Item::IRON_INGOT or $itemId === Item::GOLD_INGOT)
        {
            return true;
        }
        return false;
    }

} 