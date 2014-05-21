<?php

class WechatMessageRecognition extends WechatMessage
{
    public $MsgType = 'voice';

    public $MediaId; //语音消息媒体id，可以调用多媒体文件下载接口拉取该媒体
    public $Format; //语音格式：amr
    public $Recognition; //语音识别结果，UTF8编码
    public $MsgId; //消息id，64位整型
}
