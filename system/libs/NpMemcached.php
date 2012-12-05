<?php
require_once 'NpCache.php';

class NpMemcached implements NpCache
{
	private $cache;
	private $prefix;
	private $timeout;

	public function __construct($prefix,$timeout)
	{
		$this->prefix=$prefix;
		$this->timeout=$timeout;
	}

	private function connect($host, $port)
	{
		$this->cache=new Memcached();
		return $this->cache->addServer($host,$port);
	}

	public function inc($key, $value=1)
	{
		$result=$this->cache->increment($this->prefix.$key, $value);
		if(!$result) {
			if($this->cache->set($this->prefix.$key, $value))
				$result=$value;
		}
		return $result;
	}

	public function dec($key, $value=1)
	{
		$result=$this->cache->decrement($this->prefix.$key, $value);
		if(!$result) {
			if($this->cache->set($this->prefix.$key, 0-$value))
				$result=0-$value;
		}
		return $result;
	}

	public function get($key)
	{
		return $this->cache->get($this->prefix.$key);
	}

	public function set($key,$value,$timeout=0)
	{
		if($timeout==0){
			$timeout=$this->timeout;
			if($timeout==0)
				return $this->cache->set($this->prefix.$key, $value);
		}
		return $this->cache->set($this->prefix.$key, $value, $timeout);
	}

	public function delete($key)
	{
		return $this->cache->delete($this->prefix.$key);
	}

	public static function getInstance($host, $port, $prefix, $timeout)
	{
		$instance=new NpMemcached($prefix,$timeout);
		if(!$instance->connect($host, $port))
			throw new Exception('Memcached cannot connect');
		return $instance;
	}
}
?>