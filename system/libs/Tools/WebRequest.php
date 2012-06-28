<?php
class WebRequest
{
	protected $s;

	function __construct() {
	}

	function __destruct() {
		@curl_close($this->s);
	}

	protected function init($url = "", $user="", $passwd="") {
		@curl_close($this->s);

		$this->s = curl_init($url);
		curl_setopt($this->s, CURLOPT_RETURNTRANSFER, 1);
		if(!empty($user) && !empty($passwd)) {
			curl_setopt($this->s, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($this->s, CURLOPT_USERPWD, $user.":".$passwd);
		}
	}

	protected function exec() {
		return curl_exec($this->s);
	}

	public function get($url, $get_params = array(), $user="", $passwd="") {
		if(!empty($get_params))
			$this->init($url."?".http_build_query($get_params),$user,$passwd);
		else
			$this->init($url,$user,$passwd);
		return $this->exec();
	}

	public function post($url, $get_params = array(), $post_params = array(), $user="", $passwd="") {
		if(!empty($get_params))
			$this->init($url."?".http_build_query($get_params),$user,$passwd);
		else
			$this->init($url,$user,$passwd);
		curl_setopt($this->s, CURLOPT_POST, true);
		curl_setopt($this->s, CURLOPT_POSTFIELDS, $post_params);
		return $this->exec();
	}

	public function delete($url, $get_params = array(), $post_params = array(), $user="", $passwd="") {
		if(!empty($get_params))
			$this->init($url."?".http_build_query($get_params),$user,$passwd);
		else
			$this->init($url,$user,$passwd);
		curl_setopt($this->s, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($this->s, CURLOPT_POSTFIELDS, $post_params);
		return $this->exec();
	}

	public function getInfo() {
		return curl_getinfo($this->s);
	}
}
?>