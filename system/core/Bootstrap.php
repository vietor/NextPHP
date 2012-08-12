<?php
require_once('Config.php');
require_once('Dispather.php');

class UndefinedException extends Exception {
	public function __construct($message='', $code=0) {
		parent::__construct($message, $code);
	}
}

class Bootstrap {
	private $dispather;

	private function __construct() {
		$this->dispather=new Dispather();
		set_exception_handler(array($this, 'handleException'));
	}

	public function handleException(Exception $e) {
		if($e instanceof UndefinedException) {
			error_log($e->getMessage());
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
		}
		else{
			error_log($e->getMessage().' '.$e->getTraceAsString());
			header('HTTP/1.1 503 Service Temporarily Unavailable');
			header('Status: 503 Service Temporarily Unavailable');
		}
		die();
	}

	private function handleRequest() {
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

		if(!isset($_GET['url']) || empty($_GET['url']))
			throw new UndefinedException('No found parameter: url');
		$url=$_GET['url'];
		if(substr($url,0,1)=='/')
			$url=substr($url,1);
		$urlArray = array();
		$urlArray = explode('/',$url);
		if(count($urlArray)<2)
			throw new UndefinedException('Bad parameter size: url');
		unset($_GET['url']);

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
		$this->dispather->dispath($module,$action,array_merge($params,$_POST));
	}

	private static $instance=null;

	public static function execute() {
		if(is_null(self::$instance))
			self::$instance=new Bootstrap();
		self::$instance->handleRequest();
	}
}

Bootstrap::execute();
?>