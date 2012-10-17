<?php
require_once('NpModel.php');
require_once('NpView.php');

class NpController {
	private $request;
	private $reponse;

	public function initialize($request,$reponse) {
		$this->request=$request;
		$this->reponse=$reponse;
	}

	public function getRequest() {
		return $this->request;
	}

	public function getResponse() {
		return $this->reponse;
	}

	public function exitProcess() {
		throw new NpPeacefulException();
	}

	public function loadModel($name) {
		if(!class_exists($name)){
			require_once(NP_BASEPATH.'application/model/'.$name.'.php');
			if(!class_exists($name))
				throw new NpUndefinedException('No found module: '.$name);
		}
		$model=new $name();
		$model->initialize($this);
		return $model;
	}

	public function loadView($name='') {
		if($name=='')
			return new NpViewBase();
		$filename=NP_BASEPATH.'application/view/'.$name.'.php';
		if(!file_exists($filename))
			throw new NpUndefinedException('No found view: '.$name);
		return new NpView($filename);
	}
}
?>