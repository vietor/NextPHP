<?php
class Model {
	public function getCache() {
		return LibLoader::loadCache();
	}
	
	public function getDatabase() {
		return LibLoader::loadDatabase();
	}
}
?>