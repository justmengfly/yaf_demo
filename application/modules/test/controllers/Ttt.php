<?php

use App\Base\Base;

/**

{
  "code": 0,
  "status": "success",
  "data": [
    {
      "key1": "user",
      "key2": 0
    }
  ]
}

{
  "code": -1,
  "status": "failed",
  "reason": "reason"
}

curl -X POST \
  http://yaf.com/test/ttt/index\?a\=123 \
  -H 'cache-control: no-cache' \
  -H 'content-type: application/json' \
  -H 'postman-token: 4fb007a9-ae09-6dd1-996b-0134f92b5e2d' \
  -d '{
  "created":"1485277341",
  "key":"this_is_a_secret"
}'
array(1) {
  ["a"]=>
  string(3) "123"
}
string(56) "{
  "created":"1485277341",
  "key":"this_is_a_secret"
}"
array(3) {
  ["a"]=>
  string(3) "123"
  ["created"]=>
  string(10) "1485277341"
  ["key"]=>
  string(16) "this_is_a_secret"
}

 */
class TttController extends Base
{
    public function IndexAction() {

      $http = $this->getRequest()->getRequest();
      var_dump($http);
      var_dump(file_get_contents("php://input"));
      var_dump($this->requestAll());
      exit;
      $res = [
          "key1" => 'user',
          "key2" => 0
      ];
      //print_r($this->failed(-1,'reason'));
        // $this->success($res);
    }
}