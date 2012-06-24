<?php
require_once('Request.php');
require_once('Reponse.php');

class Router {
	public $module;
	public $action;
	public $request;
	public $reponse;
	
	public function __construct() {		
		if (get_magic_quotes_gpc ()) {
			$in = array (&$_GET, &$_POST, &$_COOKIE, &$_FILES );
			while ( (list ( $k, $v ) = each ( $in )) !== false ) {
				foreach ( $v as $key => $val ) {
					if (! is_array ( $val )) {
						$in [$k] [$key] = stripslashes ( $val );
						continue;
					}
					$in [] = & $in [$k] [$key];
				}
			}
			unset ( $in );
		}
		
		$config=Config::getConfig('router');
		$type=$config['type'];
		$key_module=$config['key_module'];
		$key_action=$config['key_action'];
		
		if($type=='URL') {
			$paths=explode('/',array_keys($_GET));
			$this->module=$paths[0];
			$this->action=$paths[1];
			$key=null;
			$params=array();
			for($i=2;$i<count($paths);++$i) {
				if(empty($params[$i]))
					continue;
				if(is_null($key))
					$key=$paths[$i];
				else {
					$params[$key]=$paths[$i];
					$key=null;
				}
			}
			unset($paths);
			$params=array_merge($params,$_POST);
		}
		else if($type=='GET') {
			$this->module=$_GET[$key_module];
			$this->action=$_GET[$key_action];
			unset($_GET[$key_module]);
			unset($_GET[$key_action]);
			$params=array_merge($_GET,$_POST);
		}
		else if($type=='POST') {
			$this->module=$_POST[$key_module];
			$this->action=$_POST[$key_action];
			unset($_POST[$key_module]);
			unset($_POST[$key_action]);
			$params=array_merge($_GET,$_POST);
		}
		else {
			$params=array_merge($_GET,$_POST);
			$this->module=$params[$key_module];
			$this->action=$params[$key_action];
			unset($params[$key_module]);
			unset($params[$key_action]);
		}
		$this->request=new Request($params);
		$this->reponse=new Reponse($request);
	}
	
	public static function getRouter() {
		return new Router();
	}
}
?>