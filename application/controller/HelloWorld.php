<?php
class HelloWorld extends NpController {
	const SESSION_KEY='test-session-key';

	public function Test1() {
		$hostUrl=$this->getRequest()->getUrlByBackward();
		$this->getResponse()->output('<HTML>
				<BODY>
				<center>'.__METHOD__.'</center><p><p>
				<center><a href="Test2">To Test2</a></center><p><p>
				<center><img src="'.$hostUrl.'php-power-black.gif"></center>
				</BODY>
				</HTML>');
		$this->getRequest()->setSession(SESSION_KEY,__METHOD__.' at '.time());
	}

	public function Test2() {
		$sessionId=$this->getRequest()->getSessionId();
		$sessionValue=$this->getRequest()->getSession(SESSION_KEY);
		$this->getResponse()->output('<HTML>
				<BODY>
				<center>'.__METHOD__.'</center><p><p>
				<center>SessionId: '.$sessionId.'</center>
				<center>SessionValue: '.$sessionValue.'</center>
				</BODY>
				</HTML>');
	}

	public function Test3() {
		$uniqueKey=NpFactory::createUniqueKey();
		$view=$this->loadView();
		$view->assign('method', __METHOD__);
		$view->assign('sessionId', $this->getRequest()->getSessionId());
		$view->assign('sessionValue', $this->getRequest()->getSession(SESSION_KEY));
		$view->assign('uniqueKey', $uniqueKey->generate($this->getRequest()->getSessionId()));
		return $view->getVariables();
	}
}
?>