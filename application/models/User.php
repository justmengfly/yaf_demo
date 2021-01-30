<?php

namespace models;


class User {
    public function __construct() {
    }   
    
    public function getUserData() {
        return [
            "zhangsan" => 18
        ];
    }
}
