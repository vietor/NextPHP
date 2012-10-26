<?php
require_once('NpModel.php');
require_once('NpView.php');

class NpController {

	public static function __callStatic($name, $arguments) {
		NpEnvironment::callFuzzyMethod($name, $arguments);
	}

	public function exitProcess() {
		NpEnvironment::safetyExit();
	}

	public function beforeProcess() {
		return true;
	}

	public function afterProcess() {
	}

	public static function getInstance($module, $action) {
		if(!class_exists($module)) {
			$module_file = NP_BASEPATH."application/controller/".$module.".php";
			if(file_exists($module_file))
				require_once($module_file);
			if(!class_exists($module))
				throw new NpUndefinedException('No module: '.$module);
		}
		$controller=new $module();
		if(!method_exists($controller,$action)) {
			unset($controller);
			throw new NpUndefinedException('No action: '.$action.' in module: '.$module);
		}
		return $controller;
	}

}
?>