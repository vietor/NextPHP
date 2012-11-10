<?php
require_once 'NpCache.php';

class NpMemcache implements NpCache 
{
	private $cache;
	private $prefix;
	private $timeout;

	public function __construct($prefix,$timeout)
	{
		$this->prefix=$prefix;
		$this->timeout=$timeout;
	}

	public function __destruct()
	{
		if(!is_null($this->cache)) {
			$this->cache->close();
			$this->cache=null;
		}
	}

	private function connect($host, $port)
	{
		$this->cache=new Memcache();
		return $this->cache->pconnect($host,$port);
	}

	public function get($key)
	{
		return $this->cache->get($this->prefix.$key);
	}

	public function set($key,$value,$timeout=0)
	{
		if($timeout==0) {
			$timeout=$this->timeout;
			if($timeout==0)
				return $this->cache->set($this->prefix.$key, $value);
		}
		return $this->cache->set($this->prefix.$key, $value, 0, $timeout);
	}

	public function delete($key)
	{
		return $this->cache->delete($this->prefix.$key);
	}

	public static function getInstance($host, $port, $prefix, $timeout)
	{
		$instance=new NpMemcache($prefix,$timeout);
		if(!$instance->connect($host, $port))
			throw new Exception('Memcached cannot connect');
		return $instance;
	}
}
?>