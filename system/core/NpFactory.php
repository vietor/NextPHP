<?php
class NpFactory
{
	private static function createObject($className, $args=null, $staticConstructor=false)
	{
		class_exists($className) or require_once(NP_SYS_PATH.'libs/'.$className.'.php');
		if($staticConstructor){
			if($args===null)
				return $className::getInstance();
			else {
				$argCount=count($args);
				if($argCount==1)
					return $className::getInstance($args[0]);
				else if($argCount==2)
					return $className::getInstance($args[0],$args[1]);
				else if($argCount==3)
					return $className::getInstance($args[0],$args[1],$args[2]);
				else if($argCount==4)
					return $className::getInstance($args[0],$args[1],$args[2],$args[3]);
				else if($argCount==5)
					return $className::getInstance($args[0],$args[1],$args[2],$args[3],$args[4]);
				else
					return call_user_func_array(array($className,"getInstance"), $args);
			}
		}else{
			if($args===null)
				return new $className();
			else{
				$argCount=count($args);
				if($argCount==1)
					return new $className($args[0]);
				else if($argCount==2)
					return new $className($args[0],$args[1]);
				else if($argCount==3)
					return new $className($args[0],$args[1],$args[2]);
				else if($argCount==4)
					return new $className($args[0],$args[1],$args[2],$args[3]);
				else if($argCount==5)
					return new $className($args[0],$args[1],$args[2],$args[3],$args[4]);
				else {
					$reflection=new ReflectionClass($className);
					return $reflection->newInstanceArgs($args);
				}
			}
		}
	}

	private static $_cache;
	public static function getCache()
	{
		if(self::$_cache===null){
			$config=NpConfig::get('cache');
			$type=$config['type'];
			if($type=='redis')
				$className='NpRedis';
			else if($type=='memcache')
				$className='NpMemcache';
			else if($type=='memcached')
				$className='NpMemcached';
			else
				throw new NpCoreException('Unsupport cache type {'.$type.'}');
			self::$_cache=self::createObject($className, array($config['host'], $config['port'], $config['prefix'], $config['timeout']), true);
		}
		return self::$_cache;
	}
	
	public static function getCacheDirect($type, $host, $port)
	{
		if($type=='redis')
			$className='NpRedis';
		else if($type=='memcache')
			$className='NpMemcache';
		else if($type=='memcached')
			$className='NpMemcached';
		else
			throw new NpCoreException('Unsupport cache type {'.$type.'}');
		$config=NpConfig::get('cache');
		return self::createObject($className, array($host, $port, $config['prefix'], $config['timeout']), true);
	}

	private static $_database;
	public static function getDatabase()
	{
		if(self::$_database===null){
			$config=NpConfig::get('database');
			self::$_database=self::createObject('NpDatabase', array($config['type'].':dbname='.$config['dbname'].';host='.$config['host'].';port='.$config['port'].';charset='.$config['charset'],$config['user'],$config['passwd']));
		}
		return self::$_database;
	}

	private static $_uniqueKey;
	public static function getUniqueKey()
	{
		if(self::$_uniqueKey===null){
			$config=NpConfig::get('unique');
			self::$_uniqueKey=self::createObject('NpUniqueKey', array($config['mode'],$config['secret'],$config['expire']));
		}
		return self::$_uniqueKey;
	}

	public static function newWebRequest()
	{
		return self::createObject('NpWebRequest');
	}

	public static function newCrypto($type)
	{
		return self::createObject('NpCrypto', array($type));
	}
}
?>