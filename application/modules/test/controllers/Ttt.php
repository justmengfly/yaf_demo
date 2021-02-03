<?php

use App\Base\Base;
use Helpers\Aes;
class TttController extends Base
{

    public function IndexAction() {

      // var_dump($this->response);
      // var_dump(Yaf\Dispatcher::getInstance()->getResponse());
      // exit;
      
      // $data = ['appid'=> 'appid', 'verison'=>'023200', 'reqid'=>'111', 'platform'=>'1', 'ts' => ceil(microtime(true) * 1000)];
      // echo $sign = Aes::createSign($data);
      // exit;

      // $data = ['appid'=> 'appid', 'verison'=>'023200', 'reqid'=>'111', 'platform'=>'1'];
      // $data['sign'] = 'l4lBCE3B_v-ODFeuoxFksl0IUY9ehBnjN_FtU3ESdrn_bMPDsEIxA8IUw0pDJ_kX06P6OOw23in46acBucgceSBE2Vx3Ew52w3FfmRn8IhE';
      // $sign = Aes::checkSign($data);
      // var_dump($sign);
      // exit;
      
      // $obj = new App\Models\User();
      // print_r($obj->getUserData());//Array ( [name] => zhangsan [age] => 18 )
      // exit;
      // $http = $this->getRequest()->getRequest();
      // var_dump($http);
      // var_dump(file_get_contents("php://input"));
      // var_dump($this->requestAll());yaf
      // exit;
      $res = [
          "userid" => 'user',
          "code" => 0
      ];
      // print_r($this->failed(-1,'reason'));
      $this->success($res);
    }
}