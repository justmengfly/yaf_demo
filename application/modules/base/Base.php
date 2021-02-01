<?php
namespace App\Base;

use Yaf\Controller_Abstract;
abstract class Base extends Controller_Abstract
{
    /**
     * 成功返回统一格式，data始终为数组，data内容为对象
     *
     * @param array $data
     * @param array $headers $headers['content-type'] = 'application/json'
     * @return void
     */
    public function success($data = [], $headers = [])
    {
        if ($this->arrayDepth($data) == 1 && !empty($data)) {
            $data = [
                $data
            ];
        }
        return $this->respond(0, "success", '', compact('data'), $headers);
    }

    /**
     * 失败返回统一格式，有失败原因，失败返加data为空数组
     *
     * @param [type] $code
     * @param string $reason
     * @param array $headers
     * @return void
     */
    public function failed($code, $reason = '', $headers = [])
    {
        return $this->respond($code, 'failed', $reason, [], $headers);
    }

    public function respond($code, $status, $reason, array $data = [], $headers = [])
    {
        $resp = [
            'code' => $code,
            'status' => $status,
        ];
        if($reason) {
            $resp['reason'] = $reason;
        }
        $data = array_merge($resp, $data);
        $response = $this->getResponse();
        $response->setHeader('content-type', 'application/json');
        if($headers) {
            foreach($headers as $name => $value) {
                $response->setHeader($name, $value);
            }
        }
        return $response->setBody(json_encode($data));
    }

    public function arrayDepth($array)
    {
        if (! is_array($array)) {
            return 0;
        }
        $max_depth = 1;
        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = self::arrayDepth($value) + 1;
                
                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }
        return $max_depth;
    }
}
