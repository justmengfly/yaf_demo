<?php
use Yaf\Controller_Abstract;

class ErrorController extends Controller_Abstract
{

    public function errorAction($exception)
    {
        switch ($exception->getCode()) {
            // 404
            //case Yaf\Registry::get(ERROR_CONFIG)->NOT_FOUND->code:
            case YAF\ERR\NOTFOUND\MODULE:
            case YAF\ERR\NOTFOUND\CONTROLLER:
            case YAF\ERR\NOTFOUND\ACTION:
            case YAF\ERR\NOTFOUND\VIEW:
                // log
                echo 'error';
                return false;
                break;
            // 错误页面展示错误
            default:
                // log
                return false;
        }
    }
}
