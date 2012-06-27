<?php
class Model {
	private $cache;
	private $database;
	
	public function getCache() {
		if($this->cache===null)
			$this->cache=CoreLoader::loadCache();
		return $this->cache;
	}

	public function getDatabase() {
		if($this->database===null)
			$this->database=CoreLoader::loadDatabase();
		return $this->database;
	}
}
?>