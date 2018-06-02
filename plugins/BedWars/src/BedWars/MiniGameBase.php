<?php

namespace BedWars\Arena;

abstract class MiniGameBase {		
	protected $plugin;
	public function __construct(BedWars $plugin) {
		if($plugin === null){
			throw new \InvalidStateException("plugin may not be null");
		}
		$this->plugin = $plugin;
	}
	protected function getManager() {
		return $this->plugin->ctfManager;
	}
	protected function getPlugin() {
		return $this->plugin;
	}
	protected function getMsg($key) {
		return $this->plugin->ctfMessages->getMessageByKey ( $key );
	}
	protected function getSetup() {
		return $this->plugin->ctfSetup;
	}
	protected function getBuilder() {
		return $this->plugin->ctfBuilder;
	}
	protected function getShop() {
            return $this->plugin->ctfShop;
        }
	
	protected function getLog() {
		return $this->plugin->getLogger();
	}
	
	protected function log($msg) {
		return $this->plugin->getLogger()->info($msg);
	}
	
	protected function getConfig($key, $defaultValue=null) {
		return $this->plugin->getConfig ()->get ( $key, $defaultValue);
	}
	
	protected function setConfig($key, $value) {
		return $this->plugin->getConfig ()->set ( $key, $value );
	}
}