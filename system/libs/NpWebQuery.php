<?php
//! The class for remote HTTP request
/*!
 * @note create by NpFactory::getLibraryObject('WebQuery')
 */
class NpWebQuery
{
	private $s;

	/*!
	 * @brief Constructor
	 */
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
	
	/*!
	 * @brief Send http GET request
	 * @param[in] url        URL
	 * @param[in] get_params GET's parameters array
	 * @param[in] user       username for HTTP authentication
	 * @param[in] password   password for HTTP authentication
	 * @return result on success or FALSE on failure
	 */
	public function get($url, $get_params = array(), $user="", $passwd="")
	{
		if(!empty($get_params))
			$this->init($url."?".http_build_query($get_params),$user,$passwd);
		else
			$this->init($url,$user,$passwd);
		
		return $this->exec();
	}

	/*!
	 * @brief Send http POST request
	 * @param[in] url          URL
	 * @param[in] get_params   GET's parameters array
	 * @param[in] post_params  POST's parameter data
	 * @param[in] content_type the Content-Type header will be set to multipart/form-data
	 * @param[in] user         username for HTTP authentication
	 * @param[in] password     password for HTTP authentication
	 * @return result on success or FALSE on failure
	 */
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

	/*!
	 * @brief Send http DELETE request
	 * @param[in] url         URL
	 * @param[in] get_params  GET's parameters array
	 * @param[in] post_params POST's parameter data
	 * @param[in] user        username for HTTP authentication
	 * @param[in] password    password for HTTP authentication
	 * @return result on success or FALSE on failure
	 */
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
	
	//! Gets information about the last transfer.
	public function getInfo()
	{
		return curl_getinfo($this->s);
	}
}

?>