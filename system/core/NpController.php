<?php
require_once 'NpModel.php';
require_once 'NpView.php';

class NpController
{
	protected function terminate($message=null)
	{
		if(empty($message))
			throw new NpExitException();
		else
			throw new NpCoreException($message);
	}

	protected function beforeProcess()
	{
	}

	protected function afterProcess()
	{
	}

	protected function onModelTerminate($code)
	{
		$this->terminate('Not implement modelTerminate, code='.$code);
	}

	private function invokeAction($action)
	{
		$result=null;
		$this->beforeProcess();
		try {
			$result=$this->$action();
			$this->afterProcess();
		} catch(NpModelException $e) {
			$this->onModelTerminate($e->getCode());
		}
		return $result;
	}
	
	public static function execute($module, $action)
	{
		$moduleFile = NP_APP_PATH."controller/".$module.".php";
		if(!file_exists($moduleFile))
			throw new NpCoreException('No module: '.$module);
		require_once($moduleFile);
		$controller=new $module();
		if(!method_exists($controller,$action))
			throw new NpCoreException('No action: '.$action.' in module: '.$module);
		return $controller->invokeAction($action);
	}

}
?>