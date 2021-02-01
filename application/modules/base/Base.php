<?php

namespace base;

use Yaf\Controller_Abstract;

class Base extends Controller_Abstract
{
    public function init() {
        \Yaf\Dispatcher::getInstance()->disableView();
    }
}