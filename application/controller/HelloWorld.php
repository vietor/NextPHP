<?php
class HelloWorld extends NpController {
	public function Test() {
		$this->reponse->output('<HTML><BODY>'.__METHOD__.'</BODY></HTML>');
	}
}
?>