<?php
namespace App\Base;

use Yaf\Controller_Abstract;
abstract class Base extends Controller_Abstract
{

    public function requestAll()
    {
        $request = $this->getRequest()->getRequest();
        if($this->getRequest()->isPost()) {
            $jsonPost = file_get_contents("php://input");
            if($jsonPost) {
                $request = array_merge($request, json_decode($jsonPost, true));
                //$request = array_replace_recursive($request, json_decode($jsonPost, true));
            }
        }
        return $request;
    }


    /**
     * 成功返回格式
     *
     * @param array $data
     * @param array $headers $headers['content-type'] = 'application/json'
     * @return void
     */
    public function success($data = [], $headers = [])
    {
        return $this->respond(0, "success", '', $data, $headers);
    }

    /**
     * 失败返回格式，有失败原因
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
}
