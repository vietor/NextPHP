<?php
require_once 'NpCache.php';
require_once 'NpDatabase.php';
require_once 'NpEncryptor.php';

//! The class for objects creation
class NpFactory
{
	private static $_cache;
	//! Get a default NpCache object
	public static function getCache()
	{
		if(self::$_cache===null){
			$config=NpConfig::get('cache');
			self::$_cache=NpCache::getInstance($config);
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
			self::$_extra_cache[$name]=NpCache::getInstance(array_merge($base,$config));
		}
		return self::$_extra_cache[$name];
	}

	private static $_database;
	//! Get a default NpDatabase object
	public static function getDatabase()
	{
		if(self::$_database===null){
			$config=NpConfig::get('database');
			self::$_database=new NpDatabase($config);
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
			self::$_extra_database[$name]=new NpDatabase(array_merge($base,$config));
		}
		return self::$_extra_database[$name];
	}

	private static $_encryptor;
	//! Get a NpEncryptor object
	public static function getEncryptor()
	{
		if(self::$_encryptor===null){
			$config=NpConfig::get('encryptor');
			self::$_encryptor=new NpEncryptor($config);
		}
		return self::$_encryptor;
	}

    //! Get a library object
    public static function getLibraryObject($className,$args)
    {
        class_exists($className) or require_once(NP_SYS_PATH.'libs/'.$className.'.php');
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
?>