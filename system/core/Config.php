<?php
class Config {
	private $configs;

	public function __construct() {
		$_CONFIG=array();
		// cookie
		$config=array();
		$config['domain']=$_SERVER['SERVER_NAME'];
		$config['path']='/';
		$config['expire']=14; //day
		$_CONFIG['cookie']=$config;
		// unique
		$config=array();
		$config['mode']='aes'; // suport = aes, 3des
		$config['secret']='b5ee4d5b4f59451431081b0246c57c7b'; // length aes=>32, 3des=>16
		$_CONFIG['unique']=$config;
		// database
		$config=array();
		$config['type']='mysql';
		$config['host']='localhost';
		$config['port']=3306;
		$config['user']='root';
		$config['passwd']='';
		$config['dbname']='mysql';
		$config['charset']='utf8';
		$_CONFIG['database']=$config;
		// cache
		$config=array();
		$config['type']='memcache';
		$config['host']='localhost';
		$config['port']=11211;
		$_CONFIG['cache']=$config;
		// system
		$config=array();
		$config['time_zone']='UTC';
		$_CONFIG['system']=$config;
		// read custom config
		$custom_file=BASEPATH.'application/config.php';
		if(file_exists($custom_file))
			include_once($custom_file);
		$this->configs=$_CONFIG;

		date_default_timezone_set($_CONFIG['system']['time_zone']);
	}

	private static $instance;

	public static function execute() {
		if(is_null(self::$instance))
			self::$instance=new Config();
		require_once('Loader.php');
	}

	public static function getConfig($key) {
		if(!isset(self::$instance->configs[$key]))
			return false;
		return self::$instance->configs[$key];
	}
}

Config::execute();
?>