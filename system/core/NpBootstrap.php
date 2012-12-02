<?php
require_once 'NpConfig.php';
require_once 'NpFactory.php';
require_once 'NpRequest.php';
require_once 'NpResponse.php';
require_once 'NpController.php';

class NpCoreException extends Exception
{
	public function __construct($message='')
	{
		parent::__construct($message);
	}
}

class NpExitException extends Exception
{
}

class NpBootstrap
{
	public function __construct()
	{
		set_exception_handler(array($this, 'handleException'));
	}
	
	public function handleException(Exception $e)
	{
		if($e instanceof NpCoreException) {
			$errorMessage=$e->getMessage();
			if(!empty($errorMessage))
				error_log($errorMessage);
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
		}
		else if(!($e instanceof NpExitException)) {
			error_log($e->getMessage().' '.$e->getTraceAsString());
			header('HTTP/1.1 503 Service Temporarily Unavailable');
			header('Status: 503 Service Temporarily Unavailable');
		}
		exit();
	}

	private function handleRequest()
	{
		if(empty($_GET['url']))
			throw new NpCoreException();
		
		$urlArray=array();
		$urlArray=explode('/',$_GET['url']);
		$urlArraySize=count($urlArray);
		if($urlArraySize<2)
			throw new NpCoreException();
		unset($_GET['url'], $_REQUEST['url']);

		$module=$urlArray[0];
		$action=$urlArray[1];
		$key=null;
		$params=array();
		for($i=2;$i<$urlArraySize;++$i) {
			if($key===null) {
				if(empty($urlArray[$i]))
					break;
				$key=$urlArray[$i];
			} else {
				$params[$key]=$urlArray[$i];
				$key=null;
			}
		}
		if(count($params)>0)
			NpRequest::addParams($params);
		$this->handleController($module,$action);
	}

	private function handleController($module,$action)
	{
		$controller=NpController::getInstance($module,$action);
		$result=$controller->invokeAction($action);
		if($result && is_object($result) && ($result instanceof NpViewBase)) {
			$result->display();
			$result=true;
		}
		return $result;
	}

	private static $instance;

	public static function execute($module=null,$action=null)
	{
		NpConfig::execute();
		
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

		if(self::$instance===null)
			self::$instance=new NpBootstrap();
		if($module===null || $action===null)
			self::$instance->handleRequest();
		else
			return self::$instance->handleController($module,$action);
	}
}
?>