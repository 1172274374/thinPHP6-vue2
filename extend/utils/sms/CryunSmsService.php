<?php


// 创瑞云聚合数据短信发送

namespace utils\sms;

use think\facade\Cache;

class CryunSmsService
{

    protected static $sendUrl = 'http://api.1cloudsp.com/api/v2/single_send';  //短信接口的URL


    /**
     * 发送短信
     * @param array $data [发送参数]
     * @return  Bool
     */
    public static function sendSms($data)
    {
        $smsConf = array(
            'accesskey' => config('rds.cryun_accesskey'), //您申请的APPKEY
            'secret' => config('rds.cryun_secret'),
            'templateId' => config('rds.cryun_templateCode'),
            'sign' => config('rds.cryun_sign'),
            'mobile' => $data['mobile'], //接受短信的用户手机号码
            'content' => $data['code'] //您设置的模板变量，根据实际情况修改
        );
        $content = self::juhecurl(self::$sendUrl, $smsConf, 1); //请求发送短信
        if ($content) {
            $result = json_decode($content, true);
            if ($result['code'] == 0) {
                return true;
            } else {
                throw new \Exception($result['msg']);
            }
        } else {
            throw new \Exception('发送失败');
        }
    }

    /**
     * 请求接口返回内容
     * @param string $url [请求的URL地址]
     * @param string $params [请求的参数]
     * @param int $ipost [是否采用POST形式]
     * @return  string
     */
    public static function juhecurl($url, $params = false, $ispost = 0)
    {
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }
        $response = curl_exec($ch);
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }

}
