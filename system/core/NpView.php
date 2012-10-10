<?php
class NpView {
	private $template;
	private $data=array();

	public function __construct($template) {
		$this->template=$template;
	}

	public function assign($name,$value) {
		$this->data[$name]=$value;
	}

	public function fetch() {
		ob_start();
		include($this->template);
		$message = ob_get_contents();
		ob_end();
		return $message;
	}

	public function display() {
		include($this->template);
	}
}
?>