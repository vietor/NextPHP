<?php
class NpRequest
{
	private static $basePath;
	private static $clientIp;
	private static $params=array();
	private static $sessionOn=false;

	public static function getMethod()
	{
		return $_SERVER ['REQUEST_METHOD'];
	}
	
	public static function getBaseUrl()
	{
		if(self::$basePath===null) {
			if(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']==="on"||$_SERVER['HTTPS']===1))
				$protocol="https";
			else if(isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT']==='443')
				$protocol="https";
			else
				$protocol="http";
			$baseRequestUri='';
			$requestUri=$_SERVER["REQUEST_URI"];
			$scriptName=$_SERVER["SCRIPT_NAME"];
			$checkLen=min(strlen($requestUri),strlen($scriptName));
			$pos=0;
			while($pos<$checkLen&&$requestUri[$pos]==$scriptName[$pos])
				++$pos;
			if($pos>0)
				$baseRequestUri=substr($requestUri,0,$pos);
			self::$basePath=$protocol."://".$_SERVER['HTTP_HOST'].$baseRequestUri;
		}
		return self::$basePath;
	}
	
	public static function getClientIp()
	{
		if(self::$clientIp===null) {
			if (!empty($_SERVER['HTTP_CLIENT_IP']))
				self::$clientIp=$_SERVER['HTTP_CLIENT_IP'];
			else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
				self::$clientIp=$_SERVER['HTTP_X_FORWARDED_FOR'];
			else
				self::$clientIp=$_SERVER['REMOTE_ADDR'];
		}
		return self::$clientIp;
	}
	
	public static function addParams($params)
	{
		self::$params=array_merge(self::$params,$params);
	}
	
	public static function hasParam($key,$minLen=1)
	{
		$value=self::getParam($key);
		return $value!==null && strlen($value)>=$minLen;
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
	
	public static function getParamArray()
	{
		return array_merge(self::$params,$_GET,$_POST);
	}
	
	public static function getCooke($key,$defaultValue=null)
	{
		if(!isset($_COOKIE[$key]))
			return $defaultValue;
		return $_COOKIE[$key];
	}
	
	public static function moveUploadFile($key, $filename)
	{
		if(!isset($_FILES[$key]))
			return false;
		return move_uploaded_file($_FILES[$key], $filename);
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
}
?>