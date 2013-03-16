<?php
class HelloWorld extends NpController
{
	public function model()
	{
		$model=NpModel::load('HelloModel');
		$model->test();
	}

	public function view()
	{
		$view=NpView::load('HelloView');
		$view->assign('value', NpRequest::getSessionId());
		return $view;
	}
	
	public function internal()
	{
		$view=NpView::load(NpView::VARIABLE);
		$view->assign('value', NpRequest::getSessionId());
		return $view;
	}

	protected function beforeProcess($action)
	{
		echo 'before process run action:'.$action.'<br/>';
	}

	protected function afterProcess()
	{
		echo 'after process<br/>';
	}
	
	protected function handleProcessBreak($code)
	{
		echo 'handle process break code is '.$code.'<br/>';
	}

	protected function handleProcessCleanup()
	{
		echo 'handle process cleanup<br/>';
	}
}
?>