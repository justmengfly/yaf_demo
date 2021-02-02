<?php

namespace App\Plugins;


class Hook extends \Yaf\Plugin_Abstract {

	public function routerStartup(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
	}

	public function routerShutdown(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
	}

	public function dispatchLoopStartup(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
	}

	public function preDispatch(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
		//处理$_POST, $_REQUEST请求,兼容application/json形式
		if(!$_POST && $request->isPost()) {
            $jsonPost = file_get_contents("php://input");
            if($jsonPost) {
				$_POST = json_decode($jsonPost, true);
				$ini = ini_get('request_order');
				if($ini) {
					$arrIni = str_split($ini, 1);
					foreach($arrIni as $type) {
						if(strtoupper($type) === 'G') {
							$_REQUEST = array_merge($_REQUEST, $_GET);
						}elseif(strtoupper($type) === 'P') { 
							$_REQUEST = array_merge($_REQUEST, $_POST);
						}
					}
				}
            }
		}
	}

	public function postDispatch(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
	}

	public function dispatchLoopShutdown(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
	}
}
