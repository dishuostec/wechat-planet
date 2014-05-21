<?php

class WechatEventSubscribe extends WechatEvent
{
    public $Event = 'subscribe';

    public $ToUserName; //开发者微信号
    public $FromUserName; //发送方帐号（一个OpenID）
    public $CreateTime; //消息创建时间 （整型）
    public $MsgType; //消息类型，event
    public $EventKey; //事件KEY值，qrscene_为前缀，后面为二维码的参数值
    public $Ticket; //二维码的ticket，可用来换取二维码图片
}
