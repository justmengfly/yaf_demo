<?php

use base\Base;

class TestController extends Base
{
    public function IndexAction() {
        $user = (new models\User())->getUserData();
        $res = [
            "data" => $user,
            "code" => 0
        ];
        $response = $this->getResponse();
        $response->setHeader('content-type', 'application/json');
        $response->setBody(json_encode($res));
    }
}