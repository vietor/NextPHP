<?php
/* You modify NP_SYS_PATH for share framework code */
defined('NP_SYS_PATH') or define('NP_SYS_PATH', dirname(dirname(__FILE__)).'/system/');

defined('NP_APP_PATH') or define('NP_APP_PATH', dirname(dirname(__FILE__)).'/application/');
defined('NP_URL_PATH') or define('NP_URL_PATH',((isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!="off")?"https":"http")."://".$_SERVER['HTTP_HOST'].'/');

class_exists("NpBootstrap") or require_once NP_SYS_PATH.'core/NpBootstrap.php';
?>