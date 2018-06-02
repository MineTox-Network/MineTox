<?php

namespace Annihilation;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use Annihilation\Arena\Arena;
use pocketmine\minetox\MTUtility;
use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;


class Annihilation extends PluginBase{
    private $arenas = [];
    public $database;
    public $maps = [];
    public $ins = [];
    
    public function onEnable() {
        $this->getServer()->loadLevel("anni_lobby");
        $this->getLogger()->info(TextFormat::GREEN."Annihilation enabled");
        $this->createMySQLConnection();
        //arena1
        $this->maps['canyon'] = ["1Spawn" => new Vector3(-108, 76, -121),
                                                  "2Spawn" => new Vector3(-121, 76, 233), 
                                                  "3Spawn" => new Vector3(233, 76, 246), 
                                                  "4Spawn" => new Vector3(246, 76, 108),
                                                  "1Nexus" => new Vector3(-113, 70, -114),
                                                  "2Nexus" => new Vector3(-114, 70, 121),
                                                  "3Nexus" => new Vector3(238, 70, 239),
                                                  "4Nexus" => new Vector3(239, 70, 113),
                                                  "1Chest" => new Vector3(-102, 73, -112),
                                                  "2Chest" => new Vector3(-112, 73, 227),
                                                  "3Chest" => new Vector3(227, 73, 237),
                                                  "4Chest" => new Vector3(237, 73, -102),
                                                  "1Furnace" => new Vector3(-103, 73, -112),
                                                  "2Furnace" => new Vector3(-112, 73, 228),
                                                  "3Furnace" => new Vector3(228, 73, 237),
                                                  "4Furnace" => new Vector3(237, 73, -103),
                                                  //signs
                                                  "1Brewing" => new Vector3(),
                                                  "1Weapons" => new Vector3(),
                                                  "2Brewing" => new Vector3(),
                                                  "2Weapons" => new Vector3(),
                                                  "3Brewing" => new Vector3(),
                                                  "3Weapons" => new Vector3(),
                                                  "4Brewing" => new Vector3(),
                                                  "4Weapons" => new Vector3(),
                                                  //diamonds
                                                  "diamonds" => [ new Vector3(), new Vector3(), new Vector3(),
                                                       new Vector3(), new Vector3(), new Vector3(), new Vector3(),
                                                       new Vector3(), new Vector3(), new Vector3(), new Vector3(),
                                                       new Vector3(), new Vector3(), new Vector3(), new Vector3()],
                                                  "bosses" => [1 => ['name' => '§cCelariel', 'pos' => new Vector3(61, 15, -52), 'chest' => new Vector3(62, 15, -42)], 2 => ['name' => '§bFerwin', 'pos' => new Vector3(64, 15, 177), 'chest' => new Vector3(63, 15, 167)]]
                                                   ];
        $this->registerServer('anni1', 2, 108, -334, $this->getServer()->getLevelbyName("anni_lobby"));
    }
    
    public function onDisable() {
        $this->getLogger()->info(TextFormat::RED."Annihilation disabled");
    }
    
    public function registerServer($id, $x, $y, $z, Level $level, $build = false){
        $this->arenas[$id] = new Arena($id, $this);
        $this->arenas[$id]->setArenaData("level", $level);
        $this->arenas[$id]->setArenaData("x", $x);
        $this->arenas[$id]->setArenaData("y", $y);
        $this->arenas[$id]->setArenaData("z", $z);
        $level->setAutoSave($build);
        $level->setTime(1000);
        $level->stopTime();
        $manager = $this->getServer()->getPluginManager()->getPlugin("MTCore")->getServerManager();
        $this->getServer()->getPluginManager()->registerEvents($this->arenas[$id], $this);
        $manager->addServer($this->arenas[$id]);
    }
    
    public function createMySQLConnection()
    {
        $database = new \mysqli("85.10.205.173", "minetoxserver", "5k30^/#$2xn82T[5|[_U577@/xO22=6}ucp>o7n1U:P?:MImg4=X<EFuc45Mt+c", "minetox", 3306);
        $this->setDatabase($database);
        if($database->connect_error)
        {
            $this->getLogger()->critical("Es konnte keine Verbindung zum MySQL hergestellt werden: ". $database->connect_error);
        }
        else
        {
            $this->getLogger()->info("§2Connected to the §3MySQL §2Server!");
            MTUtility::getMTCore()->getMySQLManager()->registerDatabase($database);
            $resource = $this->getResource("mysql.sql");
            $database->query(stream_get_contents($resource));
            fclose($resource);
        }
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
        if($sender instanceof Player){
            $arena = $this->getPlayerArena($sender);
            switch(strtolower($cmd->getName())){
                case 'class':
                    if($arena === false){
                        break;
                    }
                    $arena->kitManager->addKitWindow($sender);
                    break;
                case 'blue':
                    if(!$sender->isOp()){
                        $arena->joinTeam($sender, 1, true);
                        break;
                    }
                    $arena->joinTeam($sender, 1);
                    break;
                case 'red':
                    if(!$sender->isOp()){
                        $arena->joinTeam($sender, 2, true);
                        break;
                    }
                    $arena->joinTeam($sender, 2);
                    break;
                case 'yellow':
                    if(!$sender->isOp()){
                        $arena->joinTeam($sender, 3, true);
                        break;
                    }
                    $arena->joinTeam($sender, 3);
                    break;
                case 'green':
                    if(!$sender->isOp()){
                        $arena->joinTeam($sender, 4, true);
                        break;
                    }
                    $arena->joinTeam($sender, 4);
                    break;
                case 'lobby':
                    break;
                case 'stats':
                    break;
                case 'msg':
                    if(!isset($args[0]) || !isset($args[1])){
                        $sender->sendMessage(TextFormat::GOLD."[Annihilation] ".TextFormat::GRAY."use /msg [player] message");
                        break;
                    }
                    break;
                case 'vote':
                    if(isset($args[1]) || !isset($args[0])){
                        $sender->sendMessage(TextFormat::GOLD."[Annihilation] ".TextFormat::GRAY."use /vote [map]");
                        break;
                    }
                    $this->getPlayerArena($sender)->votingManager->onVote($sender, strtolower($args[0]));
                    break;
            }
        }
    }
    
    public function getDatabase()
    {
        return $this->database;
    }
    
    public function setDatabase(\mysqli $database)
    {
        $this->database = $database;
    }
    
    public function registerPlayer($player)
    {
        $name = trim(strtolower($player));
        $data =
        [
            "nexus_dmg" => 0,
            "nexuses" => 0,
            "kills" => 0,
            "deaths" => 0,
            "wins" => 0,
            "losses" => 0,
        ];

        $this->getDatabase()->query
        (
            "INSERT INTO minetox_annihilation
            (name, kills, deaths, wins, losses, nexus_dmg, nexuses)
            VALUES
            ('".$this->getDatabase()->escape_string($name)."', ".$data["kills"].", ".$data["deaths"].", ".$data["wins"].", ".$data["losses"].", ".$data["nexus_dmg"].", ".$data["nexuses"].")"
        );
        $this->getLogger()->Info("Ein neuer Spieler wurde registriert: ". $player);
        return $data;
    }
    
    public function getPlayer($player)
    {
        $result = $this->getDatabase()->query
        (
            "SELECT * FROM minetox_annihilation WHERE name = '" . $this->getDatabase()->escape_string(trim(strtolower($player)))."'"
        );

        if($result instanceof \mysqli_result)
        {
            $data = $result->fetch_assoc();
            $result->free();
            if(isset($data["name"]) and $data["name"] === trim(strtolower($player)))
            {
                unset($data["name"]);
                return $data;
            }
        }
        return null;
    }
    
    public function isPlayerRegistered($name)
    {
        return $this->getPlayer($name) !== null;
    }
    
    public function addKill($player, $kills = 1)
    {
        $this->getDatabase()->query
        (
            "UPDATE minetox_annihilation SET kills = kills+'".$kills."' WHERE name = '".$this->getDatabase()->escape_string(trim(strtolower($player)))."'"
        );
    }
    
    public function addDeath($player, $deaths = 1)
    {
        $this->getDatabase()->query
        (
            "UPDATE minetox_annihilation SET deaths = deaths+'".$deaths."' WHERE name = '".$this->getDatabase()->escape_string(trim(strtolower($player)))."'"
        );
    }
    
    public function addWin($player, $wins = 1)
    {
        $this->getDatabase()->query
        (
            "UPDATE minetox_annihilation SET wins = wins+'".$wins."' WHERE name = '".$this->getDatabase()->escape_string(trim(strtolower($player)))."'"
        );
    }
    
    public function addLoss($player, $losses = 1)
    {
        $this->getDatabase()->query
        (
            "UPDATE minetox_annihilation SET losses = losses+'".$losses."' WHERE name = '".$this->getDatabase()->escape_string(trim(strtolower($player)))."'"
        );
    }
    
    public function addNexusDmg($player, $nexusDmg = 1)
    {
        $this->getDatabase()->query
        (
            "UPDATE minetox_annihilation SET nexusDmg = nexusDmg+'".$nexusDmg."' WHERE name = '".$this->getDatabase()->escape_string(trim(strtolower($player)))."'"
        );
    }
    
    public function addNexusDestroy($player, $nexuses = 1)
    {
        $this->getDatabase()->query
        (
            "UPDATE minetox_annihilation SET nexuses = nexuses+'".$nexuses."' WHERE name = '".$this->getDatabase()->escape_string(trim(strtolower($player)))."'"
        );
    }
    
    public function addTokens(Player $player, $tokens)
    {
        $this->getDatabase()->query
        (
            "UPDATE minetox_playerstats SET tokens = tokens+'".$tokens."' WHERE name = '".$this->getDatabase()->escape_string(trim(strtolower($player->getName())))."'"
        );
    }
    
    public function getRank($player)
    {
        $data = $this->getPlayer($player);
        return $data["rank"];
    }
    
    public function getPlayerArena(Player $p){
        foreach($this->arenas as $arena){
            if($arena->arenaTeams->getPlayerTeam($p) !== false){
                return $arena;
            }
        }
        return false;
    }
}