<?php
class NpModel {

	private static $_models=array();

	public static function loadModel($name) {
		if(!array_key_exists($name,self::$_models)) {
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