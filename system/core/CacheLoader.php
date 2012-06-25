<?php
class CacheLoader {
	public static function connect(){
		$config=Config::getConfig('cache');	
		$type=$config['type'];
		
		if($type=='redis') {
			if(!class_exists('CcRedis'))
				require_once(BASEPATH.'libs/Cache/CcRedis.php');
			return CcRedis::getInstance($config['host'], $config['port']);
		}
		else if($type=='memcache') {
			if(!class_exists('CcMemcache'))
				require_once(BASEPATH.'libs/Cache/CcMemcache.php');
			return CcMemcache::getInstance($config['host'], $config['port']);
		}
		else if($type=='memcached') {
			if(!class_exists('CcMemcached'))
				require_once(BASEPATH.'libs/Cache/CcMemcached.php');
			return CcMemcached::getInstance($config['host'], $config['port']);
		}
		else 
			throw new Exception('Unsupport cache type {'.$type.'}');
	}
}
?>