<?php

use Yaf\Bootstrap_Abstract;
use Yaf\Dispatcher;
use App\Library\Register;
use App\Plugins\Hook;

class Bootstrap extends Bootstrap_Abstract {
    
    /**
     * @var Register
     */
    protected $register;
    
    /**
     * 项目基本初始化操作.
     *
     * @param Dispatcher $dispatcher
     */
    public function _initProject(Dispatcher $dispatcher)
    {
        date_default_timezone_set('PRC');
        //是否返回Response对象, 如果启用, 则Response对象在分发完成以后不会自动输出给请求端, 而是交给程序员自己控制输出.
        //$dispatcher->returnResponse(true);
        $dispatcher->disableView();
    }
    
    /**
     * autoload.
     *
     * @param Dispatcher $dispatcher
     */
    public function _initLoader(Dispatcher $dispatcher)
    {
        $loader = Yaf\Loader::getInstance();
        $loader->import(ROOT_PATH.'/vendor/autoload.php');
    }
    
    /**
     * init container
     *
     * @param Dispatcher $dispatcher
     */
    //     public function _initContainer(Dispatcher $dispatcher)
    //     {
    //         // init Container
    //         $this->register = $register = new Register();
    //         $register->set(Register::class, $register);
    //         $register->alias('services.register', $register);
    //     }
    
    public function _initConfig() {
        //把配置保存起来
        $arrConfig = Yaf\Application::app()->getConfig();
        \Yaf\Registry::set('config', $arrConfig);
    }
    
    public function _initPlugin(Dispatcher $dispatcher) {
        //注册一个插件
        //$objSamplePlugin = new Hook();
        //$dispatcher->registerPlugin($objSamplePlugin);
    }
    
    public function _initRoute(Dispatcher $dispatcher) {
        //在这里注册自己的路由协议,默认使用简单路由
    }
    
    public function _initView(Dispatcher $dispatcher) {
        //在这里注册自己的view控制器，例如smarty,firekylin
    }
    }
    