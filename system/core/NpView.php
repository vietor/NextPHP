<?php
class NpView {
	private $template;
	private $base;
	private $data=array();

	public function __construct($template) {
		$this->base=NP_BASEURL;
		$this->template=$template;
	}

	public function assign($name,$value) {
		$this->data[$name]=$value;
	}

	public function fetch() {
		ob_start();
		include($this->template);
		return ob_get_clean();
	}

	public function display() {
		include($this->template);
	}
}
?>