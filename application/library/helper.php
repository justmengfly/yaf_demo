<?php
if (!function_exists('config')) {
    function config($str)
    {
        $arr = explode('.', $str);
        if (!isset($arr[0]) || empty($arr[0]) || !isset($arr[1]) || empty($arr[1])) {
            return false;
        }
        $file = $arr[0];
        $key = $arr[1];
        $conf_file = ROOT_PATH . '/conf/' . $file . '.php';
        if (! file_exists($conf_file)) {
            return false;
        }
        $ret = include $conf_file;
        if (!isset($ret[$key])) {
            return false;
        }
        return $ret[$key];
    }
}
