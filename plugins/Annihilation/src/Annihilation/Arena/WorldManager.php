<?php

namespace Annihilation\Arena;

use Annihilation\Arena\Arena;

class WorldManager{
    
    public function addWorld($worldname) {
		$source = Server::getInstance ()->getDataPath () . "worlds/annihilation" . $worldname . "/";
		$dest = Server::getInstance ()->getDataPath () . "worlds/" . $worldname . "/";		
		
		$count = 0;
		
		if ($this->xcopy ( $source, $dest )) {
			try {
				Server::getInstance ()->loadLevel ( $worldname );
			} catch ( \Exception $e ) {
				$this->log ( "level loading error: " . $e->getMessage () );
			}
						
			Server::getInstance ()->loadLevel ( $worldname );
			$level = Server::getInstance ()->getLevelByName ( $worldname );
		}
	}
        
        public function deleteWorld($worldname) {
		// delete folder
		$levelpath = Server::getInstance ()->getDataPath () . "worlds/" . $worldname . "/";
		$fileutil = new FileUtil ();
		$fileutil->unlinkRecursive ( $levelpath, true );
	}
        
    function xcopy($source, $dest, $permissions = 0755) {
		// Check for symlinks
		if (is_link ( $source )) {
			return symlink ( readlink ( $source ), $dest );
		}
		
		// Simple copy for a file
		if (is_file ( $source )) {
			return copy ( $source, $dest );
		}
		
		// Make destination directory
		if (! is_dir ( $dest )) {
			mkdir ( $dest, $permissions );
		}
		
		// Loop through the folder
		$dir = dir ( $source );
		while ( false !== $entry = $dir->read () ) {
			// Skip pointers
			if ($entry == '.' || $entry == '..') {
				continue;
			}
			
			// Deep copy directories
			$this->xcopy ( "$source/$entry", "$dest/$entry", $permissions );
		}
		
		// Clean up
		$dir->close ();
		return true;
	}
	
	public function unlinkRecursive($dir, $deleteRootToo) {
		if (! $dh = @opendir ( $dir )) {
			return;
		}
		while ( false !== ($obj = readdir ( $dh )) ) {
			if ($obj == '.' || $obj == '..') {
				continue;
			}
			
			if (! @unlink ( $dir . '/' . $obj )) {
				$this->unlinkRecursive ( $dir . '/' . $obj, true );
			}
		}
		
		closedir ( $dh );
		
		if ($deleteRootToo) {
			@rmdir ( $dir );
		}
		
		return;
	}
        
        public function resetWorld($world){
            $this->deleteWorld($world);
            $this->addWorld($world);
        }
}

