<?php

$args=$argv;
if(!isset($args[1])) exit;
$client=$args[1];

$ds = '/';
$config = dirname(dirname(__FILE__)).$ds.'configs'.$ds.'cron.php';
require_once $config;

$cake = CAKE_CONSOLE_EXEC_FILE;
$app = CAKE_CONSOLE_APP_DIR;

$controller = 'cron';
$action = 'backupLineAction';

$command = "php -f {$cake} {$controller} {$action} {$cake} {$app} {$client} -app {$app}";
system($command);
exit;
