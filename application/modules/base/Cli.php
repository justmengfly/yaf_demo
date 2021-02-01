<?php


namespace APP\Base;


use Yaf\Controller_Abstract;

class Cli extends Controller_Abstract
{
    public function init() {
        \Yaf\Dispatcher::getInstance()->disableView();
    }
}