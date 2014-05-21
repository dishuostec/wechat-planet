<?php

class WechatEventUnsubscribe extends WechatEvent
{
    public $Event = 'unsubscribe';

    public $ToUserName; //开发者微信号
    public $FromUserName; //发送方帐号（一个OpenID）
    public $CreateTime; //消息创建时间 （整型）
    public $MsgType; //消息类型，event
}
