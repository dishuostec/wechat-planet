<?php

abstract Class WechatEvent extends WechatData
{
    public $MsgType = 'event';

    public $Event = '';

    public function setEvent($value)
    {
        return FALSE;
    }
}