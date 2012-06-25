<?php
require_once('Model.php');

class Controller {
	public function undefined($request,$reponse) {
		throw new Exception('Undefined module or action');
	}
}
?>