<?php

$minPhpVersion = '5.3';

// Check system requirements
if (!defined('CONSOLE')) {
    if (version_compare(phpversion(), $minPhpVersion, '<'))
        exit('This application requires PHP version '.$minPhpVersion.' and higher (current is '.phpversion().').');
    if (!defined('CRYPT_BLOWFISH') || !CRYPT_BLOWFISH)
        exit('This application requires that PHP was compiled with Blowfish support for crypt().');
}

// Setup initial environment
define('LOCALHOST', is_file(dirname(__FILE__).'/.localhost') ? true : false);

if (LOCALHOST === true)
    define('FRAMEWORK_PATH', realpath(dirname(__FILE__).'/../libs/yii-1.1.14'));
else
    define('FRAMEWORK_PATH', realpath(dirname(__FILE__).'/yii'));

if (FRAMEWORK_PATH === false)
    exit('Framework path is incorrect.');

// Make sure the assets directory exists
if (!is_dir(dirname(__FILE__).'/assets'))
    @mkdir(dirname(__FILE__).'/assets');

// Set the locale and timezone
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU.UTF-8');

// On some hosting providers this header is required to set the proper encoding
header('Content-Type: text/html; charset=utf-8');