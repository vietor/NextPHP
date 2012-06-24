<?php
class DatabaseLoader {
	public static function connect(){		
		if(!class_exists('DbConnection'))
			require_once(BASEPATH.'libs/Database/DbConnection.php');
		
		$config=Config::getConfig('database');
		if($config['type']=='mysql') {
			$dsn='mysql:dbname='.$config['dbname'].';host='.$config['host'].';port='.$config['port'].';charset='.$config['charset'];
			return new DbConnection($dsn,$config['user'],$config['passwd']);
		}
	}
}
?>