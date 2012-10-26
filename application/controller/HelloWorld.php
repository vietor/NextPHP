<?php
class HelloWorld extends NpController {
	const SESSION_KEY='test-session-key';

	public function Test1() {
		$request=NpRequest::getInstance();
		$request->setSession(SESSION_KEY,__METHOD__.' at '.time());
		$hostUrl=$request->getUrlByBackward();
		Np::output('<HTML>
				<BODY>
				<center>'.__METHOD__.'</center><p><p>
				<center><a href="Test2">To Test2</a></center><p><p>
				<center><img src="'.$hostUrl.'php-power-black.gif"></center>
				</BODY>
				</HTML>');
	}

	public function Test2() {
		$sessionId=Np::getSessionId();
		$sessionValue=Np::getSession(SESSION_KEY);
		NpResponse::getInstance()->output('<HTML>
				<BODY>
				<center>'.__METHOD__.'</center><p><p>
				<center>SessionId: '.$sessionId.'</center>
				<center>SessionValue: '.$sessionValue.'</center>
				</BODY>
				</HTML>');
	}

	public function Test3() {
		$request=NpRequest::getInstance();
		$uniqueKey=Np::getUniqueKey();
		$view=NpView::loadView();
		$view->assign('method', __METHOD__);
		$view->assign('sessionId', $request->getSessionId());
		$view->assign('sessionValue', $request->getSession(SESSION_KEY));
		$view->assign('uniqueKey', $uniqueKey->generate($request->getSessionId()));
		return $view->getVariables();
	}
}
?>