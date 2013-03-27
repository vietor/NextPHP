<?php
require_once 'NpRequest.php';
require_once 'NpResponse.php';
require_once 'NpController.php';

class NpMvcException extends Exception
{
}

class NpMvcExitException extends Exception
{
}

function NpExceptionHandler(Exception $e)
{
	if($e instanceof NpMvcException) {
		$errorMessage=$e->getMessage();
		if(!empty($errorMessage)) {
			error_log('NPE '.$errorMessage);
			header("HTTP/1.1 403 Forbidden");
			header("Status: ".$errorMessage);
		}
		else {
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
		}
	}
	else if(!($e instanceof NpMvcExitException)) {
		error_log('NPE ['.$e->getCode().'] '.$e->getMessage().' '.$e->getTraceAsString());
		header('HTTP/1.1 503 Service Temporarily Unavailable');
		header('Status: 503 Service Temporarily Unavailable');
	}
	exit();
}

//! The class for framework core process
class NpFramework
{
	private function handleRequest()
	{
		if(empty($_GET[NP_REDIRECT_KEY]))
			throw new NpMvcException();

		$urlArray=array();
		$urlArray=explode('/',$_GET[NP_REDIRECT_KEY]);
		$urlArraySize=count($urlArray);
		if($urlArraySize<2)
			throw new NpMvcException();
		unset($_GET[NP_REDIRECT_KEY], $_REQUEST[NP_REDIRECT_KEY]);

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
		$this->executeController($module,$action);
	}

	private function executeController($module,$action)
	{
		$result=NpController::execute($module,$action);
		if($result && is_object($result) && ($result instanceof NpViewFace)) {
			$result->display();
			$result=true;
		}
		return $result;
	}

	private static $instance;
	/*!
	 * @brief Execute an ACTION in a CONTROLLER object
	 * @param[in] module CONTROLLER name
	 * @param[in] action ACTION name
	 * @note When module and action are NULL, then analyze from URL
	 */
	public static function execute($module=null,$action=null)
	{
		if(self::$instance!==null)
			throw new NpMvcException('Framework multiple entry');

		define('NP_REDIRECT_KEY','url');
		set_exception_handler('NpExceptionHandler');

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

		self::$instance=new NpFramework();
		if($module===null || $action===null)
			self::$instance->handleRequest();
		else
			return self::$instance->executeController($module,$action);
	}
}
?>