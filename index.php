<?php
define('BASEPATH', dirname(__FILE__).'/');
require_once(BASEPATH.'system/core/CoreInit.php');

Dispather::dispath(Router::getRouter());
?>