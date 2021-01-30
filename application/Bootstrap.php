<?php

use plugins\Hook;


class Bootstrap extends \Yaf\Bootstrap_Abstract {

    public function _initConfig() {
		//把配置保存起来
		$arrConfig = \Yaf\Application::app()->getConfig();
        \Yaf\Registry::set('config', $arrConfig);
	}

	public function _initPlugin(\Yaf\Dispatcher $dispatcher) {
		//注册一个插件
		$objSamplePlugin = new Hook();
		$dispatcher->registerPlugin($objSamplePlugin);
	}

	public function _initRoute(\Yaf\Dispatcher $dispatcher) {
		//在这里注册自己的路由协议,默认使用简单路由
	}
	
	public function _initView(\Yaf\Dispatcher $dispatcher) {
		//在这里注册自己的view控制器，例如smarty,firekylin
	}
}
