<?php
namespace App\Services\demo;

use Api\PhpUtils\Mongo\Doc;

class DocService
{
    public function getDocListByIds($ids)
    {
        $doc = new Doc();
        $ret = $doc->getDocListByIds($ids);
        var_dump($ret);
        exit;
    }
}
