<?php

class NpModelException extends Exception {
	public function __construct($code=0) {
		parent::__construct('',$code);
	}
}

class NpModel {

	protected function terminate($code) {
		throw new NpModelException($code);
	}

	private static $_models=array();

	public static function loadModel($name) {
		if(!isset(self::$_models[$name])) {
			if(!class_exists($name)){
				require_once(NP_APP_PATH.'model/'.$name.'.php');
				if(!class_exists($name))
					throw new NpUndefinedException('No found module: '.$name);
			}
			self::$_models[$name]=new $name();
		}
		return self::$_models[$name];
	}
}
?>