<?php
class NpFactory {

	private static function newInstance($className, $args=null, $staticConstructor=false)
	{
		if(!class_exists($className)){
			require_once(NP_BASEPATH.'system/libs/'.$className.'.php');
			if(!class_exists($className))
				throw new Exception('Not found class name {'.$className.'}');
		}
		if($staticConstructor){
			if(is_null($args))
				return call_user_func(array($className,"getInstance"));
			else
				return call_user_func_array(array($className,"getInstance"), $args);
		}
		else{
			if(is_null($args))
				return $reflection->newInstance();
			else
				return $reflection->newInstanceArgs(args);
		}
	}

	public static function createCache(){
		$config=NpConfig::getConfig('cache');
		if($config->type=='redis')
			$className='NpRedis';
		else if($config->type=='memcache')
			$className='NpMemcache';
		else if($config->type=='memcached')
			$className='NpMemcached';
		else
			throw new Exception('Unsupport cache type {'.$config->type.'}');
		return self::newInstance($className, array($config->host, $config->port, $config->prefix), true);
	}

	public static function createDatabase(){
		$config=NpConfig::getConfig('database');
		return self::newInstance('NpDbConnection', array($config->type.':dbname='.$config->dbname.';host='.$config->host.';port='.$config->port.';charset='.$config->charset,$config->user,$config->passwd));
	}

	public static function createUniqueKey() {
		$config=NpConfig::getConfig('unique');
		return self::newInstance('NpUniqueKey', array($config->mode,$config->secret,$config->expire), true);
	}

	public static function createWebRequest(){
		return self::newInstance('NpWebRequest');
	}

	public static function createCrypto($type){
		return self::newInstance('NpCrypto', array($type));
	}

	public static function sendMail($toName, $toAddress, $subject, $body, $html=null) {
		class_exists('PHPMailer')
			or require(NP_BASEPATH.'system/libs/Mailer/class.phpmailer.php');
		$config=NpConfig::getConfig('mailer');
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