<?php

class WechatMessageLocation extends WechatMessage
{
    public $MsgType = 'location';

    public $Location_X; //地理位置维度
    public $Location_Y; //地理位置精度
    public $Scale; //地图缩放大小
    public $Label; //地理位置信息
    public $MsgId; //消息id，64位整型
}
