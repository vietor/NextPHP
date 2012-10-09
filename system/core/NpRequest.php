<?php
class NpRequest {
	private $params=array();

	public function __construct($params) {
		$this->params=$params;
	}

	public function getMethod() {
		return $_SERVER ['REQUEST_METHOD'];
	}

	public function hasParam($key, $minLen=0) {
		$result=false;
		if(isset($this->params[$key]) && !empty($this->params[$key])) {
			if($minLen<1 || strlen($this->params[$key])>=$minLen)
				$result=true;
		}
		return $result;
	}

	public function getParam($key) {
		if(!$this->hasParam($key))
			return null;
		return $this->params[$key];
	}

	public function getCooke($key) {
		if(!isset($_COOKIE[$key]))
			return null;
		return $_COOKIE[$key];
	}

	public function uploadFile($key, $filename) {
		if(!isset($_FILES[$key]))
			return false;
		return move_uploaded_file($_FILES[$key], $filename);
	}

	public function getClientIp(){
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		else
			$ip=$_SERVER['REMOTE_ADDR'];
		return $ip;
	}
}
?>