<?php
use Yaf\Controller_Abstract;

class ErrorController extends Controller_Abstract
{
    use \Helpers\ApiResponse;

    public function errorAction($exception)
    {
        if ($exception->getPrevious() !== NULL) {
            $exception = $exception->getPrevious();
        }
        switch ($exception->getCode()) {
            // 404
            case YAF\ERR\NOTFOUND\MODULE:
            case YAF\ERR\NOTFOUND\CONTROLLER:
            case YAF\ERR\NOTFOUND\ACTION:
            case YAF\ERR\NOTFOUND\VIEW:
                // todo 各项目自定义
                // log
                // LogUtil::ERROR('error code'.$exc->getCode(), '', $exc);
                $this->failed(10, 'failed', 'reason');
                return false;
                break;
            default:
                // todo 各项目自定义
                // log
                $reason = '服务器忙, 请稍后再试[' . $exception->getCode() . ']';
                $this->failed(10, 'failed', $reason);
                return false;
        }
    }
}
