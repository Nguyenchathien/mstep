<?php
/**
 * Created by PhpStorm.
 * User: Edward
 * Date: 8/26/2016
 * Time: 5:09 PM
 */

$ds = '/';
$config = dirname(dirname(__FILE__)).$ds.'configs'.$ds.'cron.php';

  require_once $config;

  $cake = CAKE_CONSOLE_EXEC_FILE;
  $app = CAKE_CONSOLE_APP_DIR;

  $controller = 'cron';
  $action = 'importWeatherYahoo';

  $command = "php -f {$cake} {$controller} {$action} -app {$app}";
  system($command);
exit;
