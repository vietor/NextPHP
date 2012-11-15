<?php
class NpResponse 
{
	public static function noCache()
	{
		header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}

	public static function setCookie ($name, $value, $expire=null, $path=null, $domain=null)
	{
		$config = NpConfig::get('cookie');
		$expire = is_null($expire) ? (time()+$config->expire) : (time()+$expire);
		$path = is_null($path) ? $config->path : $path;
		$domain = is_null($domain) ? $config->domain : $domain;
		setcookie($name, $value, $expire, $path, $domain);
	}

	public static function output($content, $contentType=null)
	{
		if(!is_null($contentType))
			header('content-type: '.$contentType);
		echo $content;
	}

	public static function location($url)
	{
		header('Location: '.$contentType);
	}

	public static function htmlLocation($url)
	{
		echo '<!DOCTYPE html>
		<html>
		<head>
		<title>Launching...</title>
		</head>
		<script type="text/javascript">
		window.location.href = "'.$url.'";
		</script>
		<body style="text-align: center; font-family: Arial, sans-serif;">Launching...</body>
		</html>';
	}
}
?>