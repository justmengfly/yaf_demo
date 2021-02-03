<?php
namespace Helpers;

/**
 * AES 加密 解密类库
 */
class Aes {

    /**
     * 加密
     * @param String content 加密的字符串
     * @return HexString
     */
    public static function encrypt($content = '') {
        $key = \Yaf\Registry::get('config')->aes->key;
        $iv = \Yaf\Registry::get('config')->aes->iv;
        $data = openssl_encrypt($content, "AES-128-CBC", $key, 0, $iv);
        return self::urlsafeEncrypt($data);
    }

    /**
     * 解密
     * @param String content 解密的字符串
     * @return String
     */
    public static function decrypt($content) {
        $content = self::urlsafeDecrypt($content);
        $key = \Yaf\Registry::get('config')->aes->key;
        $iv = \Yaf\Registry::get('config')->aes->iv;
        return openssl_decrypt($content, "AES-128-CBC", $key, 0, $iv);
    }

    public static function urlsafeEncrypt($string) {
        return str_replace(array('+','/','='), array('-','_',''), $string);
    }

    public static function urlsafeDecrypt($string) {
        $string = str_replace(array('-','_'), array('+','/'), $string);
        $mod4 = strlen($string) % 4;
        if ($mod4) {
            $string .= substr('====', $mod4);
        }
        return $string;
    }

    // openssl AES 向量长度固定 16 位 这里为兼容建议固定长度为 16 位
    // 随机字符串
    public static function getRandomStr($length = 16) {
        $char_set = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
        shuffle($char_set);
        return implode('', array_slice($char_set, 0, $length));
    }

    /**
     * 生成每次请求的sign
     * @param array $data
     * @return string
     */
    public static function createSign($data = []) {
        // 1 按字段排序
        ksort($data);
        // 2拼接字符串数据  &
        $string = http_build_query($data);
        // 3通过aes来加密
        $string = self::encrypt($string);

        return $string;
    }

    /**
     * 检查sign是否正常
     * @param array $data
     * @param $data
     * @return boolen
     */
    public static function checkSign($data) {
        if(!isset($data['sign']) 
            || empty($data['sign'])
            || !isset($data['reqid'])
            || !isset($data['appid'])
            || !isset($data['platform'])
        ) {
            return false;
        }
        $str = self::decrypt($data['sign']);

        if(empty($str)) {
            return false;
        }

        // appid=xx&version=023300&....
        parse_str($str, $arr);
        if(!is_array($arr) 
            || !isset($arr['reqid'])
            || !isset($arr['appid'])
            || !isset($arr['platform'])
            || $arr['reqid'] != $data['reqid']
            || $arr['appid'] != $data['appid']
            || $arr['platform'] != $data['platform']
        ) {
            return false;
        }
        $timeout = \Yaf\Registry::get('config')->aes->timeout;
        if(\Yaf\Application::app()->environ() !== 'dev') {
            if ((time() - ceil($arr['ts'] / 1000)) > $timeout) {
                return false;
            }
        }
        return true;
    }
}