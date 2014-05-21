<?php

class WechatEventLocation extends WechatEvent
{
    public $Event = 'LOCATION';

    public $ToUserName; //开发者微信号
    public $FromUserName; //发送方帐号（一个OpenID）
    public $CreateTime; //消息创建时间 （整型）
    public $MsgType; //消息类型，event
    public $Latitude; //地理位置纬度
    public $Longitude; //地理位置经度
    public $Precision; //地理位置精度
}
