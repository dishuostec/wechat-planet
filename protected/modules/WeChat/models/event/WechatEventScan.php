<?php

class WechatEventScan extends WechatEvent
{
    public $Event = 'scan';

    public $ToUserName; //开发者微信号
    public $FromUserName; //发送方帐号（一个OpenID）
    public $CreateTime; //消息创建时间 （整型）
    public $MsgType; //消息类型，event
    public $EventKey; //事件KEY值，是一个32位无符号整数，即创建二维码时的二维码scene_id
    public $Ticket; //二维码的ticket，可用来换取二维码图片
}
