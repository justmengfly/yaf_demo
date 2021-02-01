<?php

ini_set("display_errors", "On");

/* 定义这个常量是为了在application.ini中引用*/
define('ROOT_PATH', realpath(__DIR__.'/../'));
define('APP_PATH', realpath(__DIR__.'/../application'));
define('APP_START', microtime(true));

$application = new Yaf\Application( ROOT_PATH . "/conf/application.ini");

$application->bootstrap()->run();
