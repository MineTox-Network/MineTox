<?php

namespace Annihilation\Arena;

use Annihilation\Arena\Arena;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\inventory\PlayerInventory;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;

class KitManager implements Listener{
    
    public $plugin;
    private $kits;
    
    public function __construct(Arena $plugin){
        $this->plugin = $plugin;
        $this->kits = [new Kits\Archer($this->plugin), new Kits\Berserker($this->plugin), new Kits\Civilian($this->plugin), new Kits\Miner($this->plugin), new Kits\Operative($this->plugin), new Kits\Spy($this->plugin), new Kits\Scout($this->plugin), new Kits\Thor($this->plugin), new Kits\Warrior($this->plugin)];
    }
    
    public function onKitChange(Player $p, $kit){
        $this->plugin->players[strtolower($p->getName())]['kit'] = $kit;
        $p->sendPopup(TextFormat::GREEN.'Selected class '.TextFormat::BLUE."$kit");
    }
    
    public function giveKit(Player $p){
        switch($this->plugin->players[strtolower($p->getName())]['kit']){
            case 'civilian':
                $this->kits[2]->give($p);
                break;
            case 'miner':
                $this->kits[3]->give($p);
                break;
            case 'warrior':
                $this->kits[8]->give($p);
                break;
            case 'scout':
                $this->kits[6]->give($p);
                break;
            case 'berserker':
                $this->kits[1]->give($p);
                break;
            case 'archer':
                $this->kits[0]->give($p);
                break;
            case 'spy':
                $this->kits[5]->give($p);
                break;
            case 'operative':
                $this->kits[4]->give($p);
                break;
            case 'thor':
                $this->kits[7]->give($p);
                break;
            default:
                $this->kits[2]->give($p);
                break;
        }
        $p->getInventory()->setArmorContents([Item::get(298, 0, 1), Item::get(299, 1, 0), Item::get(300, 0, 1), Item::get(301, 0, 1)]);
    }
    
    public function registerKits(){
        foreach($this->kits as $kit){
            if(!$kit instanceof Kits\Civilian && !$kit instanceof Kits\Miner){
                $this->plugin->plugin->getServer()->getPluginManager()->registerEvents($kit, $this->plugin->plugin);
            }
            foreach($this->plugin->players as $p => $data){
                if($data['kit'] == $kit->name){
                    $kit->players[$p] = $this->plugin->plugin->getServer()->getPlayer($p);
                }
            }
        }
    }
    
    public function addKitWindow(Player $p){
        $inv = $p->getInventory();
        $inv->setItem(0, Item::get(58, 0, 1)); //civilian
        $inv->setItem(1, Item::get(274, 0, 1)); //miner
        $inv->setItem(2, Item::get(272, 0, 1)); //warrior
        $inv->setItem(3, Item::get(346, 0, 1)); //scout
        $inv->setItem(4, Item::get(303, 0, 1)); //berserker
        $inv->setItem(5, Item::get(261, 0, 1)); //archer
        $inv->setItem(6, Item::get(325, 1, 1)); //spy - invisible potion
        $inv->setItem(7, Item::get(246, 0, 1)); //operative - soulsand
        $inv->setItem(8, Item::get(286, 0, 1)); //thor
        $inv->setHotbarSlotIndex(0, 0);
        $inv->setHotbarSlotIndex(0, 1);
        $inv->setHotbarSlotIndex(0, 2);
        $inv->setHotbarSlotIndex(0, 3);
        $inv->setHotbarSlotIndex(0, 4);
        $inv->setHotbarSlotIndex(0, 5);
        $inv->setHotbarSlotIndex(0, 6);
        $inv->setHotbarSlotIndex(0, 7);
        $inv->setHotbarSlotIndex(0, 8);
    }
    
    /*public function onItemTrans(InventoryTransactionEvent $e){
        if($e->getTransaction() instanceof Transaction){
        $inv = $e->getTransaction()->getInventory();
        if($inv instanceof PlayerInventory){
            $p = $inv->getHolder();
            if($this->plugin->arenateams->getPlayerTeam($p) !== 0){
                return;
            }
            $e->setCancelled();
        }
        }
    }*/
    
    public function itemHeld(PlayerItemHeldEvent $e){
        $p = $e->getPlayer();
        if($this->plugin->arenateams->getPlayerTeam($p) !== 0 && $this->plugin->arenaTeams->getPlayerTeam($p) !== false){
            return;
        }
        $e->setCancelled();
        switch($e->getInventorySlot()){
                case 0:
                    $this->onKitChange($p, 'civilian');
                    break;
                case 1:
                    $this->onKitChange($p, 'miner');
                    break;
                case 2:
                    $this->onKitChange($p, 'warrior');
                    break;
                case 3:
                    $this->onKitChange($p, 'scout');
                    break;
                case 4:
                    $this->onKitChange($p, 'berserker');
                    break;
                case 5:
                    $this->onKitChange($p, 'archer');
                    break;
                case 6:
                    $this->onKitChange($p, 'spy');
                    break;
                case 7:
                    $this->onKitChange($p, 'operative');
                    break;
                case 8:
                    $this->onKitChange($p, 'thor');
                    break;
                default:
                    return;
            }
    }
}