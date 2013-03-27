<?php
require_once 'NpFactory.php';

//! The class for tools creation
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
	//! Get a default NpCache object
	public static function getCache()
	{
		if(self::$_cache===null){
			$config=NpConfig::get('cache');
			self::$_cache=self::createObject('NpCache', array($config['type'], $config['host'], $config['port'], $config['prefix'], $config['timeout']), true);
		}
		return self::$_cache;
	}

	private static $_extra_cache;
	/*!
	 * @brief Get a extra NpCache object
	 * @param[in] name the extra cache's name
	 */
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
	//! Get a default NpDatabase object
	public static function getDatabase()
	{
		if(self::$_database===null){
			$config=NpConfig::get('database');
			$dsn=$config['type'].':dbname='.$config['dbname'].';host='.$config['host'].';port='.$config['port'].';charset='.$config['charset'];
			self::$_database=self::createObject('NpDatabase', array($dsn,$config['user'],$config['passwd'],$config['persistent']));
		}
		return self::$_database;
	}

	private static $_extra_database;
	/*!
	 * @brief Get a extra NpDatabase object
	 * @param[in] name the extra database's name
	 */
	public static function getExtraDatabase($name)
	{
		if(!isset(self::$_extra_database[$name])){
			$base=NpConfig::get('database');
			$config=NpConfig::get('database-'.$name);
			$dsn=$config['type'].':dbname='.$config['dbname'].';host='.$config['host'].';port='.$config['port'].';charset='.(isset($config['charset'])?$config['charset']:$base['charset']);
			self::$_extra_database[$name]=self::createObject('NpDatabase', array($dsn,isset($config['user'])?$config['user']:$base['user'],isset($config['passwd'])?$config['passwd']:$base['passwd'],isset($config['persistent'])?$config['persistent']:$base['persistent']));
		}
		return self::$_extra_database[$name];
	}

	private static $_encryptor;
	//! Get a NpEncryptor object
	public static function getEncryptor()
	{
		if(self::$_encryptor===null){
			$config=NpConfig::get('encryptor');
			self::$_encryptor=self::createObject('NpEncryptor', array($config['mode'],$config['password'],$config['timeout']));
		}
		return self::$_encryptor;
	}

	//! Get a NpWebRequest object
	public static function newWebRequest()
	{
		return self::createObject('NpWebRequest');
	}

	//! Get a NpCrypto object
	public static function newCrypto($type)
	{
		return self::createObject('NpCrypto', array($type));
	}
}
?>