<?php
class Controller {
	
	private $params;
	
	public function __construct($params) {
		$this->params=$params;
	}
	
	public function getParam($key) {
		return $this->params[$key];
	}
	
	public function getCooke($key) {
		return $_COOKIE[$key];
	}
	
	public function storeFile($key, $filename) {
		if(!isset($_FILES[$key]))
			return false;
		return move_uploaded_file($_FILES[$key], $filename);
	}
	
	public function Undefined() {
		throw Exception("Undefined Controller {".__CLASS__."}");
	}
}
?>