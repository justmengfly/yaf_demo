<?php

$config = array(
    'debug' => false,
    'mode' => 'development',
    'extension' => 'tideways_xhprof',
    'save.handler' => 'mongodb',
    'db.host' => 'mongodb://10.126.150.23:27017',
    'db.db' => 'xhprof',
    'db.options' => array(),
    'date.format' => 'Y-m-d H:i:s',
    'detail.count' => 6,
    'page.limit' => 25,

    // Profile 1 in 100 requests.
    // You can return true to profile every request.
    'profiler.enable' => function () {
        // 暂时关闭xhgui性能监控
//        if (strpos($_SERVER['SCRIPT_NAME'], 'Action.php') !== false)
//        {
//            return true;
//        }
        return false;
    },

    'profiler.simple_url' => function ($url) {
        return preg_replace('/\=\d+/', '', $url);
    },

    //'/home/admin/www/xhgui/webroot','F:/phpPro'
    'profiler.filter_path' => array()
);

if (!extension_loaded('tideways_xhprof'))
{
    error_log('xhgui - extension tideways_xhprof must be loaded');
    return;
}

if ($config['debug'])
{
    ini_set('display_errors', 1);  // 该选项设置是否将错误信息作为输出的一部分显示到屏幕，或者对用户隐藏而不显示。
}

// 指定监控的目录
if (is_array($config['profiler.filter_path']) && in_array($_SERVER['DOCUMENT_ROOT'], $config['profiler.filter_path']))
{
    return;
}

if ((!extension_loaded('mongo') && !extension_loaded('mongodb')) && $config['save.handler'] === 'mongodb')
{
    error_log('xhgui - extension mongo not loaded');
    return;
}

if (!shouldRun())
{
    return;
}

if (!isset($_SERVER['REQUEST_TIME_FLOAT']))
{
    $_SERVER['REQUEST_TIME_FLOAT'] = microtime(true);
}

if ($config['extension'] == 'tideways_xhprof' && extension_loaded('tideways_xhprof'))
{
    tideways_xhprof_enable(TIDEWAYS_XHPROF_FLAGS_MEMORY | TIDEWAYS_XHPROF_FLAGS_MEMORY_MU | TIDEWAYS_XHPROF_FLAGS_MEMORY_PMU | TIDEWAYS_XHPROF_FLAGS_CPU);
}
else
{
    error_log("Please check the extension name in config.php, you can use the 'php -m' command.");
    return;
}

//注册一个会在php中止时执行的函数
register_shutdown_function(
    function () {
        $data['profile'] = tideways_xhprof_disable();

        // ignore_user_abort(true) allows your PHP script to continue executing, even if the user has terminated their request.
        // Further Reading: http://blog.preinheimer.com/index.php?/archives/248-When-does-a-user-abort.html
        // flush() asks PHP to send any data remaining in the output buffers. This is normally done when the script completes, but
        // since we're delaying that a bit by dealing with the xhprof stuff, we'll do it now to avoid making the user wait.
        ignore_user_abort(true);
        flush();

        $uri = array_key_exists('REQUEST_URI', $_SERVER) ? $_SERVER['REQUEST_URI'] : null;
        if (empty($uri) && isset($_SERVER['argv']))
        {
            $cmd = basename($_SERVER['argv'][0]);
            $uri = $cmd . ' ' . implode(' ', array_slice($_SERVER['argv'], 1));
        }

        $time = array_key_exists('REQUEST_TIME', $_SERVER) ? $_SERVER['REQUEST_TIME'] : time();
        $requestTimeFloat = explode('.', $_SERVER['REQUEST_TIME_FLOAT']);
        if (!isset($requestTimeFloat[1]))
        {
            $requestTimeFloat[1] = 0;
        }

        $requestTs = new MongoDB\BSON\UTCDateTime($time * 1000);
        $requestTsMicro = new MongoDB\BSON\UTCDateTime($_SERVER['REQUEST_TIME_FLOAT'] * 1000);

        $data['meta'] = array(
            'url' => $uri,
            'SERVER' => $_SERVER,
            'get' => $_GET,
            'env' => $_ENV,
            'simple_url' => simpleUrl($uri),
            'request_ts' => $requestTs,
            'request_ts_micro' => $requestTsMicro,
            'request_date' => date('Y-m-d', $time),
        );
        // 保存数据到MongoDB
        save2mongo($data);
    }
);

function simpleUrl($url)
{
    global $config;
    $callable = $config['profiler.simple_url'];
    if (is_callable($callable))
    {
        return call_user_func($callable, $url);
    }
    return preg_replace('/\=\d+/', '', $url);
}

function shouldRun()
{
    global $config;
    $callback = $config['profiler.enable'];
    if (!is_callable($callback))
    {
        return false;
    }
    return (bool)$callback();
}

function save2mongo($data)
{
    global $config;
    $manager = new MongoDB\Driver\Manager($config['db.host']);
    $bulk = new MongoDB\Driver\BulkWrite();
    try
    {
        $bulk->insert($data);
        $writeConcern = new MongoDB\Driver\WriteConcern(1, 100);
        $manager->executeBulkWrite($config['db.db'] . '.results', $bulk, $writeConcern);
    } catch (Exception $e)
    {
        error_log('xhgui - ' . $e->getMessage());
    }
}
