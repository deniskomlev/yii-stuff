<?php

require_once(dirname(__FILE__).'/bootstrap.php');

$yii = FRAMEWORK_PATH.'/yii.php';

if (LOCALHOST) {
    $config = dirname(__FILE__).'/application/config/development.php';
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
    error_reporting(E_ALL);
} else {
    $config = dirname(__FILE__).'/application/config/production.php';
    defined('YII_DEBUG') or define('YII_DEBUG', false);
    error_reporting(0);
}

require_once($yii);
Yii::createWebApplication($config)->run();