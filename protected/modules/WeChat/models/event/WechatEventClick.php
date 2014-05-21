<?php

class WechatEventClick extends WechatEvent
{
    public $Event = 'CLICK';

    public $ToUserName; //开发者微信号
    public $FromUserName; //发送方帐号（一个OpenID）
    public $CreateTime; //消息创建时间 （整型）
    public $MsgType; //消息类型，event
    public $EventKey; //事件KEY值，与自定义菜单接口中KEY值对应
}
