<?php
require_once('Cache.php');

class CcMemcache implements Cache {
	private $cache;
	
	private function connect($host, $port) {
		$this->cache=new Memcache();
		return $this->cache->pconnect($host,$port);
	}
	
	public function get($key) {
		return $this->cache->get($key);	
	}
	
	public function set($key,$value) {
		return $this->cache->set($key, $value);
	}
	
	public function set($key,$value,$timeout) {
		return $this->cache->set($key, $value, 0, $timeout);
	}
	
	public function delete($key) {
		return $this->cache->delete($key);
	}
	
	public function close() {
		return $this->cache->close();
	}
	
	public static function getInstance($host, $port) {
		$instance=new CcMemcache();
		if(!$instance->connect($host, $port))
			throw new Exception('Memcached cannot connect');
		return $instance;
	}
}
?>