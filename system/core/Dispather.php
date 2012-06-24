<?php
require_once('Router.php');
require_once('Controller.php');

class Dispather {
	private $controllers=array();
	
	private function getController($module) {
		if(!isset($this->controllers[$module])) {
			$module_file = BASEPATH."application/controller/".$module.".php";
			if(!file_exists($module_file))
				throw new Exception("Not found controller {".$module."} file");
			require_once($module_file);
			if(!class_exists($module))
				throw new Exception("Not found controller {".$module."} class");
			$this->controllers[$module]=new $module();
		}
		return self::$this->controllers[$module];
	}
	
	private static $instance=null;
	
	public static function initialize() {
		if(!is_null(self::$instance))
			return;
		self::$instance=new Dispather();
	}
	
	public static function dispath($router) {
		$module=$router->module;
		$method=$router->action;
		
		$controller=self::$instance->getController($module);
		if(!method_exists($controller,$method))
			throw new Exception("Not found method {".$method."} in controller {".$module."}");

		$controller->$method($router->request,$router->reponse);
	}
}

Dispather::initialize();
?>