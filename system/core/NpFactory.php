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
			self::$_cache=self::createObject('NpCache', array($config['type'], $config['host'], $config['port'], $config['prefix'], $config['timeout']), true);
		}
		return self::$_cache;
	}

	private static $_extra_cache;
	public static function getExtraCache($name)
	{
		if(!isset(self::$_extra_cache[$name])){
			$base=NpConfig::get('cache');
			$config=NpConfig::get('cache-'.$name);
			self::$_extra_cache[$name]=self::createObject('NpCache', array($config['type'], $config['host'], $config['port'],
					isset($config['prefix'])?$config['prefix']:$base['prefix'], isset($config['timeout'])?$config['timeout']:$base['timeout']), true);
		}
		return self::$_extra_cache[$name];
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

	private static $_extra_database;
	public static function getExtraDatabase($name)
	{
		if(!isset(self::$_extra_database[$name])){
			$base=NpConfig::get('database');
			$config=NpConfig::get('database-'.$name);
			$charset=isset($config['charset'])?$config['charset']:$base['charset'];
			self::$_extra_database[$name]=self::createObject('NpDatabase', array($config['type'].':dbname='.$config['dbname'].';host='.$config['host'].';port='.$config['port'].';charset='.$charset,
					isset($config['user'])?$config['user']:$base['user'],isset($config['passwd'])?$config['passwd']:$base['passwd']));
		}
		return self::$_extra_database[$name];
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