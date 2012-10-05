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
		if($config->type=='redis')
			$className='CcRedis';
		else if($config->type=='memcache')
			$className='CcMemcache';
		else if($config->type=='memcached')
			$className='CcMemcached';
		else
			throw new Exception('Unsupport cache type {'.$config->type.'}');

		class_exists($className)
			or require(BASEPATH.'system/libs/Cache/'.$className.'.php');
		return call_user_func_array(array($className,'getInstance'), array($config->host, $config->port, $config->prefix));
	}

	public static function loadDatabase(){
		static $supported=array('mysql','pgsql');
		
		class_exists('DbConnection')
			or require(BASEPATH.'system/libs/Database/DbConnection.php');
		
		$config=Config::getConfig('database');
		if(!in_array($config->type, $supported))
			throw new Exception('Unsupport database type {'.$config['type'].'}');
		$dsn=$config->type.':dbname='.$config->dbname.';host='.$config->host.';port='.$config->port.';charset='.$config->charset;

		return new DbConnection($dsn,$config->user,$config->passwd);
	}

	private static function requireTool($name){
		if(!class_exists($name)) {
			$file=BASEPATH.'system/libs/Tools/'.$name.'.php';
			if(!file_exists($file))
				throw new Exception('Unsupport tool type {'.$name.'}');
			require($file);
		}
	}

	public static function loadUniqueKey() {
		$name='UniqueKey';
		$object = self::getObject($name);
		if(!$object) {
			self::requireTool($name);
			$config=Config::getConfig('unique');
			$object=new UniqueKey($config->mode,$config->secret,$config->expire);
			self::setObject($name, $object);
		}
		return $object;
	}

	public static function loadWebRequest(){
		self::requireTool('WebRequest');
		return new WebRequest;
	}
	
	public static function loadEasyCrypto($type){
		self::requireTool('EasyCrypto');
		return new EasyCrypto($type);
	}

	public static function loadSmarty() {
		class_exists('Smarty')
			or require(BASEPATH.'system/libs/Smarty/Smarty.class.php');
		return new Smarty;
	}

	public static function Mail($toName, $toAddress, $subject, $body, $html=null) {
		class_exists('PHPMailer')
			or require(BASEPATH.'system/libs/Mailer/class.phpmailer.php');
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
}
?>