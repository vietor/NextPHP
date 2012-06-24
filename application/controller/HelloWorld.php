<?php
class HelloWorld extends Controller {
	public function Test($request, $reponse) {
		var_dump($request);
		var_dump($reponse);
	}
}
?>