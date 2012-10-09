<?php
define('BASEPATH', dirname(dirname(__FILE__)).'/');

class_exists("NpBootstrap") 
	or require_once(BASEPATH.'system/core/NpBootstrap.php');

NpConfig::execute();
NpBootstrap::execute();
?>