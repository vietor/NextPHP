<?php
class Config {
	private $configs;

	public function __construct() {
		$_CONFIG=new stdClass;
		// cookie
		$config=new stdClass;
		$config->domain     = $_SERVER['SERVER_NAME'];
		$config->path       = '/';
		$config->expire     = 14; //day
		$_CONFIG->cookie=$config;
		// unique
		$config=new stdClass;
		$config->mode       = 'aes'; // as: aes(32), 3des(16)
		$config->secret     = 'b5ee4d5b4f59451431081b0246c57c7b';
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
		$config->timeZone   = 'UTC';
		$_CONFIG->system    = $config;
		// read custom config
		$custom_file=BASEPATH.'application/config.php';
		if(file_exists($custom_file))
			include_once($custom_file);
		$this->configs=$_CONFIG;

		date_default_timezone_set($_CONFIG->system->timeZone);
	}

	private static $instance;

	public static function execute() {
		if(is_null(self::$instance))
			self::$instance=new Config();
		require_once('Loader.php');
	}

	public static function getConfig($key) {
		if(!property_exists(self::$instance->configs,$key))
			throw new Exception('Not found config item:'.$key);
		return self::$instance->configs->$key;
	}
}

Config::execute();
?>