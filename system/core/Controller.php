<?php
require_once('Model.php');

class Controller {
	public function undefined($request,$reponse) {
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
	}
}
?>