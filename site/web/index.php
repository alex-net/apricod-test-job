<?php

// закомментируйте следующие две строки при использовании в рабочем режиме
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');

$config = require __DIR__ . '/../config.php';
(new yii\web\Application($config))->run();