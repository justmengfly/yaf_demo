<?php
namespace App\Models\demo;

use Api\PhpUtils\Mysql\Base;

class UserIdIndex extends Base
{
    protected static $write;

    protected static $read;

    protected static $db_config_index = 'userbinding';

    protected function getTableName()
    {
        return 'userid_index';
    }

    protected function getPKey()
    {
        return 'userid';
    }

    protected function getWhere($params)
    {
        $where = [];
        if (isset($params['ORDER']) && is_array($params['ORDER']) && !empty($params['ORDER'])) {
            $where['ORDER'] = $params['ORDER'];
        }
        if (isset($params['username']) && !empty($params['username'])) {
            $where['username'] = strval($params['username']);
        }
        return $where;
    }
}
