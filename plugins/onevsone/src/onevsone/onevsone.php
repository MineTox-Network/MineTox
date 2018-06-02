<?php

namespace onevsone;

use onevsone\Arena\ArenaSchedule;
use pocketmine\plugin\PluginBase;
use pocketmine\level\Level;
use pocketmine\utils\TextFormat ;
use onevsone\Arena\Server;


class onevsone extends PluginBase
{
    private $arenas = [];
    private $servers = [];
    public $database = false;

    public function onEnable()
    {
        $this->getServer()->loadLevel("onevsone1");

        $this->registerServer(1, 0, 0, 0, $this->getServer()->getLevelbyName("onevsone1"));

        $this->getServer()->getPluginManager()->getPlugin("MTCore")->getServerManager()->addServer($this->arenas[1]);

        $this->getServer()->getScheduler()->scheduleRepeatingTask(new ArenaSchedule($this), 20);

        foreach($this->getArenas() as $arena)
        {
            $this->getServer()->getPluginManager()->registerEvents($arena, $this);
        }

        $this->database = new \mysqli("85.10.205.173", "minetoxserver", "5k30^/#$2xn82T[5|[_U577@/xO22=6}ucp>o7n1U:P?:MImg4=X<EFuc45Mt+c", "minetox", 3306);

        if($this->getDatabase()->connect_error)
        {
            $this->getLogger()->critical("Es konnte keine Verbindung zum MySQL hergestellt werden: ". $this->getDatabase()->connect_error);
        }
        else
        {
            $this->getLogger()->info(TextFormat::GREEN . "Connected to the" . TextFormat::BLUE . " MySQL Server!");
        }
        $resource = $this->getResource("mysql.sql");
        $this->getDatabase()->query(stream_get_contents($resource));
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new MySQLPingTask($this), 600); //Alle 30 Sekunden
        fclose($resource);
    }

    public function registerServer($id, $x, $y, $z, Level $level)
    {
        $this->arenas[$id] = new Server($id, $this);
        $this->arenas[$id]->setArenaData("x", $x);
        $this->arenas[$id]->setArenaData("y", $y);
        $this->arenas[$id]->setArenaData("z", $z);
        $this->arenas[$id]->setArenaData("level", $level);
        $this->arenas[$id]->setArenaData("displayname", "Server ".$id);

        $level->setTime(1000);
        $level->stopTime();
    }

    /**
     * @param $player
     * @return array|null
     */
    public function getPlayer($player)
    {
        $result = $this->getDatabase()->query("SELECT * FROM minetox_onevsone WHERE name = '" . $this->getDatabase()->escape_string(trim(strtolower($player)))."'");

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
     * @param Player $player
     * @param $target
     */
    public function sendStats(Player $player, $target)
    {
        $data = $this->getPlayer($target);
        if($data != null)
        {
            $player->sendMessage("==============================================");
            $player->sendMessage(">> 1vs1 Statistiken für ".$target);
            $player->sendMessage(">> Spiele: ".$data["played_games"]);
            $player->sendMessage(">> Siege: ".$data["wins"]);
            $player->sendMessage(">> K/D: ".$data["kills"]/$data["tode"]);
            $player->sendMessage(">> Tox: ".$data["tox"]);
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
	
    public function getArenas()
    {
        return $this->arenas;
    }
}