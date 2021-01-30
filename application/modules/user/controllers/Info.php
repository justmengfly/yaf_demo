<?php

use base\Base;

class InfoController extends Base
{
    public function IndexAction() {
        echo Tool\Http::get();
        $user = new \models\User();
        print_r($user->getUserData());
        return false;
    }
}