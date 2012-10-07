<?php
require_once('Model.php');
require_once('View.php');

class Controller {
	protected $request;
	protected $reponse;

	public function initialize($request,$reponse) {
		$this->request=$request;
		$this->reponse=$reponse;
	}

	public function loadModel($name) {
		class_exists($name)
			or require(BASEPATH.'application/model/'.$name.'.php');
		return new $name();
	}

	public function loadView($name) {
		$filename=BASEPATH.'application/view/'.$name;
		if(!file_exists($filename))
			return false;
		return new View($name);
	}
}
?>