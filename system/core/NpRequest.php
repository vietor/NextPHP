<?php
class NpRequest
{
	private static $params=array();
	private static $sessionOn=false;

	public static function addParams($params)
	{
		self::$params=array_merge(self::$params,$params);
	}

	public static function getMethod()
	{
		return $_SERVER ['REQUEST_METHOD'];
	}

	private static function startSession()
	{
		if(!self::$sessionOn) {
			self::$sessionOn=true;
			if(session_id()=='')
				session_start();
		}
	}

	public static function getSession($key,$defaultValue=null)
	{
		self::startSession();
		if(!isset($_SESSION[$key]))
			return $defaultValue;
		return $_SESSION[$key];
	}

	public static function setSession($key,$value)
	{
		self::startSession();
		return $_SESSION[$key]=$value;
	}

	public static function getSessionId()
	{
		self::startSession();
		return session_id();
	}

	public static function removeSession($key)
	{
		unset($_SESSION[$key]);
	}

	public static function clearSession()
	{
		session_destroy();
		self::$sessionOn=false;
	}

	public static function getUrlByHost($path='')
	{
		return NP_URL_PATH.$path;
	}

	public static function getUrlByBackward($path='',$backward=3)
	{
		$array=explode('/',$_SERVER['REQUEST_URI']);
		$count=count($array);
		$count-=min($count,$backward);
		$url='';
		for($i=0; $i<$count;++$i)
			$url.=$array[$i].'/';
		return NP_URL_PATH.$url.$path;
	}

	public static function hasParam($key, $minLen=0)
	{
		$result=false;
		$value=self::getParam($key);
		if(!is_null($value)) {
			if($minLen>0)
				$result=strlen($value)>=$minLen;
			else
				$result=true;
		}
		return $result;
	}

	public static function getParam($key,$defaultValue=null)
	{
		if(isset(self::$params[$key]))
			$result=self::$params[$key];
		else if(isset($_POST[$key]))
			$result=$_POST[$key];
		else if(isset($_GET[$key]))
			$result=$_GET[$key];
		else
			$result=$defaultValue;
		return $result;
	}

	public static function getCooke($key,$defaultValue=null)
	{
		if(!isset($_COOKIE[$key]))
			return $defaultValue;
		return $_COOKIE[$key];
	}

	public static function uploadFile($key, $filename)
	{
		if(!isset($_FILES[$key]))
			return false;
		return move_uploaded_file($_FILES[$key], $filename);
	}

	public static function getClientIp()
	{
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