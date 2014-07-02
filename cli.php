<?php
// composerのautoloadを読み込み
require_once __DIR__.'/config.php';
$loader = require __DIR__ . DS . 'vendor' . DS . 'autoload.php';
$loader->add('WpMoving', __DIR__.'/src');
$loader->register();
set_time_limit(0);
use WpMoving\Application;
$cli_app = new Application();
$cli_app->run();