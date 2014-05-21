<?php

class CWechatController extends CController
{
    /**
     * @var WeChat
     */
    protected $wechat;
    protected $_requestDataObject;

    public function init()
    {
        $this->wechat = $this->module->instance();
    }

    public function actionEventClick($data)
    {
    }

    public function actionEventLocation($data)
    {
    }

    public function actionEventScan($data)
    {
    }

    public function actionEventSubscribe($data)
    {
    }

    public function actionEventUnsubscribe($data)
    {
    }

    public function actionMessageImage($data)
    {
    }

    public function actionMessageLink($data)
    {
    }

    public function actionMessageLocation($data)
    {
    }

    public function actionMessageRecognition($data)
    {
    }

    public function actionMessageText($data)
    {
    }

    public function actionMessageVideo($data)
    {
    }

    public function actionMessageVoice($data)
    {
    }

    public function actionDataUnknown($data)
    {
    }

    public function actionInvalid()
    {
        throw new CHttpException(400, 'Invalid Request.');
    }

    public function actionVerifyUrl()
    {
        echo Arr::get($_GET, 'echostr');
    }

    public function response($response)
    {
        if ($response instanceof WechatMessage) {
            $request = $this->_requestDataObject;
            $response->ToUserName = $request->FromUserName;
            $response->FromUserName = $request->ToUserName;
            $response->CreateTime = time();

            echo $response->asXml();
        }
    }

    protected function isRequestValid()
    {
        $timestamp = Arr::get($_GET, 'timestamp');
        $nonce = Arr::get($_GET, 'nonce');
        $signature = Arr::get($_GET, 'signature');

        return $this->wechat->checkSignature($timestamp, $nonce,
            $signature);
    }

    public function run($actionID)
    {
        if (! $this->isRequestvalid()) {
            $actionID = 'Invalid';
        } elseif (Mod::app()->request->isPostRequest) {
            // 数据请求
            $xml = $GLOBALS["HTTP_RAW_POST_DATA"];
            $obj = $this->wechat->parseXML($xml);

            $this->_requestDataObject = $obj;

            $actionID = get_class($obj);
        } else {
            // 验证数据
            $actionID = 'VerifyUrl';
        }

        parent::run($actionID);
    }

    public function createAction($actionID)
    {
        if (strncasecmp($actionID, 'wechat', 6)) {
            return parent::createAction($actionID);
        }

        $actionID = substr($actionID, 6);

        return new CInlineAction($this, $actionID);
    }

    public function getActionParams()
    {
        if (Mod::app()->request->isPostRequest) {
            return array(
                'data' => $this->_requestDataObject
            );
        }

        return parent::getActionParams();
    }
}