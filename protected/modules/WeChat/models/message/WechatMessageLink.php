<?php

class WechatMessageLink extends WechatMessage
{
    public $MsgType = 'link';

    public $Title; //消息标题
    public $Description; //消息描述
    public $Url; //消息链接
    public $MsgId; //消息id，64位整型
}
