<?php
require_once('Router.php');
require_once('Controller.php');

class Dispather {
	private $controllers=array();
	
	private function getController($module) {
		if(!isset($this->controllers[$module])) {
			$module_file = BASEPATH."application/controller/".$module.".php";
			if(!file_exists($module_file))
				return false;
			require_once($module_file);
			if(!class_exists($module))
				return false;
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
		if($controller===false) {
			$config=Config::getConfig('router');
			$default_module=$config['default_module'];
			if($default_module!='Controller')
				$controller=self::$instance->getController($default_module);
			if($controller===false)
				$controller=new Controller();
		}
		if(!method_exists($controller,$method)){
			if(!isset($config))
				$config=Config::getConfig('router');
			$method='undefined';
			$default_action=$config['default_action'];
			if($default_action!=$method) {
				if(method_exists($controller,$default_action))
					$method=$default_action;
			}
		}
		$controller->$method($router->request,$router->reponse);
	}
}

Dispather::initialize();
?>