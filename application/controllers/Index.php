<?php

use Yaf\Controller_Abstract;

class IndexController extends Controller_Abstract
{
    use \Helpers\ApiResponse;

    public function IndexAction() {
        $this->success();
    }
}
