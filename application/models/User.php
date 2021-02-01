<?php

namespace models;


class User {
    public function __construct() {
    }   
    
    public function getUserData() {
        return [
            "name" => "zhangsan",
            "age" => 18
        ];
    }
}
