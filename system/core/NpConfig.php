<?php
class NpConfig {
	private static $configs;

	public static function execute()
	{
		if(self::$configs===null) {			
			$_CONFIG=new stdClass;
			// cookie
			$config=new stdClass;
			$config->domain     = $_SERVER['SERVER_NAME'];
			$config->path       = '/';
			$config->expire     = 14 * 24 * 3600; // seconds
			$_CONFIG->cookie=$config;
			// unique
			$config=new stdClass;
			$config->mode       = 'aes'; // as: aes(32), 3des(16)
			$config->secret     = 'b5ee4d5b4f59451431081b0246c57c7b';
			$config->expire		= 0; // seconds
			$_CONFIG->unique=$config;
			// database
			$config=new stdClass;
			$config->type       = 'mysql';
			$config->host       = 'localhost';
			$config->port       = 3306;
			$config->user       = 'root';
			$config->passwd     = '';
			$config->dbname     = 'mysql';
			$config->charset    = 'utf8';
			$_CONFIG->database=$config;
			// cache
			$config=new stdClass;
			$config->type       = 'memcache';
			$config->host       = 'localhost';
			$config->port       = 11211;
			$config->prefix     = '';
			$config->timeout	= 0; // seconds
			$_CONFIG->cache=$config;
			// mailer
			$config=new stdClass;
			$config->SMTPAuth   = true;
			$config->SMTPSecure = 'ssl';
			$config->Host       = 'smtp.gmail.com';
			$config->Port       = 465;
			$config->Username   = 'yourusername@gmail.com';
			$config->Password   = 'yourpassword';
			$config->FromName   = 'First Last';
			$config->FromAddress = 'name@yourdomain.com';
			$_CONFIG->mailer=$config;
			// system
			$config=new stdClass;
			$config->timeZone        = 'UTC';
			$_CONFIG->system    = $config;
			// load application config
			@include(NP_APP_PATH.'config.php');
			// apply values to setting
			date_default_timezone_set($_CONFIG->system->timeZone);

			self::$configs=$_CONFIG;
		}
	}

	public static function get($key)
	{
		if(!isset(self::$configs->$key))
			throw new NpCoreException('Not found config item:'.$key);
		return self::$configs->$key;
	}
}
?>