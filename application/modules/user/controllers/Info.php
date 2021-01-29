<?php


use app\modules\Base;

class InfoController extends Base
{
    /**
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/yaf_skeleton/index/index/index/name/admin 的时候, 你就会发现不同
     * @param string $name
     * @return bool
     */
    public function indexAction($name = "YiDian") {
        //1. fetch query
        $get = $this->getRequest()->getQuery("get", "default value");

        //2. fetch model
        $model = new SampleModel();

        echo "name: $name \n";
        echo "content:".$model->selectSample()." \n";

        //3. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
        return false;
    }
}