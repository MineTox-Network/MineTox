<?php

makeServer();

function makeServer()
{
try
{
	$pharPath = "PocketMine-MP.phar";
    if(file_exists($pharPath))
	{
        echo "Phar file already exists, overwriting...\n";
        @unlink($pharPath);
    }
    $phar = new \Phar($pharPath);
    $phar->setMetadata([
        "name" => "PocketMine-MP",
        "version" => "1",
        "api" => "1.10.0",
        "minecraft" => "0.11.0",
        "protocol" => "21",
        "creationDate" => time()
    ]);
    $phar->setStub('<?php define("pocketmine\\\\PATH", "phar://". __FILE__ ."/"); require_once("phar://". __FILE__ ."/src/pocketmine/PocketMine.php");  __HALT_COMPILER();');
    $phar->setSignatureAlgorithm(\Phar::SHA1);
    $phar->startBuffering();
	$currentDir = dirname(__FILE__);
    $filePath = substr("$currentDir", 0, 7) === "phar://" ? "$currentDir" : "$currentDir" . "/";
    $filePath = rtrim(str_replace("\\", "/", $filePath), "/") . "/";
	$int = 0;
	$lastPercent = 0;
    foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator( $filePath."src")) as $file)
	{
		$int++;
        $path = ltrim(str_replace(["\\", $filePath], ["/", ""], $file), "/");
        if($path{0} === "." or strpos($path, "/.") !== false or substr($path, 0, 4) !== "src/")
            continue;
        $phar->addFile($file, $path);
        //echo " Adding $path.\n";
		$percent = round($int/(1066/100));
		if($percent != $lastPercent)
		     echo "Creating Phar ".$percent."/100%\n";
		$lastPercent = $percent;
    }
    foreach($phar as $file => $finfo)
        /** @var \PharFileInfo $finfo */
        if($finfo->getSize() > (1024 * 512))
            $finfo->compress(\Phar::GZ);
    $phar->stopBuffering();
		echo "Phar has been created \n";
		echo "Phar includes  ".$int." Files\n";
	sleep(10);
		echo "Window will be closed in 20 seconds\n";
	sleep(5);
		echo "Window will be closed in 10 seconds\n";
	sleep(1);
		echo "Window will be closed in 5 seconds\n";
	sleep(1);
		echo "Window will be closed in 4 seconds\n";
	sleep(1);
		echo "Window will be closed in 3 seconds\n";
	sleep(1);
		echo "Window will be closed in 2 seconds\n";
	sleep(1);
		echo "Window will be closed in 1 seconds\n";
	sleep(1);
		echo "Window will be closed in 0 seconds!\n";
		echo "\n";
		echo "BYE!\n;";
	sleep(1);
    return true;
}
catch(\exception $e)
{
	echo "Es konnte keine Phar erstellt werden.";
	echo "Fehler: ".$e;
	sleep(500);
}
}