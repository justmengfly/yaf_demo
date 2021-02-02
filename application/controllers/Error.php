<?php
use Yaf\Controller_Abstract;

class ErrorController extends Controller_Abstract
{

    public function errorAction($exception)
    {
        //todo pre
        switch ($exception->getCode()) {
            // 404
            case YAF\ERR\NOTFOUND\MODULE:
            case YAF\ERR\NOTFOUND\CONTROLLER:
            case YAF\ERR\NOTFOUND\ACTION:
            case YAF\ERR\NOTFOUND\VIEW:
                // log
                //LogUtil::ERROR('error code'.$exc->getCode(), '', $exc);
                print_r($exception);
                //todo
                echo json_encode(['status' => 'failed', 'reason' => 'reason', 'code' => -1]);
                return false;
                break;
            default:
                // log
                return false;
        }
    }
}
