<?php
class Model {
	private $cache;
	private $database;
	
	public function getCache() {
		if($this->cache===null)
			$this->cache=Loader::loadCache();
		return $this->cache;
	}

	public function getDatabase() {
		if($this->database===null)
			$this->database=Loader::loadDatabase();
		return $this->database;
	}
}
?>