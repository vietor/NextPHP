<?php
require_once('Cache.php');

class CcMemcached implements Cache {
	private $cache;
	private $prefix;

	public function __construct($prefix) {
		$this->prefix=$prefix;
	}

	private function connect($host, $port) {
		$this->cache=new Memcached();
		return $this->cache->addServer($host,$port);
	}

	public function get($key) {
		return $this->cache->get($this->prefix.$key);
	}

	public function set($key,$value,$timeout) {
		if($timeout==0)
			return $this->cache->set($this->prefix.$key, $value);
		return $this->cache->set($this->prefix.$key, $value, $timeout);
	}

	public function delete($key) {
		return $this->cache->delete($this->prefix.$key);
	}

	private static $instance;

	public static function getInstance($host, $port, $prefix) {
		if(!is_null(self::$instance))
			return self::$instance;
		$instance=new CcMemcached($prefix);
		if(!$instance->connect($host, $port))
			throw new Exception('Memcached cannot connect');
		else
			self::$instance=$instance;
		return self::$instance;
	}
}
?>