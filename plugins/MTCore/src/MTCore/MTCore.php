<?php

/*
*  _____                              __  __   _                  _                  
* |_   _|   ___    __ _   _ __ ___   |  \/  | (_)  _ __     ___  | |_    ___   __  __
*   | |    / _ \  / _` | | '_ ` _ \  | |\/| | | | | '_ \   / _ \ | __|  / _ \  \ \/ /
*   | |   |  __/ | (_| | | | | | | | | |  | | | | | | | | |  __/ | |_  | (_) |  >  < 
*   |_|    \___|  \__,_| |_| |_| |_| |_|  |_| |_| |_| |_|  \___|  \__|  \___/  /_/\_\
* 
*/

/* Legende
* ====================
 * Rang:
 * 0 = Nicht eingeloggt
 * 1 = Member 		 <-> Normal
 * 2 = Premium		 <-> Premium
 * 3 = Youtuber 	 <-> Youtuber Rechte
 * 4 = Moderator	 <-> Moderatoren Rechte -> Developer
 * 5 = Administrator <-> Administratoren Rechte
 * 6 = Owner   		 <-> Alle Rechte
 */

namespace MTCore;

use pocketmine\minetox\AccountManager\AccountManager;
use pocketmine\minetox\MySQLManager\MySQLManager;
use pocketmine\command\CommandSender;
use pocketmine\Utils\Utils;
use pocketmine\minetox\ServerManager;
use pocketmine\minetox\MTMinigame;
use pocketmine\minetox\MTUtility;
use pocketmine\minetox\Task\PopupTask;
use pocketmine\plugin\PluginBase;
use pocketmine\minetox\MTServer;
use pocketmine\command\Command;
use MTCore\Task\MTSignManager;
use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\item\Item;
use MTCore\Task\MTTask;
use pocketmine\Server;
use pocketmine\Player;

class MTCore extends PluginBase
{
    public $hubs = [];
    public $timer = 0;

    private $servermanager;
    private $mysqlManager;
    private $acManager;
    private $database;

    public function onEnable()
    {
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new PopupTask(Server::getInstance()), 10);
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new MTSignManager($this), 30);
        $this->getServer()->getPluginManager()->registerEvents(new MTListener($this), $this);
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new MTTask($this),1200);
        $this->servermanager = new ServerManager($this);
        $this->mysqlManager = new MySQLManager(Server::getInstance());
        $this->acManager = new AccountManager(Server::getInstance(), $this->mysqlManager);
        $this->getServer()->getPluginManager()->registerEvents($this->servermanager, $this);
        MTUtility::initMTUtility();
        try
        {
            $this->getServer()->loadLevel("hub1");
            $this->getServer()->loadLevel("premiumhub1");
            $this->getServer()->loadLevel("silenthub1");

            $this->registerHub(1, 0, 436, 85, 1042, $this->getServer()->getLevelByName("hub1"));

            $this->registerHub(2, 1, 436, 85, 1042, $this->getServer()->getLevelByName("premiumhub1"));

            $this->registerHub(3, 2, 436, 85, 1042, $this->getServer()->getLevelByName("silenthub1"));
        }
        catch(\exception $e)
        {
            $this->getLogger()->critical("Der Server konnte nicht die Hub's laden: ".$e->getMessage());
        }

        $database = new \mysqli("85.10.205.173", "minetoxserver", "5k30^/#$2xn82T[5|[_U577@/xO22=6}ucp>o7n1U:P?:MImg4=X<EFuc45Mt+c", "minetox", 3306);
        $this->setDatabase($database);
        if($database->connect_error)
        {
            $this->getLogger()->critical("Es konnte keine Verbindung zum MySQL hergestellt werden: ". $database->connect_error);
        }
        else
        {
            $this->getLogger()->info("§2Connected to the MySQL Server!");
            MTUtility::getMTCore()->getMySQLManager()->registerDatabase($database);
            $resource = $this->getResource("mysql.sql");
            $database->query(stream_get_contents($resource));
            fclose($resource);
        }
        $this->registerServerstats(1);
        $this->getServer()->getNetwork()->setName("§6Minetox: §ePE Server [".$this->getPlayerCount(1)."/500]");
        $this->getLogger()->info("§2[Minetox] Der §1MTCore §2 wurde geladen!");
    }

    /**
     * @param int $id
     * @param int $type
     * @param int $x
     * @param int $y
     * @param int $z
     * @param Level $level
     */
    public function registerHub($id, $type, $x, $y, $z, level $level)
    {
        $this->hubs[$id] = new MTHub($id, $this, $type);
        $hub = $this->hubs[$id];
        if($hub instanceof MTServer)
        {
            $level->setTime(1000);
            $level->stopTime();
            $hub->setArenaData("x", $x);
            $hub->setArenaData("y", $y);
            $hub->setArenaData("z", $z);
            $hub->setArenaData("level", $level);
            $this->getServerManager()->addServer($hub);
            $level->setSpawnLocation(new Vector3(436, 85, 1042));
        }
        else
        {
            $this->getLogger()->critical("Couldnt register Hub! ID: ".$id);
        }
    }

    /**
     * @return ServerManager
     */
    public function getServerManager()
    {
        return $this->servermanager;
    }

    /**
     * @return MySQLManager
     */
    public function getMySQLManager()
    {
        return $this->mysqlManager;
    }
    /**
     * @return AccountManager
     */
    public function getAccountManager()
    {
        return $this->acManager;
    }

    public function setDatabase(\mysqli $database)
    {
        $this->database = $database;
    }

    /**
     * @return \mysqli
     */
    public function getDatabase()
    {
        return $this->database;
    }

    public function LogStatus()
    {
		$mUsage = Utils::getMemoryUsage(true);
		$server = $this->getServer();
        $this->getLogger()->info("§e================================================");
		$this->getLogger()->info("§6Current TPS: §2".$server->getTicksPerSecond() . " (".$server->getTickUsage()."%)");

		$this->getLogger()->info("§6Network upload: §c".round($server->getNetwork()->getUpload() / 1024, 2) . " kB/s");
		$this->getLogger()->info("§6Network download: §c".round($server->getNetwork()->getDownload() / 1024, 2) . " kB/s");

		$this->getLogger()->info("§6Thread count: §c".Utils::getThreadCount());

		$this->getLogger()->info("§6Main thread memory: §c".number_format(round(($mUsage[0] / 1024) / 1024, 2)) . " MB.");
		$this->getLogger()->info("§6Allocated memory: §c".number_format(round(($mUsage[1] / 1024) / 1024, 2)) . " MB.");
		$this->getLogger()->info("§6Maximum memory (system): §c".number_format(round(($mUsage[2] / 1024) / 1024, 2)) . " MB.");
        $this->getLogger()->info("§e================================================");
   }

    /**
     * @param Player $player
     * @param bool $clearInv
     * @return bool
     */
    public function giveSpawnItems(Player $player, $clearInv = false) //TODO
    {
        $inventory = $player->getInventory();
        if($clearInv)
        {
            $inventory->clearAll();
        }

        switch($player->rank)
        {
            case 1:
                $inventory->addItem(new Item(345, 0, 1));
                $inventory->addItem(new Item(347, 0, 1));
                break;
            case 2:
                $inventory->addItem(new Item(345, 0, 1));
                $inventory->addItem(new Item(347, 0, 1));
                $inventory->addItem(new Item(265, 0, 1));
                break;
            case 3:
            case 4:
            case 5:
            case 6:
                $inventory->addItem(new Item(345, 0, 1));
                $inventory->addItem(new Item(347, 0, 1));
                $inventory->addItem(new Item(46, 0, 1));
                $inventory->addItem(new Item(265, 0, 1)); //Checks on which Server the Player is
                break;
            default:
        }
    }

    /**
     * @param $player
     * @param $new_rank
     */
    public function promotePlayer($player, $new_rank)
    {
        $this->getAccountManager()->setRank($player, $new_rank);
        if($player = $this->getServer()->getPlayer($player))
        {
            $player->sendMessage("§6[Minetox] §eDu wurdest zum ".MTUtility::RanktoString($new_rank, 1)." §ebefördert");
            $player->rank = $new_rank;
        }
    }

    /**
     * @param Player $player
     * @param $id
     * @param int $priority = 0 means OK
     */
    public function sendError(Player $player, $id, $priority = 0) //TODO
    {
        $player->sendMessage("§7=============================================");
        $player->sendMessage("§4[Error] Ein Fehler ist aufgetreten. ID: ".$id);
        $player->sendMessage("§4[Error] Wenn dieser Fehler öfter auftritt,");
        $player->sendMessage("§4[Error] melde dich bitte bei einem Teammitglied");
        $player->sendMessage("§7=============================================");

        $this->getLogger()->alert("=============================================");
        $this->getLogger()->alert("Ein Fehler ist aufgetreten. ID: ".$id);
        $this->getLogger()->alert("Betroffener Spieler: ".$player->getName());
        $this->getLogger()->alert("Auf Server ".$player->minetoxserver);
        $this->getLogger()->alert("=============================================");
        if($priority <= 5)
        {
            $this->getLogger()->critical("A fatal Error has occurred");
            $this->getLogger()->critical("Shutting down the Server...");
            $this->getServer()->forceShutdown();
        }
    }

    public function getServerstats($id)
    {
        $result = $this->getDatabase()->query
        (
            "SELECT * FROM minetox_serverstats WHERE id = ".$id.""
        );

        if($result instanceof \mysqli_result)
        {
            $data = $result->fetch_assoc();
            $result->free();
            if(isset($data["id"]) and $data["id"] === $id)
            {
                unset($data["id"]);
                return $data;
            }
        }
        return null;
    }

    public function isServerRegistered($id)
    {
        return $this->getServerstats($id) !== null;
    }

    public function registerServerstats($id, $playerCount = 0)
    {
        if($this->isServerRegistered($id))
        {
            $this->getDatabase()->query
            (
                "INSERT INTO minetox_serverstats
                (id, playerCount)
                VALUES
                (".$id.", ".$playerCount.")"
            );
        }
    }

    public function increasePlayerCount($id = 1)
    {
        $this->getDatabase()->query
        (
            "UPDATE minetox_serverstats SET playerCount = playerCount+1 WHERE id = ".$id.""
        );
    }

    public function decreasePlayerCount($id = 1)
    {
        $this->getDatabase()->query
        (
            "UPDATE minetox_serverstats SET playerCount = playerCount-1 WHERE id = ".$id.""
        );
    }

    public function getPlayerCount($id = 1)
    {
        $data = $this->getServerstats($id);
        return $data["playerCount"];
    }

    /**
     * @param CommandSender $player
     * @param Command $cmd
     * @param string $label
     * @param array $args
     * @return bool
     */
    public function onCommand(CommandSender $player, Command $cmd, $label, array $args)
    {
        switch($cmd->getName())
        {
            case 'join':
                if($player instanceof Player)
                {
                    if(isset($args[0]) and $player->rank >= 0)
                    {
                        $server = $this->getServerManager()->getServerByID("-".$args[0]."-");
                        if($server instanceof MTServer)
                        {
                            $server->addPlayer($player);
                        }
                        else
                        {
                            $player->sendMessage("§7[§6Minetox§7] §cDieser Server wurde nicht gefunden");
                            return true;
                        }
                    }
                }
                break;
            case 'register':
                if(!$this->getAccountManager()->isPlayerRegistered($player->getName()))
                {
                    if(isset($args[0]))
                    {
                        if(isset($args[1]))
                        {
                            $passwort1 = $args[0];
                            if($passwort1 === $args[1])
                            {
                                if(!$passwort1 < 3)
                                {
                                    $player->sendMessage("§7[§6Minetox§7] §aDu hast dich erfolgreich registriert");
                                    $this->getAccountManager()->registerPlayer($player->getName(), $args[1]);
                                    $player->auth = true;
                                }
                                else
                                    $player->sendMessage("§7[§6Minetox§7] §cDein Passwort ist zu kurz");
                            }
                            else
                                $player->sendMessage("§7[§6Minetox§7] §cDie Passwörter stimmen nicht überein");
                        }
                        else
                            $player->sendMessage("§4>> /register <passwort> <passwort>");
                    }
                    else
                        $player->sendMessage("§4>> /register <passwort> <passwort>");
                }
                else
                    $player->sendMessage("§7[§6Minetox§7] §cDu bist bereits registriert");
                break;
            case 'login':
                if($this->getAccountManager()->isPlayerRegistered($player->getName()))
                {
                    if(!$player->auth)
                    {
                        if(isset($args[0]))
                        {
                            if($args[0] === $this->getAccountManager()->getPasswort($player->getName()))
                            {
                                $player->sendMessage("§7[§6Minetox§7] §aDu hast dich erfolgreich eingeloggt");
                                $player->auth = true;
                            }
                            else
                                $player->sendMessage("§7[§6Minetox§7] §cDein Passwort ist falsch");
                        }
                        else
                            $player->sendMessage(">> /login <passwort>");
                    }
                    else
                        $player->sendMessage("§7[§6Minetox§7] §cDu bist bereits eingeloggt");
                }
                else
                    $player->sendMessage("§7[§6Minetox§7] §cDu musst dich zuerst registrieren");
                break;
            case "promote":
                if($player instanceof Player)
                {
                    if($player->rank >= 4)
                    {
                        if(isset($args[0]) and isset($args[1]))
                        {
                            $target = $args[0];
                            $rank = $args[1];
                            $Rank = MtUtility::rankToString($rank, true);
                            if($this->getAccountManager()->isPlayerRegistered($target))
                            {
                                if($player->rank === 5)
                                {
                                    if($rank <= 3)
                                    {
                                        $this->promotePlayer($target, $rank);
                                        $player->sendMessage(">> ".$target." wurde zum ".$Rank." befördert");
                                    }
                                    else
                                        $player->sendMessage("§4>> Du bist nicht berechtigt ".$target." zum ".$Rank." zu befördern");
                                }
                                elseif($player->rank === 6) //Nur für Sulfatezz B|
                                {
                                    $this->promotePlayer($target, $rank);
                                    $player->sendMessage(">> ".$rank." wurde zum ".$Rank." befördert");
                                }
                            }
                            else
                                $player->sendMessage("§4>> Spieler ".$target." wurde nicht gefunden");
                        }
                        else
                            $player->sendMessage("§4Command Usage: /promote <name> <level>");
                    }
                    else
                    {
                        $player->sendMessage("§4>> Du bist nicht Berechtigung diesen Befehl auszuführen");
                    }
                }
                else
                {
                    if(isset($args[0]) and isset($args[1]))
                    {
                        $Rank = MtUtility::rankToString($args[1], true);
                        $target = $args[0];
                        $rank = $args[1];
                        if($this->getAccountManager()->isPlayerRegistered($target))
                        {
                            $this->promotePlayer($target, $rank);
                            $player->sendMessage("§e>> ".$target." wurde zum ".$Rank." §ebefördert");
                        }
                        else
                            $player->sendMessage("§4>> Spieler wurde nicht gefunden");
                    }
                    else
                        $player->sendMessage("Command Usage: /promote <name> <level>");
                }
                break;
            case "party":
                /*
                if($player->auth)
                {
                    if(isset($args[0]))
                    {
                        switch($args[0])
                        {
                            case 'add':
                                if(isset($args[1]))
                                {
                                    if($this->getServer()->getPlayer($args[1]) != false)
                                    {
                                        $player->sendMessage("[Party] Spieler ". $args[1] ." ist nicht Online");
                                        //$player->party = [];
                                    }
                                    else
                                    {
                                        $player->sendMessage("[Party] Spieler ". $args[1] ." ist nicht Online");
                                    }
                                }
                                else
                                {
                                    $player->sendMessage("[Party] Ungültiger Name");
                                }
                                break;
                            case 'kick':
                                $player->sendMessage("[Party] Kommt bald.");
                                break;
                            case 'leave':
                                $player->sendMessage("[Party] Kommt bald.");
                                break;
                            case 'list':
                                $player->sendMessage("[Party] Kommt bald.");
                                break;
                            default:
                                $player->sendMessage("[Party] Hilfe für Partys");
                                $player->sendMessage("[Party] /party add <Spieler> - fügt einen Spieler zu deiner Party hinzu");
                                $player->sendMessage("[Party] /party kick <Spieler> - Kickt einen Spieler aus der Party");
                                $player->sendMessage("[Party] /party leave - Verlässt die aktuelle Party");
                                $player->sendMessage("[Party] /party list - Listet alle Party Mitglieder auf");
                                break;
                        }
                    }
                    else
                    {
                        $player->sendMessage("[Party] Hilfe für Partys");
                        $player->sendMessage("[Party] /party add <Spieler> - fügt einen Spieler zu deiner Party hinzu");
                        $player->sendMessage("[Party] /party kick <Spieler> - Kickt einen Spielr aus der Party");
                        $player->sendMessage("[Party] /party list - Listet alle Party Mitglieder auf");
                        $player->sendMessage("[Party] /party leave - Verlässt die aktuelle Party");
                    }
                }
                */
                break;
            case "hub":
                $current = $this->getServerManager()->getServerByID($player->minetoxServer);
                if($current instanceof MTServer)
                {
                    if($current->getType() === 2)
                    {
                        $current->checkAlive();
                        $current->onArenaLeave();
                        $current->removePlayer($player);
                    }
                    else
                        $player->sendMessage("§7[§6Minetox§7] §eDu bist bereits in der Lobby.");
                }
                else
                {
                    $player->sendMessage("§7=================================================");
                    $player->sendMessage("§4[Error] §eEin Fehler ist aufgetreten. ID: #167");
                    $player->sendMessage("§4[Error] §eWenn dieser Fehler öfters auftritt,");
                    $player->sendMessage("§4[Error] §emelde dich bitte bei einem Teammitglied");
                    $player->sendMessage("§7==================================================");
                }
                break;
            case "start":
                if($player instanceof Player)
                {
                    $server = $this->getServerManager()->getServerByID($player->minetoxServer);
                    if($player->rank >= 3)
                    {
                        if($server instanceof MTMinigame and !$server->isPreparing)
                        {
                            $player->sendMessage("§7[§6Minetox§7] §eDiese Runde wird gestartet");
                            $server->boostTimer();
                        }
                        else
                            $player->sendMessage("§7[§6Minetox§7] §eDiese Runde läuft bereits");
                    }
                    else
                    {
                        $player->sendMessage("§4>> Du hast keine Berechtigung für diesen Befehl");
                    }
                }
                else
                {
                    if(isset($args[0]))
                    {
                        $server = $this->getServerManager()->getServerByID($args[0]);
                        if($server instanceof MTMinigame and !$server->isPreparing)
                        {
                            $player->sendMessage("§7[§6Minetox§7] §eDiese Runde wird gestartet");
                            $server->boostTimer();
                        }
                    }
                }
                break;
            case "stats":
                if(isset($args[0]))
                {
                    $server = $this->getServerManager()->getServerByID($player->minetoxServer);
                    if($server->getType() === 2)
                        $server->getOwner()->sendStats($player, $args[0]);
                    else
                    {
                        $data = $this->getAccountManager()->getPlayer($args[0]);
                        if($data != null)
                        {
                            $player->sendMessage(">> Name: " . $args[0]);
                            $player->sendMessage(">> Rang: ".$this->getAccountManager()->getRank($player->getName()));
                            $player->sendMessage(">> Tokens: " .$this->getAccountManager()->getTokens($player->getName()));
                        }
                        else
                        {
                            $player->sendMessage(">> Name: " . $args[0]);
                            $player->sendMessage(">> Rang: Member");
                            $player->sendMessage(">> Tokens: 100");
                        }
                    }
                }
                else
                {
                    $server = $this->getServerManager()->getServerByID($player->minetoxServer);
                    if($server->getType() === 2)
                        $server->getOwner()->sendStats($player, $player->getName());
                    else
                    {
                        $player->sendMessage(">> Name: " . $player->getName());
                        $player->sendMessage(">> Rang: ".$this->getAccountManager()->getRank($player->getName()));
                        $player->sendMessage(">> Tokens: " . $this->getAccountManager()->getTokens($player->getName()));
                    }
                }
                break;
            case "server":
                if(isset($args[0]) and $player->isOp())
                {
                    $server = $this->getServerManager()->getServerByID("-".$args[0]."-");
                    if($server instanceof MTServer)
                    {
                        if($server->getState() != 2)
                        {
                            $server->setState(2);
                            $player->sendMessage("§7[§6Minetox§7] §9".$server->getName()." §ewurde pausiert");
                        }
                        else
                        {
                            $server->setState(0);
                            $player->sendMessage("§7[§6Minetox§7] §9".$server->getName()." §ewurde aktiviert");
                        }
                    }
                    else
                        $player->sendMessage("§7[§6Minetox§7] §eDieser Server wurde nicht gefunden");
                }
                else
                    $player->sendMessage("§4>> /server <server> <start/stop>");
                break;
            case "nick":
                if($player->rank >= 3)
                {
                    $server = $this->getServerManager()->getServerByID($player->minetoxServer);
                    if($server->getType() === 2)
                    {
                        if(!isset($args[0]))
                        {
                            if(!$player->isNicked)
                            {
                                if(!$server->hasStarted)
                                {
                                    MTUtility::nickPlayer($player, true);
                                }
                                else
                                {
                                    $player->sendMessage("§7[§5NICK§7] §4Du kannst dich nicht während dem Spiel nicken");
                                }
                            }
                            else
                            {
                                if(!$server->hasStarted)
                                {
                                    MTUtility::unNickPlayer($player, true);
                                }
                                else
                                {
                                    $player->sendMessage("§7[§5NICK§7] §4Du kannst dich nicht während der Runde nicken");
                                }
                            }
                        }
                        else
                        {
                            if($args[0] === "on")
                            {
                                $player->sendMessage("§7[§5NICK§7] §eDu wirst nun Automatisch genickt!");
                                $this->getAccountManager()->setAutoNick($player->getName(), 1);
                            }
                            elseif($args[0] === "off")
                            {
                                $player->sendMessage("§7[§5NICK§7] §eDu wirst nicht mehr Automatisch genickt!");
                                $this->getAccountManager()->setAutoNick($player->getName(), 0);
                            }
                        }
                    }
                    else
                        $player->sendMessage("§7[§6Minetox§7] §eDu kannst dich nicht auf diesem Server nicken");
                }
                else
                {
                    $player->sendMessage("§7[§6Minetox§7] §eDu hast keine Berechtigung für diesen Befehl");
                }
                break;
            case "getpos":
                if($player instanceof player)
                {
                    if($player->auth)
                    {
                        $player->sendMessage("§3>> x: " .round($player->getX()));
                        $player->sendMessage("§3>> y: " .round($player->getY()));
                        $player->sendMessage("§3>> z: " .round($player->getZ()));
                        $player->sendMessage("§3>> Server: ". $player->minetoxServer);
                    }
                }
                break;
        }
        return false;
    }
}