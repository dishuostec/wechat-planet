<?php

/**
 * Class WechatMessageText
 *
 */
class WechatMessageText extends WechatMessage
{
    public $MsgType = 'text';

    protected $_json_format = array(
        'touser'  => 'ToUserName',
        'msgtype' => 'MsgType',
        'text'    => array(
            'content' => 'Content',
        ),
    );

    protected $_xml_format = array(
        'ToUserName'   => TRUE,
        'FromUserName' => TRUE,
        'CreateTime',
        'MsgType'      => TRUE,
        'Content'      => TRUE,
    );

    public $Content; //是，文本消息内容

    public function rules()
    {
        return parent::rules() + array(
            array('Content', 'required'),
            array('content', 'safe', 'on' => 'autofill'),
        );
    }

    public function getText()
    {
        return array(
            'Content' => $this->Content,
        );
    }

    public function setContent($value)
    {
        $this->Content = $value;
    }
}
