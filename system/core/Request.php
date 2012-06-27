<?php
class Request {
	private $params=array();

	public function __construct($params) {
		$this->params=$params;
	}

	public function getMethod() {
		return $_SERVER ['REQUEST_METHOD'];
	}

	public function hasParam($key) {
		return isset($this->params[$key]) && !empty($this->params[$key]);
	}

	public function getParam($key) {
		if(!$this->hasParam($key))
			return false;
		return $this->params[$key];
	}

	public function getCooke($key) {
		if(!isset($_COOKIE[$key]))
			return false;
		return $_COOKIE[$key];
	}

	public function uploadFile($key, $filename) {
		if(!isset($_FILES[$key]))
			return false;
		return move_uploaded_file($_FILES[$key], $filename);
	}
}
?>