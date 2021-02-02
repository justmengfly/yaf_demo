<?php
namespace Helpers;

trait ApiResponse
{

    /**
     * 成功返回格式
     *
     * @param array $data            
     * @param array $headers
     *            $headers['content-type'] = 'application/json'
     * @return void
     */
    public function success($data = [], $status = "success", $headers = [])
    {
        return $this->respond(0, $status, '', $data, $headers);
    }

    /**
     * 失败返回格式，有失败原因
     *
     * @param [type] $code            
     * @param string $reason            
     * @param array $headers            
     * @return void
     */
    public function failed($code, $status = "failed", $reason = '', $headers = [])
    {
        return $this->respond($code, $status, $reason, [], $headers);
    }

    public function respond($code, $status, $reason, array $data = [], $headers = [])
    {
        $resp = [
            'code' => $code,
            'status' => $status
        ];
        if ($reason) {
            $resp['reason'] = $reason;
        }
        if ($data && ! is_array($data)) {
            $data = json_decode(strval($data), true);
            if ($$data === NULL) {
                throw new \Exception('非json格式', 13);
            }
        }
        
        $response = $this->getResponse();
        $response->setHeader('content-type', 'application/json');
        
        $apiHeader = "";
        if ($data) {
            if (isset($data['code'])) {
                $apiHeader = $apiHeader . strval($data['code']);
            }
            if (isset($data['userid'])) {
                $apiHeader = $apiHeader . "_" . strval($data['userid']);
            } elseif (isset($_REQUEST['userid'])) {
                $apiHeader = $apiHeader . "_" . strval($_REQUEST['userid']);
            }
            if (strlen($apiHeader) > 0) {
                $response->setHeader('Api-Result', $apiHeader);
            }
        }
        
        // headder中输出主要依赖服务的处理时间
        if (isset($GLOBALS['DEPENDENT-REQUEST-TIME'])) {
            $response->setHeader('Dependent-Request-Time', $GLOBALS['DEPENDENT-REQUEST-TIME']);
        }
        if (isset($GLOBALS['DEPENDENT-STATUS'])) {
            $response->setHeader('Dependent-Status', $GLOBALS['DEPENDENT-STATUS']);
        }
        if (isset($GLOBALS['DEPENDENT-URI'])) {
            $response->setHeader('Actual-Request-Url', $GLOBALS['DEPENDENT-URI']);
        }
        
        if ($headers) {
            foreach ($headers as $name => $value) {
                $response->setHeader($name, $value);
            }
        }
        $data = array_merge($resp, $data);
        return $response->setBody(json_encode($data));
    }
}