<?php
class Config {
	private $configs;
	
	public function __construct() {
		$_CONFIG=array();
		// router
		$config=array();
		$config['type']='GET'; // support = URL GET POST MIXd
		$config['key_module']='module';
		$config['key_action']='action';
		$_CONFIG['router']=$config;
		// cookie
		$config=array();
		$config['domain']=$_SERVER['SERVER_NAME'];
		$config['path']='/';
		$config['expire']=14; //day
		$_CONFIG['cookie']=$config;
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
		$config['type']='memcached';
		$config['host']='localhost';
		$config['port']=11211;
		$_CONFIG['cache']=$config;
		// read custom config
		include_once(BASEPATH.'application/config.php');
		$this->configs=$_CONFIG;
	}
	
	private static $instance=null;
	
	public static function initialize() {
		if(!is_null(self::$instance))
			return;
		self::$instance=new Config();
		
		$include_paths = array(
			"application",
		);
		foreach ($include_paths as $path)
			set_include_path(get_include_path().PATH_SEPARATOR.BASEPATH.$path);
	}
	
	public static function getConfig($key) {
		if(!isset(self::$instance->configs[$key]))
			return false;
		return self::$instance->configs[$key];
	}
}

Config::initialize();
?>