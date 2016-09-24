<?php

/**
 * Run Appliation
 */

define('BASEPATH', __DIR__);
define('SYSPATH', __DIR__.'/system/');
define('APPPATH', __DIR__.'/application/');

require_once SYSPATH.'/Core/Config.php';
require_once SYSPATH.'/Core/Application.php';

$app = new Diode\Core\Application();
$app->run();