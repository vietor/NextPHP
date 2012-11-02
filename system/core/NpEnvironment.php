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