<?php

namespace Validate;

use Api\PhpUtils\Validate\Validate;
use Yaf\Dispatcher;


class BaseValidate extends Validate
{
    /**
     * 参数校验
     * @return array | boolean
     * @throws
     */
    public function validate()
    {
        $params = Dispatcher::getInstance()->getRequest()->getRequest();
        $result = $this->batch()->check($params);
        if (!$result) {
            return $this->error;
        } else {
            return true;
        }
    }

    protected function isPositiveInteger($value)
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        } else {
            return false;
        }
    }

    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}