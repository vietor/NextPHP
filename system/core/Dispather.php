<?php
require_once('Router.php');
require_once('Controller.php');

class Dispather {
	public static $instance;
	private $default_module;
	private $default_action;
	
	public function __construct() {
		$config=Config::getConfig('router');
		$this->default_module=$config['default_module'];
		$this->default_action=$config['default_action'];
	}
	
	private function getController($module, &$method) {
		$controller=false;
		if(!class_exists($module)) {
			$module_file = BASEPATH."application/controller/".$module.".php";
			if($this->default_module!='Controller') {
				if(!file_exists($module_file)) {
					$module=$this->default_module;
					$module_file = BASEPATH."application/controller/".$module.".php";
				}
			}
			if(file_exists($module_file)) {
				require_once($module_file);
				if(class_exists($module))
					$controller=new $module();
			}
		}
		if($controller) {
			if(!method_exists($controller,$method)){
				$method='undefined';
				if($this->default_action!=$method) {
					if(method_exists($controller,$this->default_action))
						$method=$this->default_action;
				}
			}
		}
		else {
			$controller=new Controller();
			$method='undefined';
		}
		return $controller;
	}
	
	public static function dispath($router) {
		$method=$router->action;
		$controller=self::$instance->getController($router->module, $method);
		$controller->initialize($router->request,$router->reponse);
		$controller->$method();
	}
}
?>