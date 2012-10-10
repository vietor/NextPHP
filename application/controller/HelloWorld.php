<?php
class HelloWorld extends NpController {
	public function Test() {
		$hostUrl=$this->request->getUrlByBackward(3);
		$this->reponse->output('<HTML><BODY><center>'.__METHOD__.'</center><p><p><center><img src="'.$hostUrl.'php-power-black.gif"></center></BODY></HTML>');
	}
}
?>