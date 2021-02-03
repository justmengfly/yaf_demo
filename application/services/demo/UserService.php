<?php
namespace App\Services\demo;

use App\Models\demo\UserIdIndex;

class UserService
{
    public function getUserIdByUsername($username)
    {
        $userid_index = UserIdIndex::getInstance();
        $params = ['username' => $username];
        $column = ['userid'];
        $ret = $userid_index->get($params, $column);
        var_dump($ret);
        exit;
    }
}
