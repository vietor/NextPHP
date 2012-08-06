<?php
class Config {
	public static $instance;

	private $configs;

	public function __construct() {
		$_CONFIG=array();
		// router
		$config=array();
		$config['type']='PARAM'; // support = URL PARAM
		$config['key_module']='module';
		$config['key_action']='action';
		$config['default_module']='Controller';
		$config['default_action']='undefined';
		$_CONFIG['router']=$config;
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
		include_once(BASEPATH.'application/config.php');
		$this->configs=$_CONFIG;

		date_default_timezone_set($_CONFIG['system']['time_zone']);
	}

	public static function getConfig($key) {
		if(!isset(self::$instance->configs[$key]))
			return false;
		return self::$instance->configs[$key];
	}
}
?>