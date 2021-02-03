<?php
namespace App\Base;

use Helpers\ApiResponse;
use Yaf\Controller_Abstract;
use Helpers\Aes;

abstract class Base extends Controller_Abstract
{
    use ApiResponse;
    
    /**
     * 校验签名
     *
     * @var boolean
     */
    protected $needSgin = true;
    
    /**
     * 校验SESSION
     *
     * @var boolean
     */
    protected $needLogin = false;
    
    public function init() {
        if ($this->needSgin) {
            $data = $this->getRequest()->getRequest();
            if(! Aes::checkSign($data)) {
                // echo 3;exit;
                throw new \Exception('签名校验失败', 15);
                // var_dump($ret);exit;
            }
        }
        
        if ($this->needLogin) {
            
        }
    }
}
