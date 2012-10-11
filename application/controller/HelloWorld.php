<?php
class HelloWorld extends NpController {
	const SESSION_KEY='test-session-key';

	public function Test1() {
		$hostUrl=$this->request->getUrlByBackward();
		$this->reponse->output('<HTML>
				<BODY>
					<center>'.__METHOD__.'</center><p><p>
					<center><a href="Test2">To Test2</a></center><p><p>
					<center><img src="'.$hostUrl.'php-power-black.gif"></center>
				</BODY>
			</HTML>');
		$this->request->setSession(SESSION_KEY,__METHOD__.' at '.time());
	}

	public function Test2() {
		$sessionId=$this->request->getSessionId();
		$sessionValue=$this->request->getSession(SESSION_KEY);
		$this->reponse->output('<HTML>
				<BODY>
					<center>'.__METHOD__.'</center><p><p>
					<center>SessionId: '.$sessionId.'</center>
					<center>SessionValue: '.$sessionValue.'</center>
				</BODY>
			</HTML>');
	}
}
?>