<?php
require_once 'NpCrypto.php';
//! The class for advanced data crypto
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
	
	/*!
	 * @brief Encrypt a data
	 * @param[in] data    data for encrypt
	 * @param[in] timeout encrypted text string effective seconds
	 * @return encrypted text string
	 */
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

	/*!
	 * @brief Decrypt an encrypted text string
	 * @param[in] data    encrypted text string
	 * @param[out] exprie encrypted text string exprie UNITX TIME
	 * @return origin data
	 */
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
	
	/*!
	 * @brief Generate an unique key
	 * @param[in] data    data for encrypt
	 * @param[in] bind    extra protected text string
	 * @param[in] timeout unique key effective seconds
	 * @return a unique text string
	 */
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
	
	/*!
	 * @brief Validate an unique key
	 * @param[in] key  a unique text string
	 * @param[in] bind extra protected text string
	 * @return origin data
	 */
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