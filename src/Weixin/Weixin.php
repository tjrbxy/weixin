<?php

namespace Weixin;

use http\Exception;

/**
 * Class Weixin
 * @package Weixin
 *
 */
class Weixin
{
    private $api = array(
        'token' => 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',
        'send' => 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=%s'
    );
    private $config = array(
        'AppId' => '',
        'AppSecret' => '',
        'path' => './'
    );

    public function __construct($option = array())
    {
        if (!empty($option)) {
            foreach ($option as $key => $val) {
                if (empty($val)) continue;
                $this->config[$key] = $val;
            }
        }
    }

    private function getAccessToken()
    {
        try {
            $data = $this->getPhpFile();
            if ((isset($data['expire_time']) && $data['expire_time'] < time()) || empty($data)) {
                $url = sprintf($this->api['token'], $this->config['AppId'], $this->config['AppSecret']);
                $jsonContent = $this->httpGet($url);
                $response = json_decode($jsonContent, true);
                if (isset($response['errcode'])) {
                    throw new \Exception($response['errcode']);
                }
                if ($response['access_token']) {
                    $data['expire_time'] = time() + 7000;
                    $data['access_token'] = $response['access_token'];;
                    $this->setPhpFile(json_encode($data));
                }
            }
            return $data['access_token'];
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }


    public function send($openid = '', $tpl = '', $page = '', $formId = '', $data = array())
    {
        $data = array(
            "touser" => $openid,
            "template_id" => $tpl,
            "page" => $page,
            "form_id" => $formId,
            "data" => $data,
            "emphasis_keyword" => "keyword1.DATA"
        );
        $data = json_encode($data);
        $access_token = $this->getAccessToken();
        $url = sprintf($this->api['send'], $access_token);
        $response = $this->httpPost($url, $data);
        print_r($response);
    }


    private
    function getPhpFile()
    {
        $file = $this->config['path'] . $this->config['AppId'] . '.php';
        $content = trim(substr(file_get_contents($file), 15));
        return json_decode($content, true);
    }

    private
    function setPhpFile($content)
    {
        $file = $this->config['path'] . $this->config['AppId'] . '.php';
        $fp = fopen($file, "w");
        fwrite($fp, "<?php exit();?>" . $content);
        fclose($fp);
    }

    private
    function httpGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }

    private
    function httpPost($url, $data = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        if ($result == NULL) {
            return 0;
        }
        return $result;
    }
}