<?php


namespace Validate;


class TestValidate extends BaseValidate
{
    /**
     * @var array
     * 基础规则
     */
    protected $rule = [
        'ids'       => 'checkIDs',
        'email'     => 'email',
        'age'       => 'between:0,100',
    ];


    protected $scene = [
        'edit'  =>  ['email','age'],
    ];

    public function sceneAdd()
    {
        return $this->only(['ids','email','age'])
            ->append('ids', 'require')
            ->append('email', 'require');
    }


    protected function checkIDs($params)
    {
        //id字符串转为id数组
        $params = explode(',', $params);
        if (empty($params)) {
            return false;
        }
        //每个id只能由字母数字组成
        foreach ($params as $param) {
            if (!$this->regex($param, '/^[A-Za-z0-9]+$/')) {
                return false;
            }
        }
        return true;
    }
}