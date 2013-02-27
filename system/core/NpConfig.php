<?php

/*! @mainpage Configuration
 *
 * \section intro Introduction
 *
 * You can create file <b>application/config.phpfile for configuration.
 *
 * \section intro Configuration items
 *
 * \subsection cookie 1. COOKIE
 * For NpResponse::setCookie
 * @param $_CONFIG['cookie']['domain']   cookie's domain, default $_SERVER['SERVER_NAME']
 * @param $_CONFIG['cookie']['path']     cookie's path, default /
 * @param $_CONFIG['cookie']['timeout']  seconds for cookie's expire time, default 2 week
 *
 * \subsection encryptor 2. Encrypt
 * For NpFactory::getEncryptor
 * @param $_CONFIG['encryptor']['mode']     encrypt mode, support aes, 3des; default aes
 * @param $_CONFIG['encryptor']['password'] password, length limit: min 7, max 32 for aes and 16 for 3des
 * @param $_CONFIG['encryptor']['timeout']  seconds for expire time, default 0 (forever)
 * 
 * \subsection database 3. Database
 * For NpFactory::getDatabase
 * @param $_CONFIG['database']['type']    database type for PDO, default mysql
 * @param $_CONFIG['database']['host']    host or ip address for connect, default localhost
 * @param $_CONFIG['database']['port']    port for connect, default 3306
 * @param $_CONFIG['database']['user']    username for connect, default root
 * @param $_CONFIG['database']['passwd']  password for connect, default empty
 * @param $_CONFIG['database']['dbname']  database name for connect, default mysql
 * @param $_CONFIG['database']['charset'] charset for connect, default utf8
 * 
 * For NpFactory::getExtraDatabase, $_CONFIG's key is 'database-' append extra name.
 * 
 * \subsection cache 4. Key-Value Cache
 * For NpFactory::getCache
 * @param $_CONFIG['cache']['type']    cache type, support memcache,memcached,redis; default memcache
 * @param $_CONFIG['cache']['host']    host or ip address for connect, default localhost
 * @param $_CONFIG['cache']['port']    port for connect, default 11211
 * @param $_CONFIG['cache']['prefix']  prefix append for keys, default empty
 * @param $_CONFIG['cache']['timeout'] seconds for expire time, default 0 (forever)
 * 
 * For NpFactory::getExtraCache, $_CONFIG's key is 'cache-' append extra name.
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
			$config['timeZone']   = 'UTC';
			$_CONFIG['system']=$config;
			// load application config
			@include(NP_APP_PATH.'config.php');
			// apply values to setting
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