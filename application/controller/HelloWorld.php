<?php
class HelloWorld extends NpController {
	public function Test() {
		$hostUrl=$this->request->getUrlByBackward();
		$this->reponse->output('<HTML><BODY><center>'.__METHOD__.'</center><p><p><center><img src="'.$hostUrl.'php-power-black.gif"></center></BODY></HTML>');
	}
}
?>