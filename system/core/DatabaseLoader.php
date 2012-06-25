<?php
class DatabaseLoader {
	public static function connect(){		
		if(!class_exists('DbConnection'))
			require_once(BASEPATH.'libs/Database/DbConnection.php');
		
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