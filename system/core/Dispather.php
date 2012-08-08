<?php
require_once('Request.php');
require_once('Reponse.php');
require_once('Controller.php');

class Dispather {
	public function dispath($module,$action,$params) {
		if(!class_exists($module)) {
			$module_file = BASEPATH."application/controller/".$module.".php";
			if(file_exists($module_file))
				require_once($module_file);
			if(!class_exists($module))
				throw new UndefinedException('No module: '.$module);
		}
		$controller=new $module();
		if(!method_exists($controller,$action))
			throw new UndefinedException('No action: '.$action.' in module: '.$module);

		$request=new Request($params);
		$reponse=new Reponse($request);
		$controller->initialize($request,$reponse);
		$controller->$action();
	}
}
?>