<?php
namespace App\Base;

use Yaf\Controller_Abstract;
use App\Presenters\PresenterInterface;
use App\Library\Http\Response;
use Psr\Http\Message\ResponseInterface;

abstract class BaseController extends Controller_Abstract
{

    /**
     * Headers.
     *
     * <pre>
     * [
     * 'content-type' => 'application/json;charset=utf-8'
     * ]
     * </pre>
     *
     * @var array
     */
    protected $headers = [];

    /**
     * 添加 header.
     *
     * @param string $name            
     * @param mixed $value            
     *
     * @return $this
     */
    public function header(string $name, $value)
    {
        $this->headers[$name] = $value;
        
        return $this;
    }

    /**
     * 获取设置的 headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    public function returnJson($status, $msg, $data = [])
    {
        $res = $this->getResponse();
        $res->setHeader("Content-Type", "application/json;charset=utf-8");
        $data = Yaf_Registry::get('utils')->returnMsg($status, $msg, $data);
        return $res->setBody($data);
    }
}
