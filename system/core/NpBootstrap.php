<?php
require_once('NpConfig.php');
require_once('NpDispather.php');

class NpUndefinedException extends Exception {
	public function __construct($message='', $code=0) {
		parent::__construct($message, $code);
	}
}

class NpPeacefulException extends Exception {
}

class NpBootstrap {
	private $dispather;

	private function __construct() {
		$this->dispather=new NpDispather();
		set_exception_handler(array($this, 'handleException'));
	}

	public function handleException(Exception $e) {
		if($e instanceof NpUndefinedException) {
			$errorMessage=$e->getMessage();
			if(!empty($errorMessage))
				error_log($errorMessage);
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
		}
		else if(!($e instanceof NpPeacefulException)) {
			error_log($e->getMessage().' '.$e->getTraceAsString());
			header('HTTP/1.1 503 Service Temporarily Unavailable');
			header('Status: 503 Service Temporarily Unavailable');
		}
		exit();
	}

	private function handleRequest() {
		if(!isset($_GET['url']) || empty($_GET['url']))
			throw new NpUndefinedException();
		$url=$_GET['url'];
		if(substr($url,0,1)=='/')
			$url=substr($url,1);
		$urlArray = array();
		$urlArray = explode('/',$url);
		if(count($urlArray)<2)
			throw new NpUndefinedException();
		unset($url, $_GET['url']);

		$module=$urlArray[0];
		$action=$urlArray[1];
		$key=null;
		$params=array();
		for($i=2;$i<count($urlArray);++$i) {
			if(empty($urlArray[$i]))
				continue;
			if(is_null($key))
				$key=$urlArray[$i];
			else {
				$params[$key]=$urlArray[$i];
				$key=null;
			}
		}
		unset($urlArray);
		$this->dispather->dispath($module,$action,$params);
	}

	private function handleController($module,$action)
	{
		return $this->dispather->dispath($module,$action,array());
	}

	private static $instance=null;

	public static function execute($module=null,$action=null) {
		if (get_magic_quotes_gpc ()) {
			$in = array (&$_GET, &$_POST, &$_COOKIE, &$_FILES );
			while ( (list ( $k, $v ) = each ( $in )) !== false ) {
				foreach ( $v as $key => $val ) {
					if (! is_array ( $val )) {
						$in [$k] [$key] = stripslashes ( $val );
						continue;
					}
					$in [] = & $in [$k] [$key];
				}
			}
			unset ( $in );
		}
		NpConfig::execute();

		if(is_null(self::$instance))
			self::$instance=new NpBootstrap();
		if(is_null($module) || is_null($action))
			self::$instance->handleRequest();
		else
			return self::$instance->handleController($module,$action);
	}
}
?>