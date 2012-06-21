<?php
define("__ROOT__", dirname(__FILE__)."/");

// initialize include paths

$include_paths = array(
		"system/core",
		"system/libs",
		"application/model",
		"application/view",
		"application/controller",
		);
foreach ($include_paths as $path)
	set_include_path(get_include_path().PATH_SEPARATOR.__ROOT__.$path);

require_once(__ROOT__."system/core/Model.php");
require_once(__ROOT__."system/core/Controller.php");

// php magic quotes process

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

// dispath controller

$module=$_GET["module"];
$action=$_GET["action"];
$params=$_GET["params"];

$module_file = __ROOT__."application/controller/".$module.".php";
if(!file_exists($module_file))
	throw new Exception("Not found controller {".$module."}");
require_once($module_file);

$controller=new $module(array_merge($params,$_POST));

if(!method_exists($controller, $action))
	throw new Exception("Not found action {".$action."} in controller {".$module."}");
$controller->$action();

?>