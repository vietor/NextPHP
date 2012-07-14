<?php
class View {
	private $smarty;
	private $template;

	public function __construct($template) {
		$this->smarty=Loader::loadSmarty();
		$this->template=$template;
	}

	public function assign($name,$value) {
		$this->smarty->assign($name,$value);
	}

	public function display() {
		$this->smarty->display($this->template);
	}
}
?>