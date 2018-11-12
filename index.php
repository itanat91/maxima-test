<?php
define('ROOT', dirname(__FILE__));

require_once(__DIR__ . '/components/Autoloader.php');
$autoloader = new \app\components\Autoloader();
$autoloader->register();

$config = require_once(__DIR__ . '/config/web.php');

(new \app\components\Application($config))->run();