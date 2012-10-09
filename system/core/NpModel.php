<?php
class NpModel {
	private $cache;
	private $database;
	
	public function getCache() {
		if($this->cache===null)
			$this->cache=NpFactory::createCache();
		return $this->cache;
	}

	public function getDatabase() {
		if($this->database===null)
			$this->database=NpFactory::createDatabase();
		return $this->database;
	}
}
?>