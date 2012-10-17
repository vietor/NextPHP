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
		$obj->d = $data;
		$obj->b = $bind;
		$obj->e = $expire>0?time()+$expire:0;
		$obj->u = uniqid(mt_rand(0, 65535),true);
		return $this->crypto->encrypt($this->secret,json_encode($obj));
	}

	public function validate($key, $bind='') {
		$json=$this->crypto->decrypt($this->secret, $key);
		if(!($obj=json_decode($json)))
			return false;
		if($obj->b != $bind)
			return false;
		if($obj->e>0 && time()>$obj->e)
			return false;
		return $obj->d;
	}
}
?>
