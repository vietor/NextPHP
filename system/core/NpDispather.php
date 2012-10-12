<?php
require_once('NpRequest.php');
require_once('NpReponse.php');
require_once('NpController.php');

class NpDispather {
	public function dispath($module,$action,$params) {
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
		$controller->initialize(new NpRequest($params),new NpReponse($request));
		$controller->$action();
	}
}
?>