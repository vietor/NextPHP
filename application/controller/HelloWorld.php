<?php
class HelloWorld extends Controller {
	public function Test($request, $reponse) {
		$reponse.output('<HTML><BODY>'.__METHOD__.'</BODY></HTML>'');
	}
}
?>