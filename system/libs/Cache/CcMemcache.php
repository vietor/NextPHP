<?php
require_once('Cache.php');

class CcMemcache implements Cache {
	private $cache;

	public function __destruct() {
		if(!is_null($this->cache)) {
			$this->cache->close();
			$this->cache=null;
		}
	}

	private function connect($host, $port) {
		$this->cache=new Memcache();
		return $this->cache->pconnect($host,$port);
	}

	public function get($key) {
		return $this->cache->get($key);
	}

	public function set($key,$value,$timeout=0) {
		if($timeout==0)
			return $this->cache->set($key, $value);
		return $this->cache->set($key, $value, 0, $timeout);
	}

	public function delete($key) {
		return $this->cache->delete($key);
	}

	public static function getInstance($host, $port) {
		$instance=new CcMemcache();
		if(!$instance->connect($host, $port))
			throw new Exception('Memcached cannot connect');
		return $instance;
	}
}
?>