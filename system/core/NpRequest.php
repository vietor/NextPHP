<?php
class NpRequest {
	private $params=array();
	private $sessionOn=false;

	public function addParams($params) {
		$this->params=array_merge($this->params,$params);
	}

	public function getMethod() {
		return $_SERVER ['REQUEST_METHOD'];
	}

	private function startSession()
	{
		if(!$this->sessionOn) {
			$this->sessionOn=true;
			if(session_id()=='')
				session_start();
		}
	}

	public function getSession($key,$defaultValue=null) {
		$this->startSession();
		if(!isset($_SESSION[$key]))
			return $defaultValue;
		return $_SESSION[$key];
	}

	public function setSession($key,$value) {
		$this->startSession();
		return $_SESSION[$key]=$value;
	}

	public function getSessionId() {
		$this->startSession();
		return session_id();
	}

	public function removeSession($key)
	{
		unset($_SESSION[$key]);
	}

	public function clearSession() {
		session_destroy();
		$this->sessionOn=false;
	}

	public function getUrlByHost($path='') {
		return NP_URL_PATH.$path;
	}

	public function getUrlByBackward($path='',$backward=3) {
		$array=explode('/',$_SERVER['REQUEST_URI']);
		$count=count($array);
		$count-=min($count,$backward);
		$url='';
		for($i=0; $i<$count;++$i)
			$url.=$array[$i].'/';
		return NP_URL_PATH.$url.$path;
	}

	public function hasParam($key, $minLen=0) {
		$result=false;
		$value=$this->getParam($key);
		if(!is_null($value)) {
			if($minLen>0)
				$result=strlen($value)>=$minLen;
			else
				$result=true;
		}
		return $result;
	}

	public function getParam($key,$defaultValue=null) {
		if(isset($this->params[$key]))
			$result=$this->params[$key];
		else if(isset($_POST[$key]))
			$result=$_POST[$key];
		else if(isset($_GET[$key]))
			$result=$_GET[$key];
		else
			$result=$defaultValue;
		return $result;
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

	private static $_instance;
	public static function getInstance() {
		if(self::$_instance==null)
			self::$_instance=new NpRequest();
		return self::$_instance;
	}
}
?>