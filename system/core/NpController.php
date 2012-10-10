<?php
require_once('NpModel.php');
require_once('NpView.php');

class NpController {
	protected $request;
	protected $reponse;

	public function initialize($request,$reponse) {
		$this->request=$request;
		$this->reponse=$reponse;
	}

	public function exitProcess() {
		throw new NpPeacefulException();
	}

	public function loadModel($name) {
		class_exists($name)
			or require_once(NP_BASEPATH.'application/model/'.$name.'.php');
		return new $name();
	}

	public function loadView($name) {
		$filename=NP_BASEPATH.'application/view/'.$name.'php';
		if(!file_exists($filename))
			return false;
		return new NpView($name);
	}
}
?>