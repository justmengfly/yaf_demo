<?php

use APP\Base\Cli;

class TestController extends Cli
{
    public function IndexAction() {
        $request = $this->getRequest();
        $params = $request->getParams();

        $res = [
            "data" => $params,
            "code" => 0
        ];
        $response = $this->getResponse();
        $response->setBody(json_encode($res));
    }
}