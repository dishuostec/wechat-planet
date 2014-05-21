<?php

class WechatMessageVoice extends WechatMessage
{
    public $MsgType = 'voice';

    protected $_json_format = array(
        'touser'  => 'ToUserName',
        'msgtype' => 'MsgType',
        'voice'   => array(
            'media_id' => 'MediaId',
        ),
    );

    protected $_xml_format = array(
        'ToUserName',
        'FromUserName',
        'CreateTime',
        'MsgType',
        'Voice',
    );

    public $Format; //无，语音格式，如amr，speex等
    public $MediaId; //是，语音消息媒体id，可以调用多媒体文件下载接口拉取数据。

    public function getVoice()
    {
        return array(
            'MediaId' => $this->MediaId,
        );
    }
}
