<?php
abstract class NpAbstractCache
{
	protected $cache;
	protected $prefix;
	protected $timeout;

	public function __construct($prefix,$timeout)
	{
		$this->prefix=$prefix;
		$this->timeout=$timeout;
	}
	
	public abstract function exists($key);
	public abstract function inc($key, $value);
	public abstract function dec($key, $value);
	public abstract function get($key);
	public abstract function set($key,$value,$timeout);
	public abstract function setNoKey($key,$value,$timeout);
	public abstract function delete($key);
}

class NpMemcache extends NpAbstractCache
{
	public function __destruct()
	{
		if($this->cache!==null) {
			$this->cache->close();
			$this->cache=null;
		}
	}

	public function connect($host, $port)
	{
		$this->cache=new Memcache();
		return $this->cache->pconnect($host,$port);
	}
	
	public function exists($key)
	{
		$result=$this->get($key);
		return ($result!==false)&&(!is_array($result)||!empty($result));
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
		if($timeout==0) {
			$timeout=$this->timeout;
			if($timeout==0)
				return $this->cache->set($key, $value);
		}
		return $this->cache->set($key, $value, 0, $timeout);
	}

	public function setNoKey($key,$value,$timeout=0)
	{
		$key=$this->prefix.$key;
		if($timeout==0) {
			$timeout=$this->timeout;
			if($timeout==0)
				return $this->cache->add($key, $value);
		}
		return $this->cache->add($key, $value, 0, $timeout);
	}

	public function delete($key)
	{
		$key=$this->prefix.$key;
		return $this->cache->delete($key);
	}
}

class NpMemcached extends NpMemcache
{
	public function __destruct()
	{
	}

	public function connect($host, $port)
	{
		$this->cache=new Memcached();
		return $this->cache->addServer($host,$port);
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

	public function setNoKey($key,$value,$timeout=0)
	{
		$key=$this->prefix.$key;
		if($timeout==0) {
			$timeout=$this->timeout;
			if($timeout==0)
				return $this->cache->add($key, $value);
		}
		return $this->cache->add($key, $value, $timeout);
	}
}

class NpRedis extends NpAbstractCache
{
	public function __destruct()
	{
		if($this->cache!==null) {
			$this->cache->close();
			$this->cache=null;
		}
	}

	public function connect($host, $port)
	{
		$this->cache=new Redis();
		return $this->cache->pconnect($host,$port);
	}
	
	public function exists($key)
	{
		return $this->cache->exists($key);
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

	public function setNoKey($key,$value,$timeout=0)
	{
		$key=$this->prefix.$key;
		if($timeout==0)
			$timeout=$this->timeout;
		$result=$this->cache->setnx($key, $value);
		if($result && $timeout>0)
			$this->cache->setTimeout($key, $timeout);
		return $result;
	}

	public function delete($key)
	{
		$key=$this->prefix.$key;
		return $this->cache->delete($key);
	}
}

class NpCache
{
	public static function getInstance($type, $host, $port, $prefix, $timeout)
	{
		if($type=='redis')
			$className='NpRedis';
		else if($type=='memcache')
			$className='NpMemcache';
		else if($type=='memcached')
			$className='NpMemcached';
		else
			throw new Exception('Unsupport cache type {'.$type.'}');

		$instance=new $className($prefix,$timeout);
		if(!$instance->connect($host, $port))
			throw new Exception($type.' cannot connect');
		return $instance;
	}
}
?>