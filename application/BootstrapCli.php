<?php


class Bootstrap extends Yaf\Bootstrap_Abstract{

    /**
     * 初始化配置文件
     * 全局配置文件 ,环境配置文件 错误码配置文件等
     */
    public function _initConfig() {
        $globalConfig = Yaf\Application::app()->getConfig();
        Yaf\Registry::set('cliConfig', $globalConfig);
    }

    /**
     * 禁用视图
     */
    public function _initView(\Yaf\Dispatcher $dispatcher) {
        $dispatcher->disableView();
    }
}
