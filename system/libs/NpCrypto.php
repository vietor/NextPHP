<?php
//! The class for simple text crypto
class NpCrypto
{
	private static $cryptoSupport=array(
			'aes'=>array(
					'cipher'=>MCRYPT_RIJNDAEL_256,
					'mode'=>MCRYPT_MODE_ECB
			),
			'3des'=>array(
					'cipher'=>MCRYPT_3DES,
					'mode'=>MCRYPT_MODE_ECB
			),
	);

	private $cryptoObj;

	public function __construct($type)
	{
		if(!isset(self::$cryptoSupport[$type]))
			throw new Exception('Unsupport crypto type:'.$type);
		$this->cryptoObj=self::$cryptoSupport[$type];
	}

	/*!
	 * @brief Encrypt a text string
	 * @param[in] secret : password
	 * @param[in] content : a text string
	 * @return encrypted text string
	 */
	public function encrypt($secret,$content)
	{
		return self::do_encrypt($this->cryptoObj['cipher'], $this->cryptoObj['mode'], $secret, $content);
	}
	
	/*!
	 * @brief Decrypt an encrypted text string
	 * @param[in] secret : password
	 * @param[in] content : a encrypted text string
	 * @return origin text string
	 */
	public function decrypt($secret,$content)
	{
		return self::do_decrypt($this->cryptoObj['cipher'], $this->cryptoObj['mode'], $secret, $content);
	}

	private function do_encrypt($cipher,$mode,$secret,$value)
	{
		return self::base64_url_encode(
				trim(
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