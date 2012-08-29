<?php
require_once('EasyCrypto.php');

class UniqueKey {
	private $crypto;
	private $secret;
	private $expire;

	public function __construct($mode,$password,$expire) {
		$this->crypto=new EasyCrypto($mode);
		$this->secret=$password;
		$this->expire=$expire;
	}

	public function generate($id, $expire=0) {
		if($expire==0)
			$expire=$this->expire;
		$obj = new stdClass;
		$obj->id = $id;
		$obj->expire = $expire>0?time()+$expire:0;
		$obj->uniqueid = mt_rand(0, 65535).uniqid();
		return $this->crypto->encrypt($this->secret,json_encode($obj));
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
