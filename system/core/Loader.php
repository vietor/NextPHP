<?php
class Loader {
	private static $tables=array();

	private static function getObject($key){
		if(!isset(self::$tables[$key]))
			return false;
		return self::$tables[$key];
	}

	private static function setObject($key, $object){
		self::$tables[$key]=$object;
	}

	public static function loadCache(){
		$config=Config::getConfig('cache');
		if($config->type=='redis') {
			if(!class_exists('CcRedis'))
				require_once(BASEPATH.'system/libs/Cache/CcRedis.php');
			return CcRedis::getInstance($config->host, $config->port, $config->prefix);
		}
		else if($config->type=='memcache') {
			if(!class_exists('CcMemcache'))
				require_once(BASEPATH.'system/libs/Cache/CcMemcache.php');
			return CcMemcache::getInstance($config->host, $config->port, $config->prefix);
		}
		else if($config->type=='memcached') {
			if(!class_exists('CcMemcached'))
				require_once(BASEPATH.'system/libs/Cache/CcMemcached.php');
			return CcMemcached::getInstance($config->host, $config->port, $config->prefix);
		}
		else
			throw new Exception('Unsupport cache type {'.$config->type.'}');
	}

	public static function loadDatabase(){
		if(!class_exists('DbConnection'))
			require_once(BASEPATH.'system/libs/Database/DbConnection.php');

		$config=Config::getConfig('database');
		if($config->type=='mysql')
			$dsn='mysql:dbname='.$config->dbname.';host='.$config->host.';port='.$config->port.';charset='.$config->charset;
		else if($config->type=='pgsql')
			$dsn='pgsql:dbname='.$config->dbname.';host='.$config->host.';port='.$config->port.';charset='.$config->charset;
		else
			throw new Exception('Unsupport database type {'.$config['type'].'}');

		return new DbConnection($dsn,$config->user,$config->passwd);
	}

	private static function requireTool($name){
		if(!class_exists($name)) {
			$file=BASEPATH.'system/libs/Tools/'.$name.'.php';
			if(!file_exists($file))
				throw new Exception('Unsupport tool type {'.$name.'}');
			require_once($file);
		}
	}

	public static function loadUniqueKey() {
		$name='UniqueKey';
		$object = self::getObject($name);
		if(!$object) {
			self::requireTool($name);
			$config=Config::getConfig('unique');
			$object=new UniqueKey($config->mode,$config->secret);
			self::setObject($name, $object);
		}
		return $object;
	}

	public static function loadWebRequest(){
		self::requireTool('WebRequest');
		return new WebRequest;
	}

	public static function loadSmarty() {
		if(!class_exists('Smarty'))
			require_once(BASEPATH.'system/libs/Smarty/Smarty.class.php');
		return new Smarty;
	}

	public static function Mail($toName, $toAddress, $subject, $body, $html=null) {
		if(!class_exists('PHPMailer'))
			require_once(BASEPATH.'system/libs/Mailer/class.phpmailer.php');
		$config=Config::getConfig('mailer');
		$mail=new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth=$config->SMTPAuth;
		$mail->SMTPSecure=$config->SMTPSecure;
		$mail->Host=$config->Host;
		$mail->Port=$config->Port;
		$mail->Username=$config->Username;
		$mail->Password=$config->Password;
		$mail->SetFrom($config->FromAddress,$config->FromName);
		$mail->AddReplyTo($config->FromAddress,$config->FromName);
		$mail->AddAddress($toAddress,$toName);
		$mail->Subject=$subject;
		if($html==null)
			$mail->Body=$body;
		else{
			$mail->AltBody=$body;
			$mail->MsgHTML($html);
		}
		return $mail->Send();
	}

	public static function loadEasyCrypto($type){
		self::requireTool('EasyCrypto');
		return new EasyCrypto($type);
	}
}
?>