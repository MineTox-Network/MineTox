<?php

namespace SurvivalGames;

use pocketmine\minetox\MTUtility;
use pocketmine\plugin\PluginBase;
use SurvivalGames\Arena\Arena;
use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\Player;


class SurvivalGames extends PluginBase
{
    private $arenas = [];

    public $database;

    public function onEnable()
    {
        $this->getServer()->loadLevel("SurvivalGames1");

        $this->registerServer(1, 2, 108, -334, $this->getServer()->getLevelbyName("SurvivalGames1"));

        $this->arenas[1]->setArenaData("displayname", "SurvivalGames4");
        $this->arenas[1]->setArenaData("required_Players", 1);
        $this->arenas[1]->setArenaData("creator", "TeamVareide");
        $this->arenas[1]->setArenaData("name", "SurvivalGames1");

        $this->arenas[1]->setArenaData("arena_spawns",
        [
            new Vector3(0, 31, -16), new Vector3(0, 31, 17), new Vector3(17, 31, 0), new Vector3(-16, 31, 0),
            new Vector3(-11, 31, 12), new Vector3(12, 31, 12), new Vector3(12, 31, -11), new Vector3(-11, 31, -11),
            new Vector3(17, 31, -3), new Vector3(-7, 31, 15), new Vector3(-8, 31, 15), new Vector3(-3, 31, 17),
            new Vector3(14, 31, 8), new Vector3(-14, 31, -7), new Vector3(-3, 31, -16), new Vector3(-16, 31, -3),
            new Vector3(-16, 31, 4), new Vector3(4, 31, 17), new Vector3(15, 31, 8), new Vector3(15, 31, -7),
            new Vector3(8, 31, -14), new Vector3(4, 31 , -16), new Vector3(-7, 31, -14), new Vector3(17, 31, 4)
        ]);

        $this->arenas[1]->setArenaData("dm_spawns1",
        [
            new Vector3(267, 62, -301), new Vector3(267, 62, -255), new Vector3(290, 62, -278),
            new Vector3(244, 62, -278), new Vector3(251, 62, -262), new Vector3(283, 62, -262),
            new Vector3(283, 62, -294), new Vector3(251, 62, -294)
        ]);

        $this->createMySQLConnection();
    }

    public function registerServer($id, $x, $y, $z, Level $level, $build = false)
    {
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

    public function registerPlayer($player)
    {
        $name = trim(strtolower($player));
        $data =
        [
            "punkte" => 0,
            "kills" => 0,
            "tode" => 0,
            "siege" => 0,
        ];

        $this->getDatabase()->query
        (
            "INSERT INTO minetox_survivalgames
            (name, punkte, kills, tode, siege)
            VALUES
            ('".$this->getDatabase()->escape_string($name)."', ".$data["punkte"].", ".$data["kills"].", ".$data["tode"].", ".$data["siege"].")"
        );
        $this->getLogger()->Info("Ein neuer Spieler wurde registriert: ". $player);
        return $data;
    }

    /**
     * @param $player
     * @return array|null
     */
    public function getPlayer($player)
    {
        $result = $this->getDatabase()->query
        (
            "SELECT * FROM minetox_survivalgames WHERE name = '" . $this->getDatabase()->escape_string(trim(strtolower($player)))."'"
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

    /**
     * @param $name
     * @return bool
     */
    public function isPlayerRegistered($name)
    {
        return $this->getPlayer($name) !== null;
    }

    /**
     * @param $player
     * @param int $points
     */
    public function addPoints($player, $points = 20)
    {
        $this->getDatabase()->query
        (
            "UPDATE minetox_survivalgames SET points = points+'".$points."' WHERE name = '".$this->getDatabase()->escape_string(trim(strtolower($player)))."'"
        );
    }

    /**
     * @param $player
     * @param int $kills
     */
    public function addKill($player, $kills = 1)
    {
        $this->getDatabase()->query
        (
            "UPDATE minetox_survivalgames SET kills = kills+'".$kills."' WHERE name = '".$this->getDatabase()->escape_string(trim(strtolower($player)))."'"
        );
    }

    /**
     * @param $player
     * @param int $deaths
     */
    public function addDeath($player, $deaths = 1)
    {
        $this->getDatabase()->query
        (
            "UPDATE minetox_survivalgames SET tode = tode+'".$deaths."' WHERE name = '".$this->getDatabase()->escape_string(trim(strtolower($player)))."'"
        );
    }

    /**
     * @param $player
     * @param int $wins
     */
    public function addWin($player, $wins = 1)
    {
        $this->getDatabase()->query
        (
            "UPDATE minetox_survivalgames SET siege = siege+'".$wins."' WHERE name = '".$this->getDatabase()->escape_string(trim(strtolower($player)))."'"
        );
    }

    /**
     * @param Player $player
     * @param $target
     */
    public function sendStats(Player $player, $target)
    {
        $data = $this->getPlayer($target);
        if($data != null)
        {
            $player->sendMessage("==============================================");
            $player->sendMessage(">> SurvivalGames Statistiken für ".$target);
            $player->sendMessage(">> Gewonnene Spiele: ".$data["siege"]);
            $player->sendMessage(">> Kills: ".$data["kills"]);
            $player->sendMessage(">> Tode: ".$data["tode"]);
            $player->sendMessage(">> K/D: ".round($data["kills"]/$data["tode"], 3));
            $player->sendMessage("==============================================");
        }
        else
        {
            $player->sendMessage("==============================================");
            $player->sendMessage(">> SurvivalGames Statistiken für ".$target);
            $player->sendMessage(">> Siege: -");
            $player->sendMessage(">> Kills: -");
            $player->sendMessage(">> Tode: -");
            $player->sendMessage(">> K/D: -");
            $player->sendMessage("==============================================");
        }
    }

    /**
     * @return Arena[]
     */
    public function getArenas()
    {
        return $this->arenas;
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
}