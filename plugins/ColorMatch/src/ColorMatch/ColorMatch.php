<?php

namespace ColorMatch;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\Player;
use ColorMatch\Arena\Arena;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\level\Position;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerKickEvent;

class ColorMatch extends PluginBase implements Listener{

    public $cfg;
    public $msg;
    public $arenas = [];
    public $ins = [];
    public $selectors = [];
    public $inv = [];
    public $setters = [];
    
    public function onEnable(){
        $this->initConfig();
        $this->checkArenas();
        $this->getLogger()->info(TextFormat::GREEN."ColorMatch enabled");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        if(!$this->getServer()->isLevelGenerated($this->cfg->getNested('lobby.world'))){
            $this->getServer()->generateLevel($this->cfg->getNested('lobby.world'));
        }
    }
    
    public function onDisable(){
        $this->getLogger()->info(TextFormat::RED."ColorMatch disabled");
    }
    
    public function setArenasData(Config $arena, $name){
        $this->arenas[$name] = $arena->getAll();
        $this->arenas[$name]['enable'] = true;
        $game = new Arena($name, $this);
        $game->enableScheduler();
        $this->ins[$name] = $game;
        $this->getServer()->getPluginManager()->registerEvents($game, $this);
    }
    
    public function initConfig(){
        if(!file_exists($this->getDataFolder())){
            @mkdir($this->getDataFolder());
        }
        if(!is_file($this->getDataFolder()."config.yml")){
            $this->saveResource("config.yml");
        }
        $this->cfg = new Config($this->getDataFolder()."config.yml", Config::YAML);
        if(!file_exists($this->getDataFolder()."arenas/")){
            @mkdir($this->getDataFolder()."arenas/");
            $this->saveResource("arenas/default.yml");
        }
        if(!file_exists($this->getDataFolder()."languages/")){
            @mkdir($this->getDataFolder()."languages/");
        }
        if(!is_file($this->getDataFolder()."languages/English.yml")){
                $this->saveResource("languages/English.yml");
        }
        if(!is_file($this->getDataFolder()."languages/Czech.yml")){
                $this->saveResource("languages/Czech.yml");
        }
        if(!is_file($this->getDataFolder()."languages/{$this->cfg->get('Language')}.yml")){
            $this->msg = new Config($this->getDataFolder()."languages/English.yml", Config::YAML);
        }
        else{
            $this->msg = new Config($this->getDataFolder()."languages/{$this->cfg->get('Language')}.yml", Config::YAML);
        }
    }
    
    public function checkArenas(){
        $this->getLogger()->info("checking arena files...");
        foreach(glob($this->getDataFolder()."arenas/*.yml") as $file){
            $arena = new Config($file, Config::YAML);
            if(strtolower($arena->get("enabled")) === "false"){
                $this->arenas[basename($file, ".yml")] = $arena->getAll();
                $this->arenas[basename($file, ".yml")]['enable'] = false;
            }
            else{
                if($this->checkFile($arena)){
                    $fname = basename($file);
                    $this->setArenasData($arena, basename($file, ".yml"));
                    $this->getLogger()->info("$fname - ".TextFormat::GREEN."checking sucessful");
                }
                else{
                    $this->arenas[basename($file, ".yml")] = $arena->getAll();
                    $this->arenas[basename($file, ".yml")]['enable'] = false;
                    //$this->setArenasData($arena, basename($file, ".yml"), false);
                    $fname = basename($file, ".yml");
                    $this->getLogger()->error("Arena \"$fname\" is not set properly");
                }
            }
        }
    }
    
    public function checkFile(Config $arena){
        if(!(is_numeric($arena->getNested("signs.join_sign_x")) && is_numeric($arena->getNested("signs.join_sign_y")) && is_numeric($arena->getNested("signs.join_sign_z")) && is_string($arena->getNested("signs.join_sign_world")) && is_string($arena->getNested("signs.status_line_1")) && is_string($arena->getNested("signs.status_line_2")) && is_string($arena->getNested("signs.status_line_3")) && is_string($arena->getNested("signs.status_line_4")) && is_numeric($arena->getNested("signs.return_sign_x")) && is_numeric($arena->getNested("signs.return_sign_y")) && is_numeric($arena->getNested("signs.return_sign_z")) && is_string($arena->getNested("arena.arena_world")) && is_numeric($arena->getNested("arena.join_position_x")) && is_numeric($arena->getNested("arena.join_position_y")) && is_numeric($arena->getNested("arena.join_position_z")) && is_numeric($arena->getNested("arena.lobby_position_x")) && is_numeric($arena->getNested("arena.lobby_position_y")) && is_numeric($arena->getNested("arena.lobby_position_z")) && is_numeric($arena->getNested("arena.first_corner_x")) && is_numeric($arena->getNested("arena.first_corner_z")) && is_numeric($arena->getNested("arena.second_corner_x")) && is_numeric($arena->getNested("arena.second_corner_z")) && is_numeric($arena->getNested("arena.spec_spawn_x")) && is_numeric($arena->getNested("arena.spec_spawn_y")) && is_numeric($arena->getNested("arena.spec_spawn_z")) && is_numeric($arena->getNested("arena.leave_position_x")) && is_numeric($arena->getNested("arena.leave_position_y")) && is_numeric($arena->getNested("arena.leave_position_z")) && is_string($arena->getNested("arena.leave_position_world")) && is_numeric($arena->getNested("arena.max_game_time")) && is_numeric($arena->getNested("arena.max_players")) && is_numeric($arena->getNested("arena.min_players")) && is_numeric($arena->getNested("arena.starting_time")) && is_numeric($arena->getNested("arena.color_wait_time")) && is_numeric($arena->getNested("arena.floor_y")) && is_string($arena->getNested("arena.finish_msg_levels")))){
            return false;
        }
        if(!((strtolower($arena->get("type")) == "furious" || strtolower($arena->get("type")) == "stoned" || strtolower($arena->get("type")) == "classic") && (strtolower($arena->get("material")) == "wool" || strtolower($arena->get("material")) == "clay") && (strtolower($arena->getNested("signs.enable_status")) == "true" || strtolower($arena->getNested("signs.enable_status")) == "false") && (strtolower($arena->getNested("arena.spectator_mode")) == "true" || strtolower($arena->getNested("arena.spectator_mode")) == "false") && (strtolower($arena->getNested("arena.time")) == "true" || strtolower($arena->getNested("arena.time")) == "day" || strtolower($arena->getNested("arena.time")) == "night" || is_numeric(strtolower($arena->getNested("arena.time")))) && (strtolower($arena->get("enabled")) == "true" || strtolower($arena->get("enabled")) == "false"))){
            return false;
        }
        return true;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
            if(strtolower($cmd->getName()) == "cm"){
                    if(isset($args[0])){
                        if($sender instanceof Player){
                        switch(strtolower($args[0])){
                            case "lobby":
                                if(!$sender->hasPermission('cm.command.lobby')){
                                    $sender->sendMessage($this->getMsg('has_not_permission'));
                                    break;
                                }
                                if($this->getPlayerArena($sender) !== false){
                                    $this->getPlayerArena($sender)->leaveArena($sender);
                                    break;
                                }
                                $sender->teleport(new Position($this->cfg->getNested('lobby.x'), $this->cfg->getNested('lobby.y'), $this->cfg->getNested('lobby.z'), $this->getServer()->getLevelByName($this->cfg->getNested('lobby.world'))));
                                $sender->sendMessage($this->getPrefix().$this->getMsg('send_to_main_world'));
                                break;
                            case "set":
                                if(!$sender->hasPermission('cm.command.set')){
                                    $sender->sendMessage($this->getMsg('has_not_permission'));
                                    break;
                                }
                                if(!isset($args[1]) || isset($args[2])){
                                    $sender->sendMessage($this->getPrefix().$this->getMsg('set_help'));
                                    break;
                                }
                                if(!$this->arenaExist($args[1])){
                                    $sender->sendMessage($this->getPrefix().$this->getMsg('arena_doesnt_exist'));
                                    break;
                                }
                                if($this->isArenaSet($args[1])){
                                    $a = $this->ins[$args[1]];
                                    if($a->game !== 0 || count(array_merge($a->ingamep, $a->lobbyp, $a->spec)) > 0){
                                        $sender->sendMessage($this->getPrefix().$this->getMsg('arena_running'));
                                        break;
                                    }
                                    $a->setup = true;
                                }
                                $this->setters[strtolower($sender->getName())]['arena'] = $args[1];
                                $sender->sendMessage($this->getPrefix().$this->getMsg('enable_setup_mode'));
                                break;
                            case "help":
                                if(!$sender->hasPermission("cm.command.help")){
                                    $sender->sendMessage($this->getMsg('has_not_permission'));
                                    break;
                                }
                                $msg = "§9--- §c§lColorMatch help§l§9 ---§r§f";
                                if($sender->hasPermission('cm.command.lobby')) $msg .= "\n- §2/cm lobby §l§5»§r§f Teleport to lobby";
                                if($sender->hasPermission('cm.command.leave')) $msg .= "\n- §2/cm leave §l§5»§r§f Leave the arena";
                                if($sender->hasPermission('cm.command.join')) $msg .= "\n- §2/cm join §b[arena name] §l§5»§r§f Join to the Arena";
                                if($sender->hasPermission('cm.command.start')) $msg .= "\n- §2/cm start §b[arena name] §l§5»§r§f Force start the arena";
                                if($sender->hasPermission('cm.command.stop')) $msg .= "\n- §2/cm stop §b[arena name] §l§5»§r§f Force stop the arena";
                                if($sender->hasPermission('cm.command.kick')) $msg .= "\n- §2/cm kick §b[arena name] [player name] [reason] §l§5»§r§f Kick player from arena";
                                if($sender->hasPermission('cm.command.set')) $msg .= "\n- §2/cm set §b[arena name] §l§5»§r§f Set or modify arena";
                                if($sender->hasPermission('cm.command.delete')) $msg .= "\n- §2/cm delete §b[arena name] §l§5»§r§f Delete existing arena";
                                if($sender->hasPermission('cm.command.create')) $msg .= "\n- §2/cm create §b[arena name] §l§5»§r§f Create new arena";
                                $sender->sendMessage($msg);
                                break;
                            case "create":
                                if(!$sender->hasPermission('cm.command.create')){
                                    $sender->sendMessage($this->getMsg ('has_not_permission'));
                                    break;
                                }
                                if(!isset($args[1]) || isset($args[2])){
                                    $sender->sendMessage($this->getPrefix().$this->getMsg('create_help'));
                                    break;
                                }
                                if($this->arenaExist($args[1])){
                                    $sender->sendMessage($this->getPrefix().$this->getMsg('arena_already_exist'));
                                    break;
                                }
                                $a = new Config($this->getDataFolder()."arenas/$args[1].yml", Config::YAML);
                                file_put_contents($this->getDataFolder()."arenas/$args[1].yml", $this->getResource('arenas/default.yml'));
                                $this->arenas[$args[1]] = $a->getAll();
                                $sender->sendMessage($this->getPrefix().$this->getMsg('arena_create'));
                                break;
                            case "delete":
                                if(!$sender->hasPermission('cm.command.delete')){
                                    $sender->sendMessage($this->getMsg ('has_not_permission'));
                                    break;
                                }
                                if(!isset($args[1]) || isset($args[2])){
                                    $sender->sendMessage($this->getPrefix().$this->getMsg('delete_help'));
                                    break;
                                }
                                if(!$this->arenaExist($args[1])){
                                    $sender->sendMessage($this->getPrefix().$this->getMsg('arena_doesnt_exist'));
                                    break;
                                }
                                unlink($this->getDataFolder()."arenas/$args[1].yml");
                                unset($this->arenas[$args[1]]);
                                $sender->sendMessage($this->getPrefix().$this->getMsg('arena_delete'));
                                break;
                            case "join":
                                if(!$sender->hasPermission('cm.command.join')){
                                    $sender->sendMessage($this->getMsg('has_not_permission'));
                                    break;
                                }
                                if(!isset($args[1]) || isset($args[2])){
                                    $sender->sendMessage($this->getPrefix().$this->getMsg('join_help'));
                                    break;
                                }
                                if(!$this->arenaExist($args[1])){
                                    $sender->sendMessage($this->getPrefix().$this->getMsg('arena_doesnt_exist'));
                                    break;
                                }
                                if($this->arenas[$args[1]]['enable'] === false){
                                    $sender->sendMessage($this->getPrefix().$this->getMsg('arena_doesnt_exist'));
                                    break;
                                }
                                $this->ins[$args[1]]->joinToArena($sender);
                                break;
                            case "leave":
                                if(!$sender->hasPermission('cm.command.leave')){
                                    $sender->sendMessage($this->getMsg ('has_not_permission'));
                                    break;
                                }
                                if(isset($args[1])){
                                    $sender->sendMessage($this->getPrefix().$this->getMsg('leave_help'));
                                    break;
                                }
                                if($this->getPlayerArena($sender) === false){
                                    $sender->sendMessage($this->getPrefix().$this->getMsg('use_cmd_in_game'));
                                    break;
                                }
                                $this->getPlayerArena($sender)->leaveArena($sender);
                                break;
                            case "start":
                                if(!$sender->hasPermission('cm.command.start')){
                                    $sender->sendMessage($this->plugin->getMsg('has_not_permission'));
                                    break;
                                }
                                if(!isset($args[1]) || isset($args[2])){
                                    $sender->sendMessage($this->getPrefix().$this->getMsg('start_help'));
                                    break;
                                }
                                if(!isset($this->ins[$args[1]])){
                                    $sender->sendMessage($this->getPrefix().$this->getMsg('arena_doesnt_exist'));
                                    break;
                                }
                                $this->ins[$args[1]]->startGame();
                                break;
                            case "stop":
                                if(!$sender->hasPermission('cm.command.start')){
                                    $sender->sendMessage($this->plugin->getMsg('has_not_permission'));
                                    break;
                                }
                                if(!isset($args[1]) || isset($args[2])){
                                    $sender->sendMessage($this->getPrefix().$this->getMsg('stop_help'));
                                    break;
                                }
                                if(!isset($this->ins[$args[1]])){
                                    $sender->sendMessage($this->getPrefix().$this->getMsg('arena_doesnt_exist'));
                                    break;
                                }
                                if($this->ins[$args[1]->game !== 1]){
                                    $sender->sendMessage($this->getPrefix().$this->getMsg('arena_not_running'));
                                    break;
                                }
                                $this->ins[$args[1]]->stopGame();
                                break;
                            //TO-DO case "ban":
                            case "kick": // cm kick [arena] [player] [reason]
                                if(!$sender->hasPermission('cm.command.kick')){
                                    $sender->sendMessage($this->getMsg('has_not_permission'));
                                    break;
                                }
                                if(!isset($args[2]) || isset($args[4])){
                                    $sender->sendMessage($this->getPrefix().$this->getMsg('kick_help'));
                                    break;
                                }
                                if(!isset(array_merge($this->ins[$args[1]]->ingamep, $this->ins[$args[1]]->lobbyp, $this->ins[$args[1]]->spec)[strtolower($args[2])])){
                                    $sender->sendMessage($this->getPrefix().$this->getMsg('player_not_exist'));
                                    break;
                                }
                                if(!isset($args[3])){
                                    $args[3] = "";
                                }
                                $this->ins[$args[1]]->kickPlayer($args[2], $args[3]);
                                break;
                            default:
                                $sender->sendMessage($this->getPrefix().$this->getMsg('help'));
                        }
                        return;
                        }
                        $sender->sendMessage('run command only in-game');
                        return;
                    }
                    $sender->sendMessage($this->getPrefix().$this->getMsg('help'));
            }
    }
    
    public function arenaExist($name){
        if(isset($this->arenas[$name])){
            return true;
        }
        return false;
    }
    
    public function getMsg($key){
        $msg = new Config($this->getDataFolder()."languages/English.yml", Config::YAML);
        return str_replace("&", "§", $msg->get($key));
    }
    
    public function onBlockTouch(PlayerInteractEvent $e){
        $p = $e->getPlayer();
        $b = $e->getBlock();
        if(isset($this->selectors[strtolower($p->getName())])){
            $p->sendMessage(TextFormat::BLUE."X: ".TextFormat::GREEN.$b->x.TextFormat::BLUE." Y: ".TextFormat::GREEN.$b->y.TextFormat::BLUE." Z: ".TextFormat::GREEN.$b->z);
        }
    }
    
    public function getPrefix(){
        return str_replace("&", "§", $this->cfg->get('Prefix'));
    }
    
    public function loadInvs(){
        foreach($this->getServer()->getOnlinePlayers() as $p){
            if(isset($this->inv[strtolower($p->getName())])){
                foreach($this->inv as $slot => $i){
                    list($id, $dmg, $count) = explode(":", $i);
                    $item = Item::get($id, $dmg, $count);
                    $p->getInventory()->setItem($slot, $item);
                    unset($this->plugin->inv[strtolower($p->getName())]);
                }
            }
        }
    }
    
    public function onBlockBreak(BlockBreakEvent $e){
        $p = $e->getPlayer();
        //for freezecraft only
        /*if(!$p->isOp()){
            $e->setCancelled(true);
        }*/
        if(isset($this->setters[strtolower($p->getName())]['arena']) && isset($this->setters[strtolower($p->getName())]['type'])){
            $e->setCancelled(true);
            $b = $e->getBlock();
            $arena = new ConfigManager($this->setters[strtolower($p->getName())]['arena'], $this);
            if($this->setters[strtolower($p->getName())]['type'] == "setjoinsign"){
                $arena->setJoinSign($b->x, $b->y, $b->z, $b->level->getName());
                $p->sendMessage($this->getPrefix().TextFormat::GREEN."Join sign has been set successfully");
            }
            if($this->setters[strtolower($p->getName())]['type'] == "setreturnsign"){
                $arena->setReturnSign($b->x, $b->y, $b->z);
                $p->sendMessage($this->getPrefix().TextFormat::GREEN."Return sign has been set successfully");
            }
            if($this->setters[strtolower($p->getName())]['type'] == "setjoinpos"){
                $arena->setJoinPos($b->x, $b->y, $b->z);
                $p->sendMessage($this->getPrefix().TextFormat::GREEN."Join position has been set successfully");
            }
            if($this->setters[strtolower($p->getName())]['type'] == "setlobbypos"){
                $arena->setLobbyPos($b->x, $b->y, $b->z);
                $p->sendMessage($this->getPrefix().TextFormat::GREEN."Lobby position has been set successfully");
            }
            if($this->setters[strtolower($p->getName())]['type'] == "setfirstcorner"){
                $arena->setFirstCorner($b->x, $b->y, $b->z);
                $p->sendMessage($this->getPrefix().TextFormat::GREEN."The first corner has been set successfully");
                $p->sendMessage($this->getPrefix().TextFormat::GREEN."Now break block to set the second corner");
                $this->setters[strtolower($p->getName())]['type'] = "setsecondcorner";
                return;
            }
            if($this->setters[strtolower($p->getName())]['type'] == "setsecondcorner"){
                $arena->setSecondCorner($b->x, $b->z);
                $p->sendMessage($this->getPrefix().TextFormat::GREEN."The second corner has been set successfully");
            }
            if($this->setters[strtolower($p->getName())]['type'] == "setspecspawn"){
                $arena->setSpecSpawn($b->x, $b->y, $b->z);
                $p->sendMessage($this->getPrefix().TextFormat::GREEN."Spectator spawn has been set successfully");
            }
            if($this->setters[strtolower($p->getName())]['type'] == "setleavepos"){
                $arena->setLeavePos($b->x, $b->y, $b->z, $b->level->getName());
                $p->sendMessage($this->getPrefix().TextFormat::GREEN."Leave position has been set successfully");
            }
        }
    }
    //for Freezecraft only
    /*public function onBlockPlace(BlockPlaceEvent $e){
        if(!$e->getPlayer()->isOp()){
            $e->setCancelled(true);
        }
    }
    //for freezecraft only
    public function onJoin(PlayerJoinEvent $e){
        $p = $e->getPlayer();
        $p->teleport($this->getServer()->getDefaultLevel()->getSpawnLocation());
    }*/
    
    public function onChat(PlayerChatEvent $e){
        $p = $e->getPlayer();
        $msg = strtolower(trim($e->getMessage()));
        if(isset($this->setters[strtolower($p->getName())]['arena'])){
            $e->setCancelled(true);
            $arena = new ConfigManager($this->setters[strtolower($p->getName())]['arena'], $this);
            switch($msg){
                case 'joinsign':
                    $this->setters[strtolower($p->getName())]['type'] = 'setjoinsign';
                    $p->sendMessage($this->getPrefix().TextFormat::GREEN."now break a sign");
                    return;
                case 'returnsign':
                    $this->setters[strtolower($p->getName())]['type'] = 'setreturnsign';
                    $p->sendMessage($this->getPrefix().TextFormat::GREEN."now break a sign");
                    return;
                case 'startpos':
                    $this->setters[strtolower($p->getName())]['type'] = 'setjoinpos';
                    $p->sendMessage($this->getPrefix().TextFormat::GREEN."now break a block");
                    return;
                case 'lobbypos':
                    $this->setters[strtolower($p->getName())]['type'] = 'setlobbypos';
                    $p->sendMessage($this->getPrefix().TextFormat::GREEN."now break a block");
                    return;
                case 'corners':
                    $this->setters[strtolower($p->getName())]['type'] = 'setfirstcorner';
                    $p->sendMessage($this->getPrefix().TextFormat::GREEN."now break a block");
                    return;
                case 'spectatorspawn':
                    $this->setters[strtolower($p->getName())]['type'] = 'setspecspawn';
                    $p->sendMessage($this->getPrefix().TextFormat::GREEN."now break a block");
                    return;
                case 'leavepos':
                    $this->setters[strtolower($p->getName())]['type'] = 'setleavepos';
                    $p->sendMessage($this->getPrefix().TextFormat::GREEN."now break a block");
                    return;
                case 'done':
                    $p->sendMessage($this->getPrefix().$this->getMsg('disable_setup_mode'));
                    $this->reloadArena($this->setters[strtolower($p->getName())]['arena']);
                    unset($this->setters[strtolower($p->getName())]);
                    return;
            }
            $args = explode(' ', $msg);
            if(count($args) >= 1 && count($args) <= 2){
                if($args[0] === 'help'){
                    $help1 = "\n- §2joinsign §l§5»§r§f Set join sign position"
                            . "\n- §2returnsign §l§5»§r§f Set leave sign position"
                            . "\n- §2startpos §l§5»§r§f Set start game position"
                            . "\n- §2lobbypos §l§5»§r§f Set lobby position"
                            . "\n- §2corners §l§5»§r§f Set floor corners"
                            . "\n- §2spectatorspawn §l§5»§r§f Set spectator spawn"
                            . "\n- §2leavepos §l§5»§r§f Set leave position";
                    $help2 = "\n- §2time §b[true/day/night/ticks] §l§5»§r§f Set time in arena"
                            . "\n- §2colortime §b[seconds] §l§5»§r§f Set color changing interval"
                            . "\n- §2type §b[classic/furious/stoned] §l»§r§f Set arena type"
                            . "\n- §2material §b[wool/clay] §l§5»§r§f Set floor material"
                            . "\n- §2allowstatus §b[true/false] §l§5»§r§f Enable/disable status on join sign"
                            . "\n- §2world §b[world name] §l§5»§r§f Set arena world"
                            . "\n- §2statusline §b[line (1-4)] [text] §l§5»§r§f Set status sign line";
                    $help3 = "\n- §2allowspectator §b[true/false] §l§5»§r§f Enable/Disable spectator mode"
                            . "\n- §2signupdatetime §b[seconds] §l§5»§r§f Set sign update interval"
                            . "\n- §2maxtime §b[seconds] §l§5»§r§f Set max game time"
                            . "\n- §2maxplayers §b[count] §l§5»§r§f Set max players in game"
                            . "\n- §2minplayers §b[count] §l§5»§r§f Set players minimal count required to start the game";
                    $helparray = [$help1, $help2, $help3];
                    if(isset($args[1])){
                        if(intval($args[1]) >= 1 && intval($args[1]) <= 3){
                            $help = "§9--- §6§lColorMatch setup help§l $args[1]/3§9 ---§r§f";
                            $help .= $helparray[intval(intval($args[1]) - 1)];
                            $p->sendMessage($help);
                            return;
                        }
                        $p->sendMessage($this->getPrefix()."§6use: §ahelp §b[page 1-3]");
                        return;
                    }
                    $p->sendMessage("§9--- §6§lColorMatch setup help§l 1/3§9 ---§r§f".$help1);
                    return;
                }
            }
            if(count(explode(' ', $msg)) !== 2 && strpos($msg, 'statusline') !== 0){
                $p->sendMessage($this->getPrefix().$this->getMsg('invalid_arguments'));
                return;
            }
            if(substr($msg, 0, 10) === 'statusline'){
                if(!strlen(substr($msg, 13)) >= 1 || !intval(substr($msg, 11, 1)) >= 1 || !intval(substr($msg, 11, 1) <= 4)){
                    $p->sendMessage($this->getPrefix().TextFormat::GOLD."use: ".TextFormat::DARK_GREEN."statusline ".TextFormat::AQUA."[line] [text]");
                    return;
                }
                $arena->setStatusLine($args[1], substr($msg, 13));
                $p->sendMessage($this->getPrefix().TextFormat::GREEN."Status line has been set successfully");
                return;
            }
            elseif(strpos($msg, 'type') === 0){
                if(substr($msg, 5) === 'classic' || substr($msg, 5) === 'furious' || substr($msg, 5) === 'stoned'){
                    $arena->setType(substr($msg, 4));
                    $p->sendMessage($this->getPrefix().TextFormat::GREEN."Arena type has been set successfully");
                    return;
                }
                $p->sendMessage($this->getPrefix()."§6use: §atype §b[classic/furious/stoned");
                return;
            }
            elseif(strpos($msg, 'enable') === 0){
                if(substr($msg, 7) === 'true' || substr($msg, 7) === 'false'){
                    $arena->setEnable(substr($msg, 7));
                    $p->sendMessage($this->getPrefix().TextFormat::GREEN."Arena has been successfully updated");
                    return;
                }
                $p->sendMessage($this->getPrefix()."§6use: §aenable §b[true/false]");
                return;
            }
            elseif(strpos($msg, 'material') === 0){
                if(substr($msg, 9) === 'wool' || substr($msg, 9) === 'clay'){
                    $arena->setMaterial(substr($msg, 9));
                    $p->sendMessage($this->getPrefix().TextFormat::GREEN."Floor material has been set successfully");
                    return;
                }
                $p->sendMessage($this->getPrefix()."§6use: §amaterial §b[wool/clay]");
            }
            elseif(strpos($msg, 'allowstatus') === 0){
                if(substr($msg, 12) === 'true' || substr($msg, 12) === 'false'){
                    $arena->setStatus(substr($msg, 12));
                    $p->sendMessage($this->getPrefix().TextFormat::GREEN."Sign status has been set successfully");
                    return;
                }
                $p->sendMessage($this->getPrefix()."§6use: §aallowstatus §b[true/false]");
            }
            elseif(strpos($msg, 'signupdatetime') === 0){
                if(!is_numeric(substr($msg, 15))){
                    $p->sendMessage($this->getPrefix()."§6use: §asignupdatetime §b[seconds]");
                    return;
                }
                $arena->setUpdateTime(substr($msg, 15));
                $p->sendMessage($this->getPrefix().TextFormat::GREEN."Sign update interval has been set successfully");
            }
            elseif(strpos($msg, 'world') === 0){
                if(is_string(substr($msg, 6))){
                    $arena->setArenaWorld(substr($msg, 6));
                    $p->sendMessage($this->getPrefix().TextFormat::GREEN."Arena world has been set successfully");
                    return;
                }
                $p->sendMessage($this->getPrefix()."§6use: §aworld §b[world name]");
            }
            elseif(strpos($msg, 'allowspectator') === 0){
                if(substr($msg, 15) === 'true' || substr($msg, 15) === 'false'){
                    $arena->setSpectator(substr($msg, 15));
                    $p->sendMessage($this->getPrefix().TextFormat::GREEN."Spectator mode has been set successfully");
                    return;
                }
                $p->sendMessage($this->getPrefix()."§6use: §aallowspectator §b[true/false]");
            }
            elseif(strpos($msg, 'maxtime') === 0){
                if(!is_numeric(substr($msg, 8))){
                    $p->sendMessage($this->getPrefix()."§6use: §amaxtime §b[seconds]");
                    return;
                }
                $arena->setMaxTime(substr($msg, 8));
                $p->sendMessage($this->getPrefix().TextFormat::GREEN."Max game time has been set successfully");
            }
            elseif(strpos($msg, 'maxplayers') === 0){
                if(!is_numeric(substr($msg, 11))){
                    $p->sendMessage($this->getPrefix()."§6use: §amaxplayers §b[count]");
                    return;
                }
                $arena->setMaxPlayers(substr($msg, 11));
                $p->sendMessage($this->getPrefix().TextFormat::GREEN."Max players count has been set successfully");
            }
            elseif(strpos($msg, 'minplayers') === 0){
                if(!is_numeric(substr($msg, 11))){
                    $p->sendMessage($this->getPrefix()."§6use: §aminplayers §b[count]");
                    return;
                }
                $arena->setMinPlayers(substr($msg, 11));
                $p->sendMessage($this->getPrefix().TextFormat::GREEN."Min players count has been set successfully");
            }
            elseif(strpos($msg, 'starttime') === 0){
                if(!is_numeric(substr($msg, 10))){
                    $p->sendMessage($this->getPrefix()."§6use: §astarttime §b[seconds]");
                    return;
                }
                $arena->setStartTime(substr($msg, 10));
                $p->sendMessage($this->getPrefix().TextFormat::GREEN."Starting time has been set successfully");
            }
            elseif(strpos($msg, 'colortime') === 0){
                if(!is_numeric(substr($msg, 10))){
                    $p->sendMessage($this->getPrefix()."§6use: §acolortime §b[seconds]");
                    return;
                }
                $arena->setColorTime(substr($msg, 10));
                $p->sendMessage($this->getPrefix().TextFormat::GREEN."Color change interval has been set successfully");
            }
            elseif(strpos($msg, 'time') === 0){
                if(substr($msg, 5) === 'true' || substr($msg, 5) === 'day' || substr($msg, 5) === 'night' || is_numeric(substr($msg, 5))){
                    $arena->setTime(substr($msg, 5));
                    $p->sendMessage($this->getPrefix().TextFormat::GREEN."Arena world time has been set successfully");
                    return;
                }
                $p->sendMessage($this->getPrefix()."§6use: §atime §b[true / day / night / ticks]");
            }
            else{
                $p->sendMessage($this->getPrefix().$this->getMsg('invalid_arguments'));
            }
        }
    }
    
    public function onQuit(PlayerQuitEvent $e){
        $p = $e->getPlayer();
        //for FC only
        //$p->teleportImmediate($this->getServer()->getDefaultLevel()->getSpawnLocation());
        $this->unsetPlayers($p);
    }
    
    public function onKick(PlayerKickEvent $e){
        $p = $e->getPlayer();
        //for FC only
        //$p->teleportImmediate($this->getServer()->getDefaultLevel()->getSpawnLocation());
        $this->unsetPlayers($p);
    }
    
    public function unsetPlayers(Player $p){
        if(isset($this->selectors[strtolower($p->getName())])){
            unset($this->selectors[strtolower($p->getName())]);
        }
        if(isset($this->setters[strtolower($p->getName())])){
            $this->reloadArena($this->setters[strtolower($p->getName())]['arena']);
            if($this->isArenaSet($this->setters[strtolower($p->getName())]['arena'])){
                $a = new Arena($this->setters[strtolower($p->getName())]['arena'], $this);
                $a->setup = false;
            }
            unset($this->setters[strtolower($p->getName())]);
        }
    }
    
    public function reloadArena($name){
        $arena = new Config($this->getDataFolder()."arenas/$name.yml");
        if(!$this->checkFile($arena) || $arena->get('enabled') === "false"){
            $this->arenas[$name] = $arena->getAll();
            $this->arenas[$name]['enable'] = false;
            return;
        }
        if($this->arenas[$name]['enable'] === false){
            $this->setArenasData($arena, $name);
            return;
        }
        $this->arenas[$name] = $arena->getAll();
        $this->arenas[$name]['enable'] = true;
        $this->ins[$name]->data = $this->arenas[$name];
    }
    
    public function getPlayerArena(Player $p){
        foreach($this->ins as $arena){
            $players = array_merge($arena->ingamep, $arena->lobbyp, $arena->spec);
            if(isset($players[strtolower($p->getName())])){
                return $arena;
            }
        }
        return false;
    }
    
    public function isArenaSet($name){
        if(isset($this->ins[$name])) return true;
        return false;
    }
}