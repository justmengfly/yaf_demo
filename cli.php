<?php

/*
 * cli入口脚本
 * cli 配置文件：conf/cli.ini
 * cli bootstrap：application/BootstrapCli.php ( 在cli.ini中配置
 * 默认模块：modules/cli
 * 脚本位置：modules/cli/controllers/xxx.php
 * 调用方式：php cli.php controller action "a=1&b=2"
 * 测试脚本：php cli.php test index "a=1&b=2"
 */
if( !substr(php_sapi_name(), 0, 3) == 'cli' ) {
    die;
}

define('APPLICATION_PATH', dirname(__FILE__));
require APPLICATION_PATH.'/vendor/autoload.php';
$application = new Yaf\Application( APPLICATION_PATH . "/conf/cli.ini");

/**
 * 获取模块/控制器/方法
 */
$module = "cli";
if ($argv[1]) $controller = $argv[1];
if ($argv[2]) $method = $argv[2];
$param = $argv[3]?:[];
if ($param)
{
    $param = convertUrlQuery($param);
}

$application->bootstrap()->getDispatcher()->dispatch( new Yaf\Request\Simple("", $module, $controller,$method,$param) );

function convertUrlQuery($query)
{
    $queryParts = explode('&', $query);
    $params = array();
    foreach ($queryParts as $param) {
        $item = explode('=', $param);
        $params[$item[0]] = $item[1];
    }
    return $params;
}