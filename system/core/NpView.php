<?php
require_once('NpViewBase.php');

class NpView extends NpViewBase {
	private $template;

	public function __construct($template) {
		$this->template=$template;
	}

	public function fetch($vars=null) {
		extract($this->getVariables($vars));
		ob_start();
		include($this->template);
		return ob_get_clean();
	}

	public function display($vars=null) {
		extract($this->getVariables($vars));
		include($this->template);
	}
}
?>