<?php
require_once('NpModel.php');
require_once('NpView.php');

class NpController {
	private $request;
	private $reponse;
	private $cache;
	private $database;

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

	public function getCache() {
		if($this->cache===null)
			$this->cache=NpFactory::createCache();
		return $this->cache;
	}

	public function getDatabase() {
		if($this->database===null)
			$this->database=NpFactory::createDatabase();
		return $this->database;
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

	public function loadView($name) {
		$filename=NP_BASEPATH.'application/view/'.$name.'.php';
		if(!file_exists($filename))
			throw new NpUndefinedException('No found view: '.$name);
		return new NpView($name);
	}
}
?>