<?php
require_once('NpConfig.php');
require_once('NpRequest.php');
require_once('NpResponse.php');
require_once('NpController.php');

class NpUndefinedException extends Exception {
	public function __construct($message='', $code=0) {
		parent::__construct($message, $code);
	}
}

class NpPeacefulException extends Exception {
}

class Np {
	public static function __callStatic($name, $arguments) {
		if(method_exists('NpFactory', $name))
			$target='NpFactory';
		else if(method_exists('NpRequest', $name))
			$target=NpRequest::getInstance();
		else if(method_exists('NpResponse', $name))
			$target=NpResponse::getInstance();
		else if(method_exists('NpConfig', $name))
			$target='NpConfig';
		else
			throw new NpUndefinedException('Not found fuzzy class method:'.$name);

		return call_user_func_array(array($target, $name), $arguments);
	}

	public static function loadModel($name) {
		return NpModel::loadModel($name);
	}

	public static function loadView($name='') {
		return NpView::loadView($name);
	}
}

class NpEnvironment  {
	protected function __construct() {
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

	public static function safetyExit() {
		throw new NpPeacefulException();
	}
}

?>