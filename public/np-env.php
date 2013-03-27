<?php
/* You can modify for share framework code */
define('NP_SYS_PATH', dirname(dirname(__FILE__)).'/system/');

/* You can modify for share logic code */
define('NP_APP_PATH', dirname(dirname(__FILE__)).'/application/');

/* Load framework environment */
require_once NP_SYS_PATH.'core/NpBasic.php';
require_once NP_SYS_PATH.'mvc/NpFramework.php';
?>