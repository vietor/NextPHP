<?php
require_once('Config.php');
require_once('Dispather.php');

class CoreInit {
	public function __construct() {
		set_exception_handler(array($this, 'handleException'));
	}

	public function handleException(Exception $e) {
		error_log($e->getMessage());
		header('HTTP/1.1 503 Service Temporarily Unavailable');
		header('Status: 503 Service Temporarily Unavailable');
	}

	private static $instance=null;

	public static function initialize() {
		if(!is_null(self::$instance))
			return;
		self::$instance=new CoreInit();

		Config::$instance=new Config();
		Dispather::$instance=new Dispather();
	}
}

CoreInit::initialize();

class CoreLoader {
	private static $tables=array();

	private static function getObject($key){
		return self::$tables[$key];
	}

	private static function setObject($key, $object){
		self::$tables[$key]=$object;
	}

	public static function loadCache(){
		$config=Config::getConfig('cache');
		$type=$config['type'];

		if($type=='redis') {
			if(!class_exists('CcRedis'))
				require_once(BASEPATH.'system/libs/Cache/CcRedis.php');
			return CcRedis::getInstance($config['host'], $config['port']);
		}
		else if($type=='memcache') {
			if(!class_exists('CcMemcache'))
				require_once(BASEPATH.'system/libs/Cache/CcMemcache.php');
			return CcMemcache::getInstance($config['host'], $config['port']);
		}
		else if($type=='memcached') {
			if(!class_exists('CcMemcached'))
				require_once(BASEPATH.'system/libs/Cache/CcMemcached.php');
			return CcMemcached::getInstance($config['host'], $config['port']);
		}
		else
			throw new Exception('Unsupport cache type {'.$type.'}');
	}

	public static function loadDatabase(){
		if(!class_exists('DbConnection'))
			require_once(BASEPATH.'system/libs/Database/DbConnection.php');

		$config=Config::getConfig('database');
		$type=$config['type'];

		if($type=='mysql')
			$dsn='mysql:dbname='.$config['dbname'].';host='.$config['host'].';port='.$config['port'].';charset='.$config['charset'];
		else if($type=='pgsql')
			$dsn='pgsql:dbname='.$config['dbname'].';host='.$config['host'].';port='.$config['port'].';charset='.$config['charset'];
		else
			throw new Exception('Unsupport database type {'.$config['type'].'}');

		return new DbConnection($dsn,$config['user'],$config['passwd']);
	}

	private static function requireTool($name){
		if(!class_exists($name)) {
			$file=BASEPATH.'system/libs/Tools/'.$name.'.php';
			if(!file_exists($file))
				throw new Exception('Unsupport tool type {'.$name.'}');
			require_once($file);
		}
	}

	public static function loadUniqueKey() {
		$name='UniqueKey';
		$object = getObject($name);
		if(!$object) {
			requireTool($name);
			$config=Config::getConfig('unique');
			
			$object=new UniqueKey($config['secret']);
			self::setObject($name, $object);
		}
		return $object;
	}

	public static function loadWebRequest(){
		requireTool('WebRequest');
		return new WebRequest;
	}
}
?>