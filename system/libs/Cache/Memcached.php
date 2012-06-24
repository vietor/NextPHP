<?php
require_once('Cache.php');

class Memcached implements Cache {
	private $cache;
	
	public function connect($host, $port) {
		$this->cache=new Memcache;
		return $this->cache->connect($host,$port);
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
	
	public function remove($key) {
		return $this->cache->delete($key);
	}
	
	public function close() {
		return $this->cache->close();
	}
}
?>