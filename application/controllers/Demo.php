<?php

use Yaf\Controller_Abstract;
use Api\PhpUtils\Http\Request;
use Api\PhpUtils\Redis\Codis;
use Api\PhpUtils\Session\Session;
use App\Services\demo\UserService;
use App\Services\demo\DocService;

class DemoController extends Controller_Abstract
{
    public function requestAction()
    {
        $request = new Request();
        $urls = [
            0 => 'http://10.103.17.132:8007/adserver/goodsAds',
            1 => 'http://10.103.17.132:8007/adserver/goodsAds',
            2 => 'http://10.103.17.132:8007/adserver/goodsAds'
        ];
        $options = [
            0 => [
                'headers' => [],
                'query' => [
                    'docIdList' => '0SQ0d3dH',
                    'appId' => 'pro',
                    'platform' => 0
                ]
            ],
            1 => [
                'headers' => [],
                'query' => [
                    'docIdList' => '0SQ0d3dH',
                    'appId' => 'pro',
                    'platform' => 0
                ]
            ],
            2 => [
                'headers' => [],
                'query' => [
                    'docIdList' => '0SQ0d3dH',
                    'appId' => 'pro',
                    'platform' => 0
                ]
            ]
        ];
        $concurrency_ret = $request->concurrencyGet($urls, $options);
        var_dump($concurrency_ret);

        $urls = [
            0 => '10.103.35.194:8080/lock-screen/list',
            1 => '10.103.35.194:8080/lock-screen/list',
            2 => '10.103.35.194:8080/lock-screen/list'
        ];
        $options = [
            0 => [
                'headers' => [
                    'Content-type' => 'application/json',
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3100.0 Safari/537.36'
                ],
                'json' => [
                    "bizid" => "YDZX",
                    "uid" => "765073697",
                    "platform" => "1",
                    "appid" => "hipu"
                ]
            ],
            1 => [
                'headers' => [
                    'Content-type' => 'application/json',
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3100.0 Safari/537.36'
                ],
                'json' => [
                    "bizid" => "YDZX",
                    "uid" => "765073697",
                    "platform" => "1",
                    "appid" => "hipu"
                ]
            ],
            2 => [
                'headers' => [
                    'Content-type' => 'application/json',
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3100.0 Safari/537.36'
                ],
                'json' => [
                    "bizid" => "YDZX",
                    "uid" => "765073697",
                    "platform" => "1",
                    "appid" => "hipu"
                ]
            ]
        ];
        $concurrency_post_ret = $request->concurrencyPost($urls, $options);
        var_dump($concurrency_post_ret);
        exit;
    }

    public function mysqlAction()
    {
        $user_service = new UserService();
        $user_service->getUserIdByUsername('HG_4C20B103B519');
    }

    public function docAction()
    {
        $doc_service = new DocService();
        $ids = explode(',', '0SHaVQo7,0SvYn7Md');
        $doc_service->getDocListByIds($ids);
    }

    public function redisAction()
    {
        $codis = (new Codis(Codis::CLUSTER_MAIN))->get();
        $key = 'demo_key_001';
        $val = $codis->get($key);
        if (empty($val)) {
            $codis->set($key, 'demo_var_' . time(), 60);
        }
        var_dump($codis->get($key));
        exit;
    }

    public function sessionAction()
    {
        $session = new Session();
        $key = 'demo_key_002';
        $val = $session->get($key);
        if (empty($val)) {
            $session->set($key, 'demo_var_' . time());
        }
        var_dump($session->get($key));
        exit;
    }
}
