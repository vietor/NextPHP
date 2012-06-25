<?php
define('BASEPATH', dirname(__FILE__).'/');
require_once(BASEPATH.'system/core/Config.php');
require_once(BASEPATH.'system/core/LibLoader.php');
require_once(BASEPATH.'system/core/Dispather.php');

Dispather::dispath(Router::getRouter());
?>