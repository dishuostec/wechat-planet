<?php

class WeChatModule extends CWebModule
{
    public $token = '';
    public $appId = '';
    public $appSecret = '';

    private $_instance = NULL;

    protected function init()
    {
        $id = $this->getId();

        $this->setImport(array(
            $id.'.behaviors.*',
            $id.'.components.*',
            $id.'.controllers.*',
            $id.'.models.*',
            $id.'.models.base.*',
            $id.'.models.event.*',
            $id.'.models.message.*',
        ));

        parent::init();
    }

    public function instance()
    {
        if (is_null($this->_instance)) {

            $this->_instance = $this->factory($this->token, $this->appId,
                $this->appSecret);
        }

        return $this->_instance;
    }

    public function factory($token, $appId, $appSecret)
    {
        $weChat = new WeChat();
        $weChat->token = $token;
        $weChat->appId = $appId;
        $weChat->appSecret = $appSecret;

        return $weChat;
    }
}