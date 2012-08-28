<?php
require_once('Cache.php');

class CcRedis implements Cache {
	private $cache;
	private $prefix;

	public function __construct($prefix) {
		$this->prefix=$prefix;
	}

	public function __destruct() {
		if(!is_null($this->cache)) {
			$this->cache->close();
			$this->cache=null;
		}
	}

	private function connect($host, $port) {
		$this->cache=new Redis();
		return $this->cache->pconnect($host,$port);
	}

	public function get($key) {
		return $this->cache->get($this->prefix.$key);
	}

	public function set($key,$value,$timeout) {
		if($timeout==0)
			return $this->cache->set($this->prefix.$key, $value);
		return $this->cache->setex($this->prefix.$key, $timeout, $value);
	}

	public function delete($key) {
		return $this->cache->delete($this->prefix.$key);
	}

	public static function getInstance($host, $port, $prefix) {
		$instance=new CcRedis($prefix);
		if(!$instance->connect($host, $port))
			throw new Exception('Redis cannot connect');
		return $instance;
	}
}
?>