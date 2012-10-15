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

	public function fetch($vars=null) {
		if(!is_null($vars) && is_array($vars))
			$this->data=array_merge($this->data, $vars);
		extract($this->data);
		ob_start();
		include($this->template);
		return ob_get_clean();
	}

	public function display($vars=null) {
		if(!is_null($vars) && is_array($vars))
			$this->data=array_merge($this->data, $vars);
		extract($this->data);
		include($this->template);
	}
}
?>