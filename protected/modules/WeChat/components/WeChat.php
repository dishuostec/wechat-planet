<?php

/**
 * Class WechatCore
 *
 * @property string $token
 * @property string $appId
 * @property string $appSecret
 * @property string $accessToken
 */
class WeChat extends CApplicationComponent
{
    const CACHE_KEY_ACCESS_TOKEN = 'WeChat.cache.access_token';

    public $cacheId = 'cache';

    private $_token = '';
    private $_appId = '';
    private $_appSecret = '';
    private $_accessToken;
    private $_cache = NULL;

    public function createMessageText()
    {
        return new WechatMessageText();
    }

    public function parseXML($xml_string)
    {
        $post = xmlit::xml2array($xml_string);

        $obj = $this->getRequestDataObject($post);

        $obj->originXml = $xml_string;

        if (! ($obj instanceof WechatDataUnknown)) {
            $obj->values($post);
        }

        $this->onParseXmlDone(new CEvent($this, $obj));

        return $obj;
    }

    public function onParseXmlDone($event)
    {
        $this->raiseEvent('onParseXmlDone', $event);
    }

    /**
     * @param Array $array
     *
     * @return WechatData
     */
    public function getRequestDataObject(Array $array)
    {
        if (empty($array['MsgType'])) {
            $array['MsgType'] = '';
        }

        $category = 'Data';
        $type = strtolower($array['MsgType']);

        switch ($type) {
            case 'voice':
                if (isset($array['Recognition'])) {
                    $type = 'recognition';
                }
            // break; // 故意注视掉break
            case 'text':
            case 'image':
            case 'link':
            case 'location':
            case 'music':
            case 'news':
            case 'video':
                $category = 'Message';
                break;

            case 'event':
                $event = strtolower($array['Event']);
                switch ($event) {
                    case 'click':
                    case 'location':
                    case 'scan':
                    case 'subscribe':
                    case 'unsubscribe':
                        $category = 'Event';
                        $type = $event;
                        break;
                    default:
                        $type = 'unknown';
                }
                break;
            default:
                $type = 'unknown';
        }

        $className = 'Wechat'.$category.ucfirst($type);

        return new $className();
    }

    /**
     * @param $type
     *
     * @return WechatData
     */
    public static function createResponseDataObject($type)
    {
        $type = strtolower($type);

        switch ($type) {
            case 'text':
            case 'image':
            case 'link':
            case 'location':
            case 'music':
            case 'news':
            case 'recognition':
            case 'video':
            case 'voice':
                $category = 'Message';
                break;
            default:
                $category = 'Data';
                $type = 'unknown';
        }

        $className = 'Wechat'.$category.ucfirst($type);

        return new $className();
    }

    public function checkSignature($timestamp, $nonce, $signature)
    {
        $token = $this->_token;

        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $joinedStr = implode($tmpArr);
        $hash = sha1($joinedStr);

        return $signature === $hash;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->_token = $token;
    }

    /**
     * @return string
     */
    public function getAppId()
    {
        return $this->_appId;
    }

    /**
     * @param string $appId
     */
    public function setAppId($appId)
    {
        $this->_appId = $appId;
    }

    /**
     * @return string
     */
    public function getAppSecret()
    {
        return $this->_appSecret;
    }

    /**
     * @param string $appSecret
     */
    public function setAppSecret($appSecret)
    {
        $this->_appSecret = $appSecret;
    }

    public function getAccessToken($forceRefresh = FALSE)
    {
        Mod::trace('获取access token:'.$forceRefresh, 'wechat.core.accessToken');

        /**
         * @var ICache $cache
         */
        if ($this->_accessToken && ! $forceRefresh) {
            return $this->_accessToken;
        }

        if ($this->cacheId !== FALSE
            && ($cache = Mod::app()->getComponent($this->cacheId)) !== NULL
        ) {
            if (! $forceRefresh) {
                $this->_accessToken = $cache->get(self::CACHE_KEY_ACCESS_TOKEN);
                if (! empty($this->_accessToken)) {
                    return $this->_accessToken;
                }
            }
        }

        $json = $this->_getAccessToken();
        $this->_accessToken = Arr::get($json, 'access_token', '');

        if ($cache !== NULL) {
            $expires_in = Arr::get($json, 'expires_in', 7200);

            if (is_string($expires_in)) {
                Mod::log('expires_in string:'.$expires_in, CLogger::LEVEL_ERROR,
                    'wechat.core.accessToken');
                $expires_in = intval($expires_in, 10);
            }

            $cache->set(self::CACHE_KEY_ACCESS_TOKEN,
                $this->_accessToken,
                $expires_in
            );
        }
        if (empty($this->_accessToken)) {
            Mod::log('access_token为空'.json_encode(array(
                    'cache' => ($cache instanceof CCache),
                    'data'  => $json,
                )), CLogger::LEVEL_ERROR, 'wechat.core');
        }

        return $this->_accessToken;
    }

    /**
     * @return array
     */
    private function _getAccessToken()
    {
        $url = 'token?grant_type=client_credential&appid=APPID&secret=APPSECRET';

        $data = $this->apiGet($url, array(
            'APPID'     => $this->_appId,
            'APPSECRET' => $this->_appSecret,
        ));

        return $data;
    }

    public function apiGet($uri, $params = array())
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/'.strtr($uri, $params);

        $response = $this->curlGet($url);

        $header = $response->header;
        $content = $response->content;

        if (preg_match('%\bjson\b%', Arr::get($header, 'content_type'))) {
            // json
            $content = json_decode($content, TRUE);
        } else {
            // try convert json
            $json = json_decode($response->content, TRUE);
            if (! empty($json)) {
                $content = $json;
            }
        }

        if (is_array($content)) {
            $errCode = intval(Arr::get($content, 'errcode', 0), 10);

            if ($errCode !== 0) {
                $logMessage = sprintf('微信API调用失败[%s]:%s',
                    $uri,
                    json_encode($response)
                );

                Mod::log($logMessage, CLogger::LEVEL_ERROR, 'wechat.core');

                return array();
            }
        } elseif (empty($content)) {
            Mod::log('API 内容为空:'.json_encode($response),
                CLogger::LEVEL_ERROR,
                'wechat.core.api');
        }

        return $content;
    }

    /**
     * @param string $media_id
     * @return string|null
     */
    public function downloadMedia($media_id, $refreshAccessToken = FALSE)
    {
        $url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=ACCESS_TOKEN&media_id=MEDIA_ID';
        $url = strtr($url, array(
            'ACCESS_TOKEN' => $this->getAccessToken($refreshAccessToken),
            'MEDIA_ID'     => $media_id,
        ));

        $response = $this->downLoadPicContent($url);

        $content = $response->content;

        if (is_array($content)) {

            if ($content['errcode'] == 40001) {
                // access token 失效，更新 token 重新下载
                if (! $refreshAccessToken) {
                    return $this->downloadMedia($media_id, TRUE);
                }
            }
            // 错误
            $logMessage = sprintf('微信媒体文件API调用失败:%s',
                json_encode($response)
            );

            Mod::log($logMessage, CLogger::LEVEL_ERROR, 'wechat.core');
            return NULL;
        } else {
            if (empty($content)) {
                Mod::log('微信媒体文件下载错误:'.json_encode($response),
                    CLogger::LEVEL_ERROR,
                    'wechat.core');
            }
        }

        return $response->content;
    }

    private function downLoadPicContent($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($ch);
        $header = curl_getinfo($ch);

        $err = curl_errno($ch);
        if ($err) {
            Mod::log('curl empty content:'.json_encode(array(
                    'errno'   => $err,
                    'error'   => curl_error($ch),
                    'header'  => $header,
                    'content' => $content,
                )),
                CLogger::LEVEL_ERROR,
                'wechat.core.downloadPicContent');
        }
        curl_close($ch);

        if (empty($content)) {
            Mod::log('curl empty content'.json_encode($header),
                CLogger::LEVEL_ERROR,
                'wechat.core');
        }

        $contentType = Arr::get($header, 'content_type');

        if (in_array($contentType, array(
            'text/plain',
        ))
        ) {
            // 错误信息 json
            $content = json_decode($content, TRUE);
        }

        $response = new stdClass();

        $response->header = $header;
        $response->content = $content;

        return $response;
    }

    private function curlGet($url)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => TRUE, // return web page
            CURLOPT_HEADER         => FALSE, // don't return headers
            CURLOPT_FOLLOWLOCATION => TRUE, // follow redirects
            CURLOPT_ENCODING       => "", // handle all encodings
            CURLOPT_USERAGENT      => "WeChat callback server", // who am i
            CURLOPT_AUTOREFERER    => TRUE, // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 5, // timeout on connect
            CURLOPT_TIMEOUT        => 5, // timeout on response
            CURLOPT_MAXREDIRS      => 10, // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => FALSE, // Disabled SSL Cert checks
            CURLOPT_SSL_VERIFYHOST => FALSE
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);

        $header = curl_getinfo($ch);
        $content = curl_exec($ch);

        $err = curl_errno($ch);
        if ($err == 0) {
            Mod::log('curl empty content:'.json_encode(array(
                    'errno'   => $err,
                    'error'   => curl_error($ch),
                    'header'  => $header,
                    'content' => $content,
                )),
                CLogger::LEVEL_ERROR,
                'wechat.core.curl');
        }

        curl_close($ch);

        if (empty($content)) {
            Mod::log('curl empty content'.json_encode($header),
                CLogger::LEVEL_ERROR,
                'wechat.core.curl');
        }

        $response = new stdClass();

        $response->header = $header;
        $response->content = $content;

        return $response;
    }
}
