<?php
class UniqueKey {
	private $mode;
	private $secret;

	public function __construct($mode,$password) {
		$this->mode=$mode;
		$this->secret=$password;
	}

	private function isAES() {
		return $this->mode=="aes";
	}

	public function generate($id) {
		$obj = new stdClass;
		$obj->id = $id;
		$obj->uniqueid = mt_rand(0, 65535).uniqid();
		if($this->isAES()) {
			$cipher = MCRYPT_RIJNDAEL_256;
			$mode   = MCRYPT_MODE_ECB;
		}
		else {
			$cipher = MCRYPT_3DES;
			$mode   = MCRYPT_MODE_ECB;
		}
		return $this->do_encrypt($cipher,$mode,json_encode($obj));
	}

	public function validate($key) {
	if($this->isAES()) {
			$cipher = MCRYPT_RIJNDAEL_256;
			$mode   = MCRYPT_MODE_ECB;
		}
		else {
			$cipher = MCRYPT_3DES;
			$mode   = MCRYPT_MODE_ECB;
		}
		$json=$this->do_decrypt($cipher,$mode,$key);
		if(!($obj=json_decode($json)))
			return false;
		return $obj->id;
	}

	public function do_encrypt($cipher,$mode,$value) {
		return self::base64_url_encode(
				trim(
						mcrypt_encrypt(
								$cipher,
								$this->secret,
								$value,
								$mode,
								mcrypt_create_iv(
										mcrypt_get_iv_size(
												$cipher,
												$mode
										),
										MCRYPT_RAND
								)
						)
				)
		);
	}

	public function do_decrypt($cipher,$mode,$value) {
		return trim(
				mcrypt_decrypt(
						$cipher,
						$this->secret,
						self::base64_url_decode($value),
						$mode,
						mcrypt_create_iv(
								mcrypt_get_iv_size(
										$cipher,
										$mode
								),
								MCRYPT_RAND
						)
				)
		);
	}

	public static function base64_url_encode($input) {
		return strtr(rtrim(base64_encode($input), '='), '+/', '-_');
	}

	public static function base64_url_decode($input) {
		return base64_decode(strtr($input, '-_', '+/'));
	}
}
?>
