<?php

/*
*  _____                              __  __   _                  _
* |_   _|   ___    __ _   _ __ ___   |  \/  | (_)  _ __     ___  | |_    ___   __  __
*   | |    / _ \  / _` | | '_ ` _ \  | |\/| | | | | '_ \   / _ \ | __|  / _ \  \ \/ /
*   | |   |  __/ | (_| | | | | | | | | |  | | | | | | | | |  __/ | |_  | (_) |  >  <
*   |_|    \___|  \__,_| |_| |_| |_| |_|  |_| |_| |_| |_|  \___|  \__|  \___/  /_/\_\
*
*/

namespace QuickSG;

use pocketmine\minetox\MTUtility;
use pocketmine\plugin\PluginBase;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use QuickSG\Arena\Arena;
use pocketmine\Player;


class QuickSG extends PluginBase
{
    public $database;

    private $arenas = [];

    public function onEnable()
    {
        $this->getServer()->loadLevel("QuickSG1");
        $this->getServer()->loadLevel("QuickSG2");
        $this->getServer()->loadLevel("QuickSG3");

        $this->registerServer(1, 1596, 56, 102, $this->getServer()->getLevelbyName("QuickSG1"));
        $this->registerServer(2, -1041, 57, -743, $this->getServer()->getLevelbyName("QuickSG2"));
        $this->registerServer(3, -453, 47, -1215, $this->getServer()->getLevelbyName("QuickSG3"));

        $this->arenas[1]->setArenaData("displayname", "Hijacked");
        $this->arenas[1]->setArenaData("required_Players", 1);
        $this->arenas[1]->setArenaData("creator", "N11ck");
        $this->arenas[1]->setArenaData("name", "QuickSG1");

        $this->arenas[2]->setArenaData("displayname", "Raid");
        $this->arenas[2]->setArenaData("required_Players", 1);
        $this->arenas[2]->setArenaData("creator", "TeamMinetox");
        $this->arenas[2]->setArenaData("name", "QuickSG2");

        $this->arenas[3]->setArenaData("displayname", "ClayTown");
        $this->arenas[3]->setArenaData("required_Players", 1);
        $this->arenas[3]->setArenaData("creator", "F3lice");
        $this->arenas[3]->setArenaData("name", "QuickSG3");

        $this->arenas[1]->setArenaData("arena_spawns",
        [
            new Vector3(1398, 114, -92), new Vector3(1385, 114, -91), new Vector3(1365, 118, -92), new Vector3(1359, 114, -79),
            new Vector3(1338, 114, -92), new Vector3(1272, 108, -92), new Vector3(1295, 110, -91), new Vector3(1331, 113, -108),
            new Vector3(1313, 116, -87), new Vector3(1359, 118, -88), new Vector3(1336, 107, -93), new Vector3(1366, 114, -89)
        ]);

        $this->arenas[2]->setArenaData("arena_spawns",
        [
            new Vector3(-912, 45, -1037), new Vector3(-930, 45, -1033), new Vector3(-1005, 47, -1057), new Vector3(-981, 47, -1036),
            new Vector3(-1008, 51, -1009), new Vector3(-960, 48, -1059), new Vector3(-1012, 47, -1067), new Vector3(-944, 45, -1039),
            new Vector3(-1063, 47, -1055), new Vector3(-1027, 47, -1073), new Vector3(-1039, 43, -1076), new Vector3(-966, 47, -1044),
        ]);

        $this->arenas[3]->setArenaData("arena_spawns",
        [
            new Vector3(-16, 22, -1124), new Vector3(22, 36, -1271), new Vector3(-19, 27, -1198), new Vector3(47, 25, -1220),
            new Vector3(94, 28, -1190), new Vector3(62, 22, -1140), new Vector3(35, 22, -1130), new Vector3(22, 22, -1125),
            new Vector3(32, 24, -1214), new Vector3(31, 33, -1177), new Vector3(21, 32, -1177), new Vector3(2, 27, -1254)
        ]);


        $this->arenas[1]->setArenaData("dm_spawns1",
        [
            new Vector3(1806, 22, 129), new Vector3(1829, 22, 106), new Vector3(1829, 22, 152),
            new Vector3(1845, 22, 145), new Vector3(1813, 22, 113), new Vector3(1813, 22, 145),
            new Vector3(1845, 22, 113)
        ]);

        $this->arenas[1]->setArenaData("dm_spawns2",
        [
            new Vector3(2081, 20, 15), new Vector3(2067, 20, 8), new Vector3(2060, 20, -6), new Vector3(2067, 20, -20),
            new Vector3(2081, 20, -27), new Vector3(2095, 20, -20), new Vector3(2102, 20, -6), new Vector3(2095, 20, 8)
        ]);

        $this->arenas[2]->setArenaData("dm_spawns1",
        [
            new Vector3(-1283, 56, -749), new Vector3(-1267, 56, -742), new Vector3(-1260, 56, -726),
            new Vector3(-1267, 56, -710), new Vector3(-1283, 56, -703), new Vector3(-1299, 56, -710),
            new Vector3(-1306, 56, -726), new Vector3(-1299, 56, -742)
        ]);

        $this->arenas[2]->setArenaData("dm_spawns2",
        [
            new Vector3(-1205, 21, -990), new Vector3(-1163, 21, -990), new Vector3(-1184, 21, -969),
            new Vector3(-1198, 21, -976), new Vector3(-1198, 21, -1004), new Vector3(-1170, 21, -1004),
            new Vector3(-1170, 21, -976), new Vector3(-1184, 21, 1011)
        ]);

        $this->arenas[3]->setArenaData("dm_spawns1",
        [
            new Vector3(-234, 30, -1090), new Vector3(-241, 30, -1074), new Vector3(-257, 30, -1067),
            new Vector3(-273, 30, -1074), new Vector3(-280, 30, -1090), new Vector3(-273, 30, -1106),
            new Vector3(-257, 30, -1113), new Vector3(-241, 30, -1106),
        ]);

        $this->arenas[3]->setArenaData("dm_spawns2",
        [
            new Vector3(-369, 19, -1126), new Vector3(-376, 19, -1112), new Vector3(-390, 19, -1105),
            new Vector3(-404, 19, -1112), new Vector3(-411, 19, -1126), new Vector3(-404, 19, -1140),
            new Vector3(-390, 19, -1147), new Vector3(-376, 19, -1140)
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

        if($this->getDatabase()->connect_error)
        {
            $this->getLogger()->critical("Es konnte keine Verbindung zum MySQL hergestellt werden: ". $this->getDatabase()->connect_error);
        }
        else
        {
            $this->getLogger()->info("§2Connected to the §3MySQL §2Server!");
            MTUtility::getMTCore()->getMySQLManager()->registerDatabase($database);
            $resource = $this->getResource("mysql.sql");
            $this->getDatabase()->query(stream_get_contents($resource));
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
        $result = $this->getDatabase()->query("SELECT * FROM minetox_survivalgames WHERE name = '" . $this->getDatabase()->escape_string(trim(strtolower($player)))."'");

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
     * @return \mysqli
     */
    public function getDatabase()
    {
        return $this->database;
    }
}