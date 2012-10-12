<?php
class NpModel {
	private $controller;

	public function initialize($controller) {
		$this->controller=$controler;
	}
	
	public function getRequest() {
		return $this->controller->getRequest();
	}
	
	public function getResponse() {
		return $this->controller->getResponse();
	}

	public function getCache() {
		return $this->controller->getCache();
	}

	public function getDatabase() {
		return $this->controller->getDatabase();
	}
}
?>