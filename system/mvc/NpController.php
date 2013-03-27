<?php
require_once 'NpModel.php';
require_once 'NpView.php';

//! The parent class for MVC's CONTROLLER
/*! 
 * @note CONTROLLER filename is same of it's class name and must store in path application/controller
 * <pre>
 * example:
 *   CONTROLLER name: account
 *   file       name: account.php
 * </pre>
 * ACTION is a public method with CONTROLLER, Returns a VIEW object or base data type
 */
abstract class NpController
{
	/*!
	 * @brief Terminate CONTROLLER process
	 * @param[in] message a text description
	 */
	protected function terminate($message=null)
	{
		if(empty($message))
			throw new NpMvcExitException();
		else
			throw new NpMvcException($message);
	}

	/*!
	 * @brief Dispose before ACTION process, Overloadable
	 * @param[in] action ACTION name
	 */
	protected function beforeProcess($action)
	{
	}

	//! Dispose after ACTION process, Overloadable
	protected function afterProcess()
	{
	}
	
	/*!
	 * @brief Dispose when ACTION break on MODEL terminate, Overloadable
	 * @param[in] code an integer status
	 */
	protected function handleProcessBreak($code)
	{
		$this->terminate('Not implement handleProcessBreak, code='.$code);
	}
	
	//! Dispose when ACTION cleanup, Overloadable
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
				$this->handleProcessBreak($e->getCode());
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
			throw new NpMvcException('No module: '.$module);
		require_once($moduleFile);
		$controller=new $module();
		if(!method_exists($controller,$action))
			throw new NpMvcException('No action: '.$action.' in module: '.$module);
		return $controller->invokeAction($action);
	}

}
?>