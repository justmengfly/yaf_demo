<?php

use App\Base\Base;
use Validate\TestValidate;

class TestController extends Base
{
    public function IndexAction() {
        $validate = (new TestValidate())->scene('add')->validate();
        if(is_array($validate)){
            $this->failed(-1, $validate);
            return;
        }
        $this->success(["a"=>1]);
    }
}