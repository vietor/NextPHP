<?php
class NpFactory
{
	private static function newInstance($className, $args=null, $staticConstructor=false)
	{
		if(!class_exists($className)){
			require_once(NP_SYS_PATH.'libs/'.$className.'.php');
			if(!class_exists($className))
				throw new Exception('Not found class name {'.$className.'}');
		}
		if($staticConstructor){
			if($args===null)
				return call_user_func(array($className,"getInstance"));
			else
				return call_user_func_array(array($className,"getInstance"), $args);
		}
		else{
			$reflection=new ReflectionClass($className);
			if($args===null)
				return $reflection->newInstance();
			else
				return $reflection->newInstanceArgs($args);
		}
	}

	private static $_cache;
	public static function getCache()
	{
		if(self::$_cache===null){
			$config=NpConfig::get('cache');
			if($config->type=='redis')
				$className='NpRedis';
			else if($config->type=='memcache')
				$className='NpMemcache';
			else if($config->type=='memcached')
				$className='NpMemcached';
			else
				throw new Exception('Unsupport cache type {'.$config->type.'}');
			self::$_cache=self::newInstance($className, array($config->host, $config->port, $config->prefix, $config->timeout), true);
		}
		return self::$_cache;
	}

	private static $_database;
	public static function getDatabase()
	{
		if(self::$_database===null){
			$config=NpConfig::get('database');
			self::$_database= self::newInstance('NpDbConnection', array($config->type.':dbname='.$config->dbname.';host='.$config->host.';port='.$config->port.';charset='.$config->charset,$config->user,$config->passwd));
		}
		return self::$_database;
	}

	private static $_uniqueKey;
	public static function getUniqueKey()
	{
		if(self::$_uniqueKey===null){
			$config=NpConfig::get('unique');
			self::$_uniqueKey=self::newInstance('NpUniqueKey', array($config->mode,$config->secret,$config->expire));
		}
		return self::$_uniqueKey;
	}

	public static function newWebRequest()
	{
		return self::newInstance('NpWebRequest');
	}

	public static function newCrypto($type)
	{
		return self::newInstance('NpCrypto', array($type));
	}

	public static function sendMail($toName, $toAddress, $subject, $body, $html=null)
	{
		class_exists('PHPMailer') or require_once(NP_SYS_PATH.'libs/Mailer/class.phpmailer.php');
		$config=NpConfig::get('mailer');
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
		else {
			$mail->AltBody=$body;
			$mail->MsgHTML($html);
		}
		return $mail->Send();
	}
}
?>