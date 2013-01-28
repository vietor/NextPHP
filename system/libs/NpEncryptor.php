<?php
require_once 'NpCrypto.php';

class NpEncryptor
{
	private $crypto;
	private $password;
	private $timeout;

	public function __construct($mode,$password,$timeout)
	{
		$this->crypto=new NpCrypto($mode);
		$this->password=$password;
		$this->timeout=$timeout;
	}
	
	public function encrypt($data, $timeout=0)
	{
		if($timeout==0)
			$timeout=$this->timeout;
		$obj = array();
		$obj['d'] = $data;
		$obj['e'] = $timeout>0?time()+$timeout:0;
		$obj['u'] = uniqid(mt_rand(0, 65535),true);
		return $this->crypto->encrypt($this->password,json_encode($obj));
	}
	
	public function decrypt($data, &$exprie=null)
	{
		$json=$this->crypto->decrypt($this->password, $data);
		if(!($obj=json_decode($json,true)))
			return false;
		if($obj['e']>0 && time()>$obj['e'])
			return false;
		if($exprie!==null)
			$exprie=$obj['e'];
		return $obj['d'];
	}

	public function generateKey($data, $bind='', $timeout=0)
	{
		if($timeout==0)
			$timeout=$this->timeout;
		$obj = array();
		$obj['d'] = $data;
		$obj['b'] = $bind;
		$obj['e'] = $timeout>0?time()+$timeout:0;
		$obj['u'] = uniqid(mt_rand(0, 65535),true);
		return $this->crypto->encrypt($this->password,json_encode($obj));
	}

	public function validateKey($key, $bind='')
	{
		$json=$this->crypto->decrypt($this->password, $key);
		if(!($obj=json_decode($json,true)))
			return false;
		if($obj['b'] != $bind)
			return false;
		if($obj['e']>0 && time()>$obj['e'])
			return false;
		return $obj['d'];
	}
}
?>