<?php
require_once('NpModel.php');
require_once('NpView.php');

class NpController {

	protected function terminate($message=null) {
		if(empty($message))
			throw new NpExitException();
		else
			throw new NpCoreException($message);
	}

	protected function beforeProcess() {
	}

	protected function afterProcess() {
	}

	protected function modelTerminate($code) {
	}

	public function invokeAction($action) {
		$result=null;
		$this->beforeProcess();
		try {
			$result=$this->$action();
			$this->afterProcess();
		}
		catch(NpModelException $e) {
			$this->modelTerminate($e->getCode());
		}
		return $result;
	}

	public static function getInstance($module, $action) {
		if(!class_exists($module)) {
			$module_file = NP_APP_PATH."controller/".$module.".php";
			if(file_exists($module_file))
				require_once($module_file);
			if(!class_exists($module))
				throw new NpCoreException('No module: '.$module);
		}
		$controller=new $module();
		if(!method_exists($controller,$action)) {
			unset($controller);
			throw new NpCoreException('No action: '.$action.' in module: '.$module);
		}
		return $controller;
	}

}
?>