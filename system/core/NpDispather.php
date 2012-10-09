<?php
require_once('NpRequest.php');
require_once('NpReponse.php');
require_once('NpController.php');

class NpDispather {
	public function dispath($module,$action,$params) {
		if(!class_exists($module)) {
			$module_file = BASEPATH."application/controller/".$module.".php";
			if(file_exists($module_file))
				require_once($module_file);
			if(!class_exists($module))
				throw new NpUndefinedException('No module: '.$module);
		}
		$controller=new $module();
		if(!method_exists($controller,$action))
			throw new NpUndefinedException('No action: '.$action.' in module: '.$module);

		$request=new NpRequest($params);
		$reponse=new NpReponse($request);
		$controller->initialize($request,$reponse);
		$controller->$action();
	}
}
?>