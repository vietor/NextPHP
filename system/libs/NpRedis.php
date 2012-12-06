<?php
require_once 'NpCache.php';

class NpRedis implements NpCache
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
		if($this->cache!==null) {
			$this->cache->close();
			$this->cache=null;
		}
	}

	private function connect($host, $port)
	{
		$this->cache=new Redis();
		return $this->cache->pconnect($host,$port);
	}

	public function inc($key, $value=1)
	{
		$key=$this->prefix.$key;
		return $this->cache->incrBy($key, $value);
	}

	public function dec($key, $value=1)
	{
		$key=$this->prefix.$key;
		return $this->cache->decrBy($key, $value);
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
		return $this->cache->setex($key, $timeout, $value);
	}

	public function delete($key)
	{
		$key=$this->prefix.$key;
		return $this->cache->delete($key);
	}

	public static function getInstance($host, $port, $prefix, $timeout)
	{
		$instance=new NpRedis($prefix,$timeout);
		if(!$instance->connect($host, $port))
			throw new Exception('Redis cannot connect');
		return $instance;
	}
}
?>