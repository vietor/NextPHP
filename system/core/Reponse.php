<?php
class Reponse {
	public function setCookie ($name, $value, $expire=null, $path=null, $domain=null) {
		$config = Config::getConfig('cookie');
		$expire = is_null($expire) ? time()+$config['expire']*86400 : time()+$expire*68400;
		$path = is_null($path) ? $config['path'] : $path;
		$domain = is_null($domain) ? $config['domain'] : $domain;
		setcookie($name, $value, $expire, $path, $domain);
	}
}
?>