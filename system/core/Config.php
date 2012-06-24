<?php
class Config {
	private $configs=array();
	
	public function __construct() {
		// router
		$config=array();
		$config['type']='GET'; // support = URL GET POST MIXd
		$config['key_module']='module';
		$config['key_action']='action';
		$this->configs['router']=$config;
		// cookie
		$config=array();
		$config['domain']=$_SERVER['SERVER_NAME'];
		$config['path']='/';
		$config['expire']=14; //day
		$this->configs['cookie']=$config;
		// database
		$config=array();
		$config['type']='mysql';
		$config['host']='localhost';
		$config['port']=3306;
		$config['user']='root';
		$config['passwd']='';
		$config['dbname']='mysql';
		$this->configs['database']=$config;
		// memcache
		$config=array();
		$config['type']='memcached';
		$config['host']='localhost';
		$config['port']=11211;
		$this->configs['memcache']=$config;
		// read custom config
		$_CONFIG=$this->configs;
		require_once(BASEPATH.'application/config.php');
	}
	
	private static $instance=null;
	
	public static function initialize() {
		if(!is_null(self::$instance))
			return;
		self::$instance=new Config();
		
		$include_paths = array(
				"system/core",
				"system/libs",
				"application/model",
				"application/view",
				"application/controller",
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