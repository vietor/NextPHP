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

	protected function beforeProcess($action)
	{
	}

	protected function afterProcess()
	{
	}

	protected function handleProcessBreak($modelTerminateCode)
	{
		$this->terminate('Not implement handleProcessBreak, code='.$modelTerminateCode);
	}
	
	protected function handleProcessCleanup()
	{
	}

	private function invokeAction($action)
	{
		$result=null;
		try
		{
			$this->beforeProcess($action);
			try {
				$result=$this->$action();
				$this->afterProcess();
			} catch(NpModelException $e) {
				$this->onModelTerminate($e->getCode());
			}
		} catch(Exception $e) {
			$this->handleProcessCleanup();
			throw $e;
		}
		$this->handleProcessCleanup();
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