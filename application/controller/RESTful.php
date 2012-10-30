<?php
class RESTful extends NpController {
	private static $errorMessage = array(
			100001 => 'Some parameter is missing or bad length',
			100002 => 'Perharps Database or Memcache has wrong',
	);

	protected function terminateWithFailed($code) {
		$result=array();
		$result['error'] = $code;
		$result['description'] = self::$errorMessage[$code];
		NpResponse::getInstance()->output(json_encode($result), 'application/json');
		$this->terminateProcess();
	}

	protected function getParamOrFailed($param,$minLen=0) {
		if(!NpRequest::getInstance()->hasParam($param,$minLen))
			$this->terminateWithFailed(100001);
		else
			return NpRequest::getInstance()->getParam($param);
	}

	public function afterProcess() {
		NpResponse::getInstance()->output(json_encode($result,true), 'application/json');
	}
}
?>