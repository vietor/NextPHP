<?php
class Reponse {
	public function setCookie ($name, $value, $expire=null, $path=null, $domain=null) {
		$config = Config::getConfig('cookie');
		$expire = is_null($expire) ? time()+$config['expire']*86400 : time()+$expire*68400;
		$path = is_null($path) ? $config['path'] : $path;
		$domain = is_null($domain) ? $config['domain'] : $domain;
		setcookie($name, $value, $expire, $path, $domain);
	}
	
	public function output($content, $contentType=null) {
		if(!is_null($contentType))
			header('content-type: '.$contentType);
		echo $content;
	}
	
	public function htmlLocation($url) {
		echo '
		<!DOCTYPE html>
		<html>
		<head>
		<title>Launching...</title>
		</head>
		<script type="text/javascript">
		window.location = "'.$url.'";
		</script>
		<body style="text-align: center; font-family: Arial, sans-serif;">Launching...</body>
		</html>';
	}
}
?>