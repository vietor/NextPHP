<?php
//! The class for local HTTP Reponse
class NpResponse
{
	//! Set no cache HTTP header
	public static function noCache()
	{
		header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}

	/*!
	 * @brief Output a text string
	 * @param[in] content     a text string
	 * @param[in] contentType a HTTP HEADER content-type string
	 */
	public static function output($content, $contentType=null)
	{
		if($contentType!==null)
			header('content-type: '.$contentType);
		echo $content;
	}

	//! Web Jump use HTTP HEADER
	public static function location($url)
	{
		header('Location: '.$contentType);
	}

	//! Web Jump use html page
	public static function htmlLocation($url)
	{
		echo '<!DOCTYPE html><html><head><title>Launching...</title></head><script type="text/javascript">window.location.href = "'.$url.'";</script><body style="text-align: center; font-family: Arial, sans-serif;">Launching...</body></html>';
	}
}
?>