<?php
class LibLoader {	
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
}
?>