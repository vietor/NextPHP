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
		$key=$this->prefix.$key;
		$result=$this->cache->increment($key, $value);
		if(!$result) {
			if($this->cache->set($key, $value))
				$result=$value;
		}
		return $result;
	}

	public function dec($key, $value=1)
	{
		$key=$this->prefix.$key;
		$result=$this->cache->decrement($key, $value);
		if(!$result) {
			if($this->cache->set($key, 0-$value))
				$result=0-$value;
		}
		return $result;
	}

	public function get($key)
	{
		$key=$this->prefix.$key;
		return $this->cache->get($key);
	}

	public function set($key,$value,$timeout=0)
	{
		$key=$this->prefix.$key;
		if($timeout==0){
			$timeout=$this->timeout;
			if($timeout==0)
				return $this->cache->set($key, $value);
		}
		return $this->cache->set($key, $value, $timeout);
	}

	public function delete($key)
	{
		$key=$this->prefix.$key;
		return $this->cache->delete($key);
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