<?php
class RESTful extends NpController {
	private static $errorMessage = array(
			100001 => 'Some parameter is missing or bad length',
			100002 => 'Perharps Database or Memcache has wrong',
	);

	protected function resultFailed($code) {
		$result=array();
		$result['error'] = $code;
		$result['description'] = self::$errorMessage[$code];
		$this->getResponse()->output(json_encode($result), 'application/json');
		$this->exitProcess();
	}

	protected function resultSuccessed($result) {
		$this->getResponse()->output(json_encode($result,true), 'application/json');
	}

	protected function getParamOrFailed($param,$minLen=0) {
		if(!$this->getRequest()->hasParam($param,$minLen))
			$this->resultFailed(100001);
		else
			return $this->getRequest()->getParam($param);
	}
}
?>