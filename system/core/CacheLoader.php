<?php
class CacheLoader {
	public static function connect(){
		$config=Config::getConfig('cache');		
		if($config['type']=='memcached') {
			if(!class_exists('Memcached'))
				require_once(BASEPATH.'libs/Cache/Memcached.php');
			$cache=new Memcached();
			if(!$cache->connect($config['host'], $config['port']))
				throw new Exception('Memcached cannot connect');
			return $cache;
		}
	}
}
?>