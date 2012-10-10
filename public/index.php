<?php
defined('NP_BASEPATH')
	or define('NP_BASEPATH', dirname(dirname(__FILE__)).'/');
defined('NP_BASEURL')
	or define('NP_BASEURL',(($_SERVER['HTTPS']&&$_SERVER['HTTPS']!="off")?"https":"http")."://".$_SERVER['HTTP_HOST'].'/');

class_exists("NpBootstrap") 
	or require_once(NP_BASEPATH.'system/core/NpBootstrap.php');

NpConfig::execute();
NpBootstrap::execute();
?>