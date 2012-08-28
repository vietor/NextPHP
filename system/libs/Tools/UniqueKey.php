<?php
require_once('EasyCrypto.php');

class UniqueKey {
	private $crypto;
	private $secret;

	public function __construct($mode,$password) {
		$this->crypto=new EasyCrypto($mode);
		$this->secret=$password;
	}

	public function generate($id, $expire=0) {
		$obj = new stdClass;
		$obj->id = $id;
		$obj->expire = $expire>0?time()+$expire:0;
		$obj->uniqueid = mt_rand(0, 65535).uniqid();
		$this->crypto->encrypt($this->secret,json_encode($obj));
	}

	public function validate($key) {
		$json=$this->crypto->decrypt($this->secret, $key);
		if(!($obj=json_decode($json)))
			return false;
		if($obj->expire>0 && time()>$obj->expire)
			return false;
		return $obj->id;
	}
}
?>
