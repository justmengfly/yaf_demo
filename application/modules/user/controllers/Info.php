<?php

use base\Base;

class InfoController extends Base
{
    public function IndexAction() {
        if(Tool\Http::get()){
            $user = (new models\User())->getUserData();
        }
        $res = [
            "data" => $user,
            "code" => 0
        ];
        $response = $this->getResponse();
        $response->setHeader('content-type', 'application/json');
        $response->setBody(json_encode($res));
    }
}