<?php
class HelloWorld extends Controller {
	public function Test() {
		$this->reponse->output('<HTML><BODY>'.__METHOD__.'</BODY></HTML>');
	}
}
?>