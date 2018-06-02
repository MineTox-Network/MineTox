@echo off
TITLE PocketMine-MP server software for Minecraft: Pocket Edition
cd /d %~dp0
if exist win\php\php.exe (
	set PHP_BINARY=win\php\php.exe
) else (
	set PHP_BINARY=php
)

if exist PocketMine-MP.phar (
	set POCKETMINE_FILE=PocketMine-MP.phar
) else (
	if exist src\pocketmine\PocketMine.php (
		set POCKETMINE_FILE=src\pocketmine\PocketMine.php
	) else (
		echo "Couldn't find a valid PocketMine-MP installation"
		pause
		exit 1
	)
)

REM if exist win\php\php_wxwidgets.dll (
REM 	%PHP_BINARY% %POCKETMINE_FILE% --enable-gui %*
REM ) else (
	if exist win\mintty.exe (
		start "" win\mintty.exe -o Columns=88 -o Rows=32 -o AllowBlinking=0 -o FontQuality=3 -o Font="DejaVu Sans Mono" -o FontHeight=10 -o CursorType=0 -o CursorBlinks=1 -h error -t "PocketMine-MP" -i win/pocketmine.ico -w max %PHP_BINARY% %POCKETMINE_FILE% --enable-ansi %*
	) else (
		%PHP_BINARY% -c win\php %POCKETMINE_FILE% %*
	)
REM )
