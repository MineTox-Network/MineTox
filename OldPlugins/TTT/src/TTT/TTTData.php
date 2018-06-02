<?php

namespace TTT;

use pocketmine\item\Item;

class TTTData
{

    //Roles
    public static $innocent = 0;
    public static $detective = 1;
    public static $traitor = 2;

    //Preferences
    public static $min_players = 6;
    public static $max_traitors = 2;
    public static $max_detectives = 1;

    //Random chest items
    static function getRandomChestItem()
    {
        $items =
        [
            Item::WOODEN_SWORD,
            Item::STONE_SWORD,
            Item::BOW
        ];
        return $items[array_rand($items)];
    }

} 