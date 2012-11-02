<?php
require_once('NpModel.php');
require_once('NpView.php');

class NpController {

	protected function terminateProcess() {
		NpEnvironment::safetyExit();
	}

	public function beforeProcess() {
	}

	public function afterProcess() {
	}

	public static function getInstance($module, $action) {
		if(!class_exists($module)) {
			$module_file = NP_APP_PATH."controller/".$module.".php";
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