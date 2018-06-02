<?php

namespace BedWars;

use pocketmine\minetox\MTUtility;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\math\Vector3;
use BedWars\Arena\Arena;
use pocketmine\utils\Config;

class BedWars extends PluginBase
{
    // object variables
	//public $config;
	public $ctfBuilder;
	public $ctfManager;
	public $ctfMessages;
	public $ctfGameKit;
	public $ctfSetup;
        public $ctfShop;
	
	// keep track of all points
	public $redTeamPlayers = [ ];
        public $redContents = [ ];
        public $redArmor = [ ];
	public $blueTeamPlayers = [ ];
        public $blueContents = [ ];
        public $blueArmor = [ ];
	public $yellowTeamPlayers = [ ];
        public $yellowContents = [ ];
        public $yellowArmor = [ ];
	public $greenTeamPlayers = [ ];
        public $greenContents = [ ];
        public $greenArmor = [ ];
	public $gameStats = [ ];	
        public $lobbyPlayers = [];
        
	
	// keep game statistics
	public $gameMode = 0;
	public $gameState = 0;
	public $blueTeamWins = 0;
	public $redTeamWins = 0;
	public $yellowTeamWins = 0;
	public $greenTeamWins = 0;
	public $pos_display_flag = 0;
	public $currentGameRound = 0;
	public $maxGameRound = 3;
        public $redBed = 0;
        public $blueBed = 0;
        public $yellowBed = 0;
        public $greenBed = 0;
	
        public $seconds = 0;
        public $taskId;
        public $ingame = false;
        public $starting = false;
	//lobby world
	public $CTFWorldName;

	//setup mode
	public $setupModeAction = "";
        public $bwLevel;
        public $restart = false;
        
        public $database;
        
        
	/**
	 * OnLoad
	 * (non-PHPdoc)
	 *
	 * @see \pocketmine\plugin\PluginBase::onLoad()
	 */
	public function onLoad() {		
		$this->initMinigameComponents();
	}
	
	/**
	 * OnEnable
	 *
	 * (non-PHPdoc)
	 *
	 * @see \pocketmine\plugin\PluginBase::onEnable()
	 */
	public function onEnable() {	
            $time = 1;
		$this->initConfigFile ();				
		$this->enabled = true;
		$this->getServer ()->getPluginManager ()->registerEvents ( new Arena ( $this ), $this );
		$this->getLogger ()->info (TextFormat::RED."Bed".TextFormat::WHITE."wars"." ".TextFormat::GREEN."Enabled");
		$this->getLogger ()->info (TextFormat::GREEN.TextFormat::ITALIC."by CreeperFace");
                $this->getLogger ()->info (TextFormat::GREEN . "-------------------------------------------------" );
		$this->initMessageTests();
                $this->initScheduler();
                $this->createMySQLConnection();
                $this->restart = true;
                
                
		
                
                
                //check if everything initializared
		if ($this->ctfManager==null) {
			$this->getLogger()->info(" manager not initialized properly");
		}		
		if ($this->ctfSetup==null) {
			$this->getLogger()->info(" setup not initialized properly");
		}
		if ($this->ctfMessages==null) {
			$this->getLogger()->info(" messages not initialized properly");
		}
		if ($this->ctfBuilder==null) {
			$this->getLogger()->info(" builder not initialized properly");
		}
	}
	
	private function initMinigameComponents() {
		try {
		$this->ctfSetup = new CTFSetup ( $this );
		$this->ctfMessages = new CTFMessages ( $this );
		$this->ctfManager = new CTFManager ( $this );		
		$this->ctfBuilder = new CTFBlockBuilder ( $this );
                $this->ctfShop = new CTFShop( $this );
		} catch ( \Exception $ex ) {
			$this->getLogger ()->error( $ex->getMessage() );
		}
	}
	
	private function initConfig() {
		try {
			$this->saveDefaultConfig ();
			if (! file_exists ( $this->getDataFolder () )) {
				@mkdir ( $this->getDataFolder (), 0777, true );
				file_put_contents ( $this->getDataFolder () . "config.yml", $this->getResource ( "config.yml" ) );
			}
			$this->reloadConfig ();
			$this->getConfig ()->getAll ();			
		} catch ( \Exception $e ) {
			$this->getLogger ()->error ( $e->getMessage());
		}
	}
	
	private function initMessageTests() {
		if ($this->getConfig ()->get ( "run_selftest_message" ) == "YES") {
			$stmsg = new TestMessages ( $this );
			$stmsg->runTests ();
		}
	}
	
        public function initScheduler() {
		// run reset scheduler
		$resetTask = new Timer ( $this );
		$taskWaitTime = 20;
                $this->taskId = $this->getServer()->getScheduler()->scheduleRepeatingTask( $resetTask, $taskWaitTime)->getTaskId();
	}
	/**
	 * OnDisable
	 * (non-PHPdoc)
	 *
	 * @see \pocketmine\plugin\PluginBase::onDisable()
	 */
	public function onDisable() {
		$this->getLogger ()->info ( TextFormat::RED . $this->ctfMessages->getMessageByKey ( "plugin.disable" ) );
		$this->enabled = false;
	}
	
	public function clearSetup() {
		$this->setupModeAction="";
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
        
        public function registerPlayer($player)
    {
        $name = trim(strtolower($player));
        $data =
        [
            "beds" => 0,
            "kills" => 0,
            "deaths" => 0,
            "wins" => 0,
            "losses" => 0,
        ];

        $this->getDatabase()->query
        (
            "INSERT INTO minetox_bedwars
            (name, deaths, kills, losses, wins, beds)
            VALUES
            ('".$this->getDatabase()->escape_string($name)."', ".$data["deaths"].", ".$data["kills"].", ".$data["losses"].", ".$data["wins"].")"
        );
        $this->getLogger()->Info("Ein neuer Spieler wurde registriert: ". $player);
        return $data;
    }
    
    public function getPlayer($player)
    {
        $result = $this->getDatabase()->query
        (
            "SELECT * FROM minetox_bedwars WHERE name = '" . $this->getDatabase()->escape_string(trim(strtolower($player)))."'"
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
	
    public function setDatabase(\mysqli $database)
    {
        $this->database = $database;
    }

    /**
     * @return Database
     */
    public function getDatabase()
    {
        return $this->database;
    }
    
    public function addBed($player, $beds = 1){
        $this->getDatabase()->query
        (
            "UPDATE minetox_bedwars SET beds = beds+'".$beds."' WHERE name = '".$this->getDatabase()->escape_string(trim(strtolower($player)))."'"
        );
    }
    
    public function addKill($player, $kills = 1){
        $this->getDatabase()->query
        (
            "UPDATE minetox_bedwars SET kills = kills+'".$kills."' WHERE name = '".$this->getDatabase()->escape_string(trim(strtolower($player)))."'"
        );
    }
    
    public function addDeath($player, $deaths = 1){
        $this->getDatabase()->query
        (
            "UPDATE minetox_bedwars SET bed = bed+'".$deaths."' WHERE name = '".$this->getDatabase()->escape_string(trim(strtolower($player)))."'"
        );
    }
    
    public function addWin($player, $wins = 1){
        $this->getDatabase()->query
        (
            "UPDATE minetox_bedwars SET wins = wins+'".$wins."' WHERE name = '".$this->getDatabase()->escape_string(trim(strtolower($player)))."'"
        );
    }
    
    public function addLoss($player, $losses = 1){
        $this->getDatabase()->query
        (
            "UPDATE minetox_bedwars SET losses = losses+'".$losses."' WHERE name = '".$this->getDatabase()->escape_string(trim(strtolower($player)))."'"
        );
    }
	/**
	 * OnCommand
	 * (non-PHPdoc)
	 *
	 * @see \pocketmine\plugin\PluginBase::onCommand()
	 */
	public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
		$this->ctfManager->onCommand ( $sender, $command, $label, $args );
	}
}