<?php

namespace app\modules;

use \Yaf\Controller_Abstract;

class Base extends Controller_Abstract
{
    /**
     * 默认初始化方法，如果不需要，可以删除掉这个方法
     * 如果这个方法被定义，那么在Controller被构造以后，Yaf会调用这个方法
     */
    public function init() {
        echo "init...\n";
    }
}