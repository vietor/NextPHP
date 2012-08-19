<?php
class View {
	private $smarty;
	private $template;

	public function __construct($template) {
		$this->smarty=Loader::loadSmarty();
		$this->smarty->template_dir = BASEPATH."application/view/";
		$this->smarty->compile_dir = BASEPATH."temporary/smarty/templates_c/";
		$this->smarty->cache_dir = BASEPATH."temporary/smarty/cache/";
		$this->template=$template;
	}

	public function assign($name,$value) {
		$this->smarty->assign($name,$value);
	}

	public function fetch() {
		return $this->smarty->fetch($this->template);
	}

	public function display() {
		$this->smarty->display($this->template);
	}
}
?>