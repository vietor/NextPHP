<?php
class NpRequest {
	private $params=array();

	public function __construct($params) {
		$this->params=$params;
	}

	public function getMethod() {
		return $_SERVER ['REQUEST_METHOD'];
	}

	public function getSession($key,$defaultValue=null) {
		if(!isset($_SESSION[$key]))
			return $defaultValue;
		return $_SESSION[$key];
	}

	public function setSession($key,$value) {
		return $_SESSION[$key]=$value;
	}

	public function getSessionId() {
		return session_id();
	}

	public function startSession() {
		session_start();
	}

	public function stopSession() {
		session_destroy();
	}

	public function getUrlByHost($path='') {
		return NP_BASEURL.$path;
	}

	public function getUrlByBackward($path='',$backward=3) {
		$array=explode('/',$_SERVER['REQUEST_URI']);
		$count=count($array);
		$count-=min($count,$backward);
		$url='';
		for($i=0; $i<$count;++$i)
			$url.=$array[$i].'/';
		return NP_BASEURL.$url.$path;
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

	public function getCooke($key,$defaultValue=null) {
		if(!isset($_COOKIE[$key]))
			return $defaultValue;
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