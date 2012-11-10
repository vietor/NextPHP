<?php
class HelloWorld extends NpController
{
	const SESSION_KEY='test-session-key';

	public function Test1()
	{
		NpRequest::setSession(self::SESSION_KEY,__METHOD__.' at '.time());
		$hostUrl=NpRequest::getUrlByBackward();
		NpResponse::output('<HTML>
				<BODY>
				<center>'.__METHOD__.'</center><p><p>
				<center><a href="Test2">To Test2</a></center><p><p>
				<center><img src="'.$hostUrl.'php-power-black.gif"></center>
				</BODY>
				</HTML>');
	}

	public function Test2()
	{
		NpResponse::output('<HTML>
				<BODY>
				<center>'.__METHOD__.'</center><p><p>
				<center>SessionId: '.NpRequest::getSessionId().'</center>
				<center>SessionValue: '.NpRequest::getSession(self::SESSION_KEY).'</center>
				</BODY>
				</HTML>');
	}

	public function Test3()
	{
		$view=NpView::loadView();
		$view->assign('method', __METHOD__);
		$view->assign('sessionId', NpRequest::getSessionId());
		$view->assign('sessionValue', NpRequest::getSession(self::SESSION_KEY));
		$view->assign('uniqueKey', NpFactory::getUniqueKey()->generate(NpRequest::getSessionId()));
		return $view->getVariables();
	}
	
	public function Test4()
	{
		$model=NpModel::loadModel('HelloModel');
		$model->test();
	}
	
	public function Test5()
	{
		$view=NpView::loadView('HelloView');
		$view->assign('method', __METHOD__);
		$view->assign('sessionId', NpRequest::getSessionId());
		$view->assign('sessionValue', NpRequest::getSession(self::SESSION_KEY));
		$view->assign('uniqueKey', NpFactory::getUniqueKey()->generate(NpRequest::getSessionId()));
		$view->display();
	}

	protected function beforeProcess()
	{
		echo __METHOD__.'-1111111';
	}

	protected function afterProcess()
	{
		echo __METHOD__.'-2222222';
	}
	
	protected function modelTerminate($code)
	{		
		$this->Test5();
	}
}
?>