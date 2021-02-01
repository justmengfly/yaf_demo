<?php

use APP\Plugins\Hook;


class Bootstrap extends \Yaf\Bootstrap_Abstract {

    public function _initConfig() {
		$arrConfig = \Yaf\Application::app()->getConfig();
        \Yaf\Registry::set('config', $arrConfig);
	}

	public function _initPlugin(\Yaf\Dispatcher $dispatcher) {
		$objSamplePlugin = new Hook();
		$dispatcher->registerPlugin($objSamplePlugin);
	}

	public function _initRoute(\Yaf\Dispatcher $dispatcher) {
		//在这里注册自己的路由协议,默认使用简单路由
	}

    /**
     * 禁用视图
     */
    public function _initView(\Yaf\Dispatcher $dispatcher) {
        $dispatcher->disableView();
    }
}
