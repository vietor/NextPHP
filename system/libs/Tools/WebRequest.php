<?php

class WebRequest
{
	protected $s;
	
	function __construct()
	{
	}
	
	function __destruct()
	{
		@curl_close($this->s);
	}
	
	protected function init($url = "", $user="", $passwd="")
	{
		@curl_close($this->s);

		$this->s = curl_init($url);
		curl_setopt($this->s, CURLOPT_RETURNTRANSFER, 1);

		if(strcasecmp(substr($url,0,5),"https")==0) {
			curl_setopt($this->s, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($this->s, CURLOPT_SSL_VERIFYHOST, 2);
		}
		if(!empty($user) && !empty($passwd)) {
			curl_setopt($this->s, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($this->s, CURLOPT_USERPWD, $user.":".$passwd);
		}
	}
	
	protected function exec()
	{
		return curl_exec($this->s);
	}
	
	public function get($url, $get_params = array(), $user="", $passwd="")
	{
		if(!empty($get_params))
			$this->init($url."?".http_build_query($get_params),$user,$passwd);
		else
			$this->init($url,$user,$passwd);
		
		return $this->exec();
	}
	
	public function post($url, $get_params = array(), $post_params = array(), $content_type="", $user="", $passwd="")
	{
		if(!empty($get_params))
			$this->init($url."?".http_build_query($get_params),$user,$passwd);
		else
			$this->init($url,$user,$passwd);
		
		if(is_string($post_params))
		{
			$data_string=$post_params;
			if(empty($content_type))
			{
				curl_setopt($this->s, CURLOPT_POST, true);
				curl_setopt($this->s, CURLOPT_POSTFIELDS, $data_string);
			}
			else
			{
				curl_setopt($this->s, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($this->s, CURLOPT_POSTFIELDS, $data_string);
				curl_setopt($this->s, CURLOPT_HTTPHEADER, array(
						'Content-Type: '.$content_type,
						'Content-Length: ' . strlen($data_string)));
			}
		}
		else 
		{
			$data_string=http_build_query($post_params);
			curl_setopt($this->s, CURLOPT_POST, true);
			curl_setopt($this->s, CURLOPT_POSTFIELDS, $data_string);
		}		
		return $this->exec();
	}
	
	public function delete($url, $get_params = array(), $post_params = array(), $user="", $passwd="")
	{
		if(!empty($get_params))
			$this->init($url."?".http_build_query($get_params),$user,$passwd);
		else
			$this->init($url,$user,$passwd);
		
		$data_string=http_build_query($post_params);
		curl_setopt($this->s, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($this->s, CURLOPT_POSTFIELDS, $data_string);
		
		return $this->exec();
	}
	
	public function getInfo()
	{
		return curl_getinfo($this->s);
	}
}

?>