<?php
require_once('Model.php');

class Controller {
	protected $request;
	protected $reponse;
	
	public function initialize($request,$reponse) {
		$this->request=$request;
		$this->reponse=$reponse;
	}
	
	public function undefined($request,$reponse) {
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
	}
	
	public function loadModel($name) {
		if(!class_exists($name))
			require_once(BASEPATH.'application/model/'.$name.'.php');
		return new $name();
	}
	
	public function loadView($name) {
		if(!class_exists($name))
			require_once(BASEPATH.'application/view/'.$name.'.php');
		return new $name();
	}
}
?>