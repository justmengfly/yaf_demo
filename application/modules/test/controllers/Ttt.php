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
 */
class TttController extends Base
{
    public function IndexAction() {
        $res = [
            "key1" => 'user',
            "key2" => 0
        ];
        $this->failed(-1,'reason');
        // $this->success($res);
    }
}