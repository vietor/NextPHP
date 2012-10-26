<?php
class NpModel {

	public static function __callStatic($name, $arguments) {
		NpEnvironment::callFuzzyMethod($name, $arguments);
	}

	private static $_models=array();

	public static function loadModel($name) {
		if(!isset(self::$_models[$name])) {
			if(!class_exists($name)){
				require_once(NP_BASEPATH.'application/model/'.$name.'.php');
				if(!class_exists($name))
					throw new NpUndefinedException('No found module: '.$name);
			}
			self::$_models[$name]=new $name();
		}
		return self::$_models[$name];
	}
}
?>