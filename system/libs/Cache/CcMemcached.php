<?php
require_once('Cache.php');

class CcMemcached implements Cache {
	private $cache;
	
	private function connect($host, $port) {
		$this->cache=new Memcached();
		return $this->cache->addServer($host,$port);
	}
	
	public function get($key) {
		return $this->cache->get($key);	
	}
	
	public function set($key,$value) {
		return $this->cache->set($key, $value);
	}
	
	public function set($key,$value,$timeout) {
		return $this->cache->set($key, $value, $timeout);
	}
	
	public function delete($key) {
		return $this->cache->delete($key);
	}
	
	public function close() {
	}
	
	private static $instance;
	
	public static function getInstance($host, $port) {
		if(!is_null(self::$instance))
			return self::$instance;
		$instance=new CcMemcached();
		if(!$instance->connect($host, $port))
			throw new Exception('Memcached cannot connect');
		else
			self::$instance=$instance;
		return self::$instance;
	}
}
?>