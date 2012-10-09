<?php
require_once('NpCrypto.php');

class NpUniqueKey {
	private $crypto;
	private $secret;
	private $expire;

	public function __construct($mode,$password,$expire) {
		$this->crypto=new NpCrypto($mode);
		$this->secret=$password;
		$this->expire=$expire;
	}

	public function generate($data, $bind='', $expire=0) {
		if($expire==0)
			$expire=$this->expire;
		$obj = new stdClass;
		$obj->data = $data;
		$obj->bind = $bind;
		$obj->expire = $expire>0?time()+$expire:0;
		$obj->uniqueid = mt_rand(0, 65535).uniqid();
		return $this->crypto->encrypt($this->secret,json_encode($obj));
	}

	public function validate($key, $bind='') {
		$json=$this->crypto->decrypt($this->secret, $key);
		if(!($obj=json_decode($json)))
			return false;
		if($obj->bind != $bind)
			return false;
		if($obj->expire>0 && time()>$obj->expire)
			return false;
		return $obj->data;
	}

	private static $instance;

	public static function getInstance($mode,$password,$expire) {
		if(!is_null(self::$instance))
			return self::$instance;
		self::$instance=new NpUniqueKey($mode,$password,$expire);
		return self::$instance;
	}
}
?>
