<?php
//! The class for simple text crypto
class NpCrypto
{
	private static $cryptoSupport=array(
			'aes'=>array(
					'cipher'=>MCRYPT_RIJNDAEL_256,
					'mode'=>MCRYPT_MODE_ECB,
					'length'=>32,
			),
			'3des'=>array(
					'cipher'=>MCRYPT_3DES,
					'mode'=>MCRYPT_MODE_ECB,
					'length'=>16,
			),
	);

	private $cryptoObj;

	public function __construct($type)
	{
		if(!isset(self::$cryptoSupport[$type]))
			throw new Exception('Unsupport crypto type:'.$type);
		$this->cryptoObj=self::$cryptoSupport[$type];
	}

	private function fixSecret($secret)
	{
		$length=strlen($secret);
		if($this->cryptoObj['length']<$length)
			$password=substr($secret,0,$this->cryptoObj['length']);
		else if($this->cryptoObj['length']>$length)
			$password=substr($secret.md5($secret),0,$length);
		else
			$password=$secret;
		return $password;
	}

	/*!
	 * @brief Encrypt a text string
	* @param[in] secret  password string
	* @param[in] content a text string
	* @return encrypted text string
	*/
	public function encrypt($secret,$content)
	{
		return self::do_encrypt($this->cryptoObj['cipher'], $this->cryptoObj['mode'], $this->fixSecret($secret), $content);
	}

	/*!
	 * @brief Decrypt an encrypted text string
	* @param[in] secret  password string
	* @param[in] content a encrypted text string
	* @return origin text string
	*/
	public function decrypt($secret,$content)
	{
		return self::do_decrypt($this->cryptoObj['cipher'], $this->cryptoObj['mode'], $this->fixSecret($secret), $content);
	}

	private function do_encrypt($cipher,$mode,$secret,$value)
	{
		return self::base64_url_encode(
				mcrypt_encrypt(
						$cipher,
						$secret,
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
		);
	}

	private function do_decrypt($cipher,$mode,$secret,$value)
	{
		return trim(
				mcrypt_decrypt(
						$cipher,
						$secret,
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

	private static function base64_url_encode($input)
	{
		return strtr(rtrim(base64_encode($input), '='), '+/', '-_');
	}

	private static function base64_url_decode($input)
	{
		return base64_decode(strtr($input, '-_', '+/'));
	}

}
?>