<?php

use APP\Base\Base;

class TestController extends Base
{
    public function IndexAction() {
        $user = (new APP\Models\User())->getUserData();
        $res = [
            "data" => $user,
            "code" => 0
        ];
        $response = $this->getResponse();
        $response->setHeader('content-type', 'application/json');
        $response->setBody(json_encode($res));
    }
}