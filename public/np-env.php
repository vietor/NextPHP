<?php
/* You can modify NP_SYS_PATH for share framework code */
defined('NP_SYS_PATH') or define('NP_SYS_PATH', dirname(dirname(__FILE__)).'/system/');

defined('NP_APP_PATH') or define('NP_APP_PATH', dirname(dirname(__FILE__)).'/application/');

class_exists("NpFramework") or require_once NP_SYS_PATH.'core/NpFramework.php';
?>