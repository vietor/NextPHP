<?php

/*!
 * @mainpage Configuration
 *
 * @section intro Introduction
 *
 * You can create file <b>application/config.phpfile</b> for configuration.
 *
 * @section intro Configuration items
 *
 * @subsection cookie Cookie
 * For NpResponse::setCookie, $_CONFIG['cookie']
 * @param domain   cookie's domain, default $_SERVER['SERVER_NAME']
 * @param path     cookie's path, default /
 * @param timeout  seconds for cookie's expire time, default 2 week
 *
 * @subsection encryptor Encrypt
 * For NpFactory::getEncryptor, $_CONFIG['encryptor']
 * @param mode     encrypt mode, support aes, 3des; default aes
 * @param password password, length limit: min 7, max 32 for aes and 16 for 3des
 * @param timeout  seconds for expire time, default 0 (forever)
 * 
 * @subsection database Database
 * For NpFactory::getDatabase, $_CONFIG['database']
 * @param type    database type for PDO, default mysql
 * @param host    host or ip address for connect, default localhost
 * @param port    port for connect, default 3306
 * @param user    username for connect, default root
 * @param passwd  password for connect, default empty
 * @param dbname  database name for connect, default mysql
 * @param charset charset for connect, default utf8
 * 
 * For NpFactory::getExtraDatabase, $_CONFIG's key is 'database-' append extra name.
 * 
 * @subsection cache Key-Value Cache
 * For NpFactory::getCache, $_CONFIG['cache']
 * @param type    cache type, support memcache,memcached,redis; default memcache
 * @param host    host or ip address for connect, default localhost
 * @param port    port for connect, default 11211
 * @param prefix  prefix append for keys, default empty
 * @param timeout seconds for expire time, default 0 (forever)
 * 
 * For NpFactory::getExtraCache, $_CONFIG's key is 'cache-' append extra name.
 * 
 * @subsection system Environment
 * For Framework environment, $_CONFIG['system']
 * @param quiet error reporting level switch, true is 0, flase is E_ALL ^ E_NOTICE, default false
 * @param timeZone date's default time zone, default UTC
 */

//! The class for configs
class NpConfig {
	private static $configs;

	public static function load()
	{
		if(self::$configs===null) {
			$_CONFIG=array();
			// cookie
			$config=array();
			$config['domain']     = $_SERVER['SERVER_NAME'];
			$config['path']       = '/';
			$config['timeout']     = 14 * 24 * 3600; // seconds
			$_CONFIG['cookie']=$config;
			// encryptor
			$config=array();
			$config['mode']       = 'aes'; // as: aes(32), 3des(16)
			$config['password']   = 'b5ee4d5b4f59451431081b0246c57c7b';
			$config['timeout']	  = 0; // seconds
			$_CONFIG['encryptor']=$config;
			// database
			$config=array();
			$config['type']       = 'mysql';
			$config['host']       = 'localhost';
			$config['port']       = 3306;
			$config['user']       = 'root';
			$config['passwd']     = '';
			$config['dbname']     = 'mysql';
			$config['charset']    = 'utf8';
			$_CONFIG['database']=$config;
			// cache
			$config=array();
			$config['type']       = 'memcache';
			$config['host']       = 'localhost';
			$config['port']       = 11211;
			$config['prefix']     = '';
			$config['timeout']	= 0; // seconds
			$_CONFIG['cache']=$config;
			// system
			$config=array();
			$config['quiet']      = false;
			$config['timeZone']   = 'UTC';
			$_CONFIG['system']=$config;
			// load application config
			@include(NP_APP_PATH.'config.php');
			// apply values to setting
			if($_CONFIG['system']['quiet']===true)
				error_reporting(0);
			else
				error_reporting(E_ALL ^ E_NOTICE);
			date_default_timezone_set($_CONFIG['system']['timeZone']);

			self::$configs=$_CONFIG;
		}
	}

	//! Get a config item object
	public static function get($key)
	{
		if(!isset(self::$configs[$key]))
			throw new NpCoreException('Not found config item:'.$key);
		return self::$configs[$key];
	}
}
?>

